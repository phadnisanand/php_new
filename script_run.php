<?php

use Drupal\node\Entity\Node;
use Drupal\Core\Language\Language;

// *** EDIT THIS ***
// Mapping each language to correct block_content entity ID
$block_map = [
  'en' => 12,   // English block ID
  'fr' => 35,   // French block ID
  'de' => 41,   // German block ID
];

// *** EDIT THIS ***
// Content Type (machine name)
$content_type = 'article';

// Load all nodes of this type
$nids = \Drupal::entityQuery('node')
  ->condition('type', $content_type)
  ->execute();

foreach ($nids as $nid) {
  $node = Node::load($nid);

  // Loop through translations
  foreach ($node->getTranslationLanguages() as $langcode => $language) {

    // Load translation
    $tnode = $node->getTranslation($langcode);

    if (!$tnode->hasField('layout_builder__layout')) {
      continue;
    }

    // Get full layout defining array
    $sections = $tnode->get('layout_builder__layout')->getValue();

    if (empty($sections)) {
      continue;
    }

    // Replace ALL block UUIDs with correct block for this language
    foreach ($sections as &$section) {
      if (!isset($section['components'])) {
        continue;
      }

      foreach ($section['components'] as &$component) {

        if (!isset($component['configuration']['id'])) {
          continue;
        }

        // Only modify block_content based blocks
        if (str_contains($component['configuration']['id'], 'block_content')) {

          // Replace with correct block for this language
          $component['configuration']['id'] = "block_content:" . $block_map[$langcode];
        }
      }
    }

    // Save updated layout
    $tnode->set('layout_builder__layout', $sections);
    $tnode->save();

    echo "Updated node $nid ($langcode)\n";
  }
}

--> drush scr fix-layout-blocks.php

---------------------------------------------
Drush commands (must run before script)

drush php-e "\Drupal::entityDefinitionUpdateManager()->updateFieldStorageDefinition(
  \Drupal::service('entity_field.manager')->getFieldStorageDefinitions('node')['layout_builder__layout']
);"

Great — here is a safe, production-ready Drush script that fixes multilingual Layout Builder issues after Panelizer migration.

This script will:

Make the layout field translatable

Remove wrong block UUIDs from translations

Insert the correct block for each language (based on a mapping you define)

Re-save all translations so Drupal rebuilds layouts correctly.

drush config:set language.content_settings.node.article third_party_settings.layout_builder.allow_custom true -y
drush config:set language.content_settings.node.article third_party_settings.layout_builder.enabled true -y
