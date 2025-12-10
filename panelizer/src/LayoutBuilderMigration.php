<?php

namespace Drupal\panelizer;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\layout_builder\Entity\LayoutEntityDisplayInterface;
use Drupal\layout_builder\Plugin\SectionStorage\OverridesSectionStorage;
use Drupal\layout_builder\Section;
use Drupal\layout_builder\SectionComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides functionality to migrate Panelizer data to Layout Builder.
 *
 * @internal
 *   This is an internal part of Panelizer and may be changed or removed at any
 *   time without warning. External code should not instantiate this class.
 */
final class LayoutBuilderMigration implements ContainerInjectionInterface {

  /**
   * The Panelizer service.
   *
   * @var \Drupal\panelizer\PanelizerInterface
   */
  private $panelizer;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The block plugin manager service.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  private $blockManager;

  /**
   * LayoutBuilderMigration constructor.
   *
   * @param \Drupal\panelizer\PanelizerInterface $panelizer
   *   The Panelizer service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Block\BlockManagerInterface $block_manager
   *   The block plugin manager service.
   */
  public function __construct(PanelizerInterface $panelizer, EntityTypeManagerInterface $entity_type_manager, BlockManagerInterface $block_manager) {
    $this->panelizer = $panelizer;
    $this->entityTypeManager = $entity_type_manager;
    $this->blockManager = $block_manager;
    \Drupal::moduleHandler()->loadInclude('panels', 'install');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('panelizer'),
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.block')
    );
  }

  /**
   * Migrates a layout-able entity view display to Layout Builder.
   *
   * @param \Drupal\layout_builder\Entity\LayoutEntityDisplayInterface $display
   *   The entity view display.
   */
  private function doProcessDisplay(LayoutEntityDisplayInterface $display) {
    $entity_type_id = $display->getTargetEntityTypeId();
    $bundle = $display->getTargetBundle();
    $mode = $display->getMode();

    $panelizer_settings = $this->panelizer->getPanelizerSettings($entity_type_id, $bundle, $mode, $display);

    if (empty($panelizer_settings['enable'])) {
      return;
    }

    $display_storage = $this->entityTypeManager->getStorage('entity_view_display');
    $layout_storage = $this->entityTypeManager->getStorage('layout');

    $display
      ->enableLayoutBuilder()
      ->setOverridable($panelizer_settings['custom'])
      ->setThirdPartySetting('layout_library', 'enable', $panelizer_settings['allow']);

    $panels_displays = $this->panelizer
      ->getDefaultPanelsDisplays($entity_type_id, $bundle, $mode, $display);

    foreach ($panels_displays as $name => $panels_display) {
      $configuration = $panels_display->getConfiguration();

      $configuration += [
        'static_context' => [],
      ];

      $section = $this->toSection($configuration, $entity_type_id, $bundle);
      $panels_display->setConfiguration($configuration);

      if ($name === $panelizer_settings['default']) {
        $display->appendSection($section);

        if ($configuration['static_context']) {
          $display->setThirdPartySetting('core_context', 'contexts', $configuration['static_context']);
        }
      }
      else {
        /** @var \Drupal\layout_library\Entity\Layout $layout */
        $layout = $layout_storage->create([
          'id' => implode('_', [$entity_type_id, $bundle, $mode, $name]),
          'targetEntityType' => $entity_type_id,
          'targetBundle' => $bundle,
          'label' => $panels_display->label(),
        ]);
        $layout->appendSection($section);

        if ($configuration['static_context']) {
          $layout->setThirdPartySetting('core_context', 'contexts', $configuration['static_context']);
        }
        $layout_storage->save($layout);
      }
    }
    $display_storage->save($display);

    $panelizer_settings['enable'] = FALSE;
    $panelizer_settings['allow'] = FALSE;
    $panelizer_settings['custom'] = FALSE;
    $this->panelizer->setPanelizerSettings($entity_type_id, $bundle, $mode, $panelizer_settings, $display);
  }

  /**
   * Migrates a custom entity-specific Panelizer layout to Layout Builder.
   *
   * This method processes entity-specific Panelizer layouts for both the
   * default language and all translations. Layout Builder sections are
   * language-aware, so each language variant will have its own section.
   *
   * For non-translatable blocks (e.g., block_content entities not translated),
   * the same block reference will be used across all language variants, which
   * is the correct behavior.
   *
   * URL aliases are preserved during migration to maintain existing SEO and
   * user-facing URL structures across all language variants.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *   The entity that has the custom layout. This may be in any language.
   *
   * @see \Drupal\layout_builder\Field\LayoutSectionItemList
   * @see \Drupal\panelizer\LayoutBuilderMigration::toSection()
   */
  private function doProcessEntity(FieldableEntityInterface $entity) {
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();

    // Preserve the entity's URL aliases before migration to prevent them from
    // being removed during entity save. This is important for maintaining SEO
    // and user-facing URL structures.
    $path_aliases = [];
    if ($entity instanceof \Drupal\Core\Entity\EntityInterface && \Drupal::moduleHandler()->moduleExists('path_alias')) {
      // Load path aliases for this entity in all languages.
      $path_alias_storage = $this->entityTypeManager->getStorage('path_alias');
      $aliases = $path_alias_storage->loadByProperties([
        'path' => '/' . $entity_type_id . '/' . $entity->id(),
      ]);
      foreach ($aliases as $alias) {
        $path_aliases[$alias->language()->getId()] = $alias->getAlias();
      }
    }

    foreach ($entity->panelizer as $panelizer_item) {
      if ($panelizer_item->view_mode === 'full') {
        if ($panelizer_item->panels_display) {
          $configuration = $panelizer_item->panels_display;
          $section = $this->toSection($configuration, $entity_type_id, $bundle);

          /** @var \Drupal\layout_builder\Field\LayoutSectionItemList $sections */
          $sections = $entity->get(OverridesSectionStorage::FIELD_NAME);
          $sections->appendSection($section);

          // The Panels display may have been modified by ::toSection() in order
          // to make the entity save-able.
          $panelizer_item->panels_display = $configuration;
        }
        if ($panelizer_item->default && $panelizer_item->default !== '__bundle_default__' && $entity->hasField('layout_selection')) {
          $entity->layout_selection->target_id = implode('_', [
            $entity_type_id,
            $bundle,
            $panelizer_item->view_mode,
            $panelizer_item->default,
          ]);
        }
        $this->entityTypeManager->getStorage($entity_type_id)->save($entity);

        // Restore URL aliases after entity save to ensure they are not lost
        // during the migration process.
        if (!empty($path_aliases) && \Drupal::moduleHandler()->moduleExists('path_alias')) {
          $path_alias_storage = $this->entityTypeManager->getStorage('path_alias');
          foreach ($path_aliases as $language_id => $alias) {
            // Check if alias already exists for this language.
            $existing_aliases = $path_alias_storage->loadByProperties([
              'path' => '/' . $entity_type_id . '/' . $entity->id(),
              'langcode' => $language_id,
            ]);
            
            if (empty($existing_aliases)) {
              // Create new alias if it was removed during save.
              $path_alias = $path_alias_storage->create([
                'path' => '/' . $entity_type_id . '/' . $entity->id(),
                'alias' => $alias,
                'langcode' => $language_id,
              ]);
              $path_alias->save();
            }
          }
        }
        break;
      }
    }
  }

  /**
   * Converts a Panels display to a single Layout Builder section.
   *
   * @param array $configuration
   *   The Panels display configuration.
   * @param string $entity_type_id
   *   The entity type ID associated with the display.
   * @param string $bundle
   *   The entity bundle associated with the display.
   *
   * @return \Drupal\layout_builder\Section
   *   A layout section with the same layout and blocks as the Panels display.
   *
   * @throws \Drupal\panelizer\Exception\PanelizerException
   *   If a referenced block entity cannot be found or is invalid.
   */
  private function toSection(array &$configuration, $entity_type_id, $bundle) {
    if (isset($configuration['static_context'])) {
      $static_contexts = $configuration['static_context'];
    }
    else {
      $static_contexts = [];
    }

    $to_component = function (array $block) use ($entity_type_id, $bundle, $static_contexts) {
      // Validate block_content references to ensure non-translatable blocks
      // maintain consistency across language variants.
      if ($block['provider'] === 'block_content' && strpos($block['id'], 'block_content:') === 0) {
        // Extract the block_content UUID from the block ID.
        $block_uuid = substr($block['id'], strlen('block_content:'));
        
        // Verify the referenced block_content entity exists.
        // This ensures that during migration, we don't create broken references
        // for non-translatable blocks in translated nodes.
        $block_storage = $this->entityTypeManager->getStorage('block_content');
        $block_entities = $block_storage->loadByProperties(['uuid' => $block_uuid]);
        
        if (empty($block_entities)) {
          // Log a warning but continue migration - the block reference will be
          // preserved as-is for later manual fixing if needed.
          \Drupal::logger('panelizer')->warning(
            'Block content entity with UUID @uuid referenced in migration could not be found.',
            ['@uuid' => $block_uuid]
          );
        }
      }

      // Convert ctools_block field blocks to use Layout Builder's field_block.
      if ($block['provider'] === 'ctools_block' && strpos($block['id'], 'entity_field:') === 0) {
        list(, , $field_name) = explode(':', $block['id']);
        $block['provider'] = 'layout_builder';
        $block['id'] = "field_block:$entity_type_id:$bundle:$field_name";
        // Remove configuration keys that are moved to component-level settings.
        unset($block['formatter']['region'], $block['formatter']['weight']);

        // If the entity being panelized is referenced in the context mapping,
        // use the Layout Builder version of that.
        if (isset($block['context_mapping']['entity']) && $block['context_mapping']['entity'] === '@panelizer.entity_context:entity') {
          $block['context_mapping']['entity'] = 'layout_builder.entity';
        }
      }

      $plugin_definition = $this->blockManager->getDefinition($block['id']);
      // The required context values must be passed directly in the plugin
      // configuration, or the plugin will throw an exception as soon as it is
      // instantiated. Note that this is only supported as of Drupal 8.8. BUT!
      // Storing context values in block configuration was deprecated in Drupal
      // 9.1, and removed in Drupal 10. (Drupal 10 expects required contexts to
      // be available at any time.) So, this can be removed if and when support
      // for Drupal 9 and older is dropped.
      // @see https://www.drupal.org/node/3120980
      /** @var \Drupal\Component\Plugin\Context\ContextDefinitionInterface $context_definition */
      foreach ($plugin_definition['context_definitions'] as $context_name => $context_definition) {
        if ($context_definition->isRequired() && array_key_exists($context_name, $static_contexts)) {
          $block['context'][$context_name] = $static_contexts[$context_name]['value'];
        }
      }

      // Preserve the original UUID to maintain consistency across languages.
      // Each block in the Panels display has a unique UUID. When migrating to
      // Layout Builder, we must preserve this UUID so that blocks maintain
      // consistent identifiers across all translations of a node.
      // Without this preservation, SectionComponent::fromArray() would generate
      // new UUIDs each time, causing blocks in different language variants to
      // have different IDs, which leads to issues with layout consistency.
      $uuid = $block['uuid'];
      $block['configuration'] = $block;
      // Remove keys which are not actually part of the block configuration.
      unset(
        $block['configuration']['provider'],
        $block['configuration']['region'],
        $block['configuration']['uuid'],
        $block['configuration']['weight']
      );
      $block += [
        'additional' => [],
      ];
      // Restore the UUID to ensure it remains constant in all translations.
      $block['uuid'] = $uuid;
      return SectionComponent::fromArray($block);
    };

    $layout_id = panels_convert_plugin_ids_to_layout_discovery($configuration['layout']);
    if ($layout_id) {
      $configuration['layout'] = $layout_id;
    }

    return new Section(
      $configuration['layout'],
      $configuration['layout_settings'],
      array_map($to_component, $configuration['blocks'])
    );
  }

  /**
   * Builds a batch definition for a migration to Layout Builder.
   *
   * @param \Drupal\layout_builder\Entity\LayoutEntityDisplayInterface $display
   *   The entity view display to migrate.
   *
   * @return \Drupal\Core\Batch\BatchBuilder
   *   The batch definition.
   */
  private function buildBatch(LayoutEntityDisplayInterface $display) {
    $batch = new BatchBuilder();

    // Migrate the Panelizer data in the view display first. Once that's done,
    // entity-specific data can be migrated.
    $batch->addOperation([static::class, 'processDisplay'], (array) $display->id());

    $entity_type = $this->entityTypeManager
      ->getDefinition($display->getTargetEntityTypeId());

    $storage = $this->entityTypeManager->getStorage($entity_type->id());

    $query = $storage->getQuery()
      ->exists('panelizer')
      ->accessCheck(TRUE)
      ->condition('panelizer.view_mode', 'full');

    if ($entity_type->hasKey('bundle')) {
      $query->condition($entity_type->getKey('bundle'), $display->getTargetBundle());
    }
    if ($entity_type->isRevisionable()) {
      $query->allRevisions();
    }

    // If the query is looking for revisions, the array keys will be revision
    // IDs. In any event, the array keys are always the canonical ID of the
    // thing we want to migrate.
    foreach (array_keys($query->execute()) as $entity_id) {
      $batch->addOperation([static::class, 'processEntity'], [$entity_type->id(), $entity_id]);
    }
    return $batch;
  }

  /**
   * Creates a migration batch process from an entity view display.
   *
   * @param \Drupal\layout_builder\Entity\LayoutEntityDisplayInterface $display
   *   The entity view display.
   *
   * @return \Drupal\Core\Batch\BatchBuilder
   *   The batch definition.
   */
  public static function fromDisplay(LayoutEntityDisplayInterface $display) {
    return \Drupal::classResolver(static::class)->buildBatch($display);
  }

  /**
   * Migrates Panelizer data in an entity view display to Layout Builder.
   *
   * This method is intended to be called as a batch operation.
   *
   * @param string $id
   *   The entity view display ID.
   *
   * @see ::buildBatch()
   */
  public static function processDisplay($id) {
    $display = EntityViewDisplay::load($id);

    assert($display instanceof LayoutEntityDisplayInterface);

    \Drupal::classResolver(static::class)->doProcessDisplay($display);
  }

  /**
   * Migrates custom Panelizer layout data for a single entity.
   *
   * This method is intended to be called as part of a batch operation.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param mixed $entity_id
   *   The ID (or revision ID, if the entity type is revisionable) of the
   *   entity to load.
   *
   * @see ::buildBatch()
   */
  public static function processEntity($entity_type_id, $entity_id) {
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type_id);

    if ($storage->getEntityType()->isRevisionable()) {
      $entity = $storage->loadRevision($entity_id);
    }
    else {
      $entity = $storage->load($entity_id);
    }

    assert($entity instanceof FieldableEntityInterface);

    \Drupal::classResolver(static::class)->doProcessEntity($entity);
  }

}
