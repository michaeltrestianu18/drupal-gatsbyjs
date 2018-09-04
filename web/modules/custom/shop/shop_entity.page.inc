<?php

/**
 * @file
 * Contains shop_entity.page.inc.
 *
 * Page callback for Shop entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Shop entity templates.
 *
 * Default template: shop_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_shop_entity(array &$variables) {
  // Fetch ShopEntity Entity Object.
  $shop_entity = $variables['elements']['#shop_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
