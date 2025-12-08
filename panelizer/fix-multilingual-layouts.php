<?php

use Drupal\node\Entity\Node;

// ==== EDIT THESE VARIABLES ====
$content_type = 'article'; // Machine name of content type
$block_map = [
  'en' => 12,
  'fr' => 35,
  'de' => 41,
];
// ==============================

// Load all nodes of the content type
$nids = \Drupal::entityQuery('node')
  ->condition('type', $content_type)
  ->execute();

foreach ($nids as $nid) {
  $node = Node::load($nid);

  // Loop through translations
  foreach ($node->getTranslationLanguages() as $langcode => $language) {
    $tnode = $node->getTranslation($langcode);

    if (!$tnode->hasField('layout_builder__layout')) {
      continue;
    }

    $sections = $tnode->get('layout_builder__layout')->getValue();
    if (empty($sections)) {
      continue;
    }

    // Replace block_content IDs for this language
    foreach ($sections as &$section) {
      if (!isset($section['components'])) {
        continue;
      }
      foreach ($section['components'] as &$component) {
        if (!isset($component['configuration']['id'])) {
          continue;
        }

        // Only replace block_content blocks
        if (str_contains($component['configuration']['id'], 'block_content')) {
          if (isset($block_map[$langcode])) {
            $component['configuration']['id'] = "block_content:" . $block_map[$langcode];
          }
        }
      }
    }

    // Save updated translation
    $tnode->set('layout_builder__layout', $sections);
    $tnode->save();

    echo "Updated node $nid ($langcode)\n";
  }
}

echo "All nodes processed.\n";
