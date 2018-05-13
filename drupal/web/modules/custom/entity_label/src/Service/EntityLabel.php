<?php

namespace Drupal\entity_label\Service;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Url;

/**
 * Class EntityLabel
 * @package Drupal\entity_label\Service
 *
 * Manage extrafield declaration and render logic to display entity labels.
 */
class EntityLabel {

  /**
   * @return array
   */
  public function getXfieldDefinitions() {
    $xfields = [];
    /** @var EntityTypeManagerInterface $manager */
    $type_manager = \Drupal::entityTypeManager();
    foreach ($type_manager->getDefinitions() as $entity_type => $def) {

      /** @var EntityTypeBundleInfoInterface $bundle_info */
      $bundle_info = \Drupal::service('entity_type.bundle.info');
      if (
        'content' === $def->getGroup()
        && $label = $def->getKey('label')
      ) {
        $bundles = array_keys($bundle_info->getBundleInfo($entity_type));
        foreach ($bundles as $bundle) {

          $xfields[$entity_type][$bundle]['display']['xfield_label'] = [
            'label' => ucfirst($label),
            'description' => t('Display' . ' ' . $entity_type . ' ' . $label),
            'weight' => 0,
            'visible' =>  false,
          ];

          $xfields[$entity_type][$bundle]['display']['xfield_linked_label'] = [
            'label' => 'Linked ' . $label,
            'description' => t('Display linked ' . $entity_type . ' ' . $label),
            'weight' => 0,
            'visible' =>  false,
          ];
        }
      }
    }

    return $xfields;
  }

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\ContentEntityBase $entity
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   * @param $view_mode
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \InvalidArgumentException
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function viewLabels(array &$build, ContentEntityBase $entity, EntityViewDisplayInterface $display, $view_mode) {
    $xfields = ['label', 'linked_label'];
    foreach ($xfields as $key) {
      if (!$display->getComponent('xfield_' . $key)) {
        continue;
      }
      /** @var EntityTypeManagerInterface $manager */
      $type_manager = \Drupal::entityTypeManager();
      $def = $type_manager->getDefinition($entity->getEntityTypeId());
      $label_value = $entity->get($def->getKey('label'))
        ->first()
        ->getValue()['value'];

      // DIRTY FIX : see field_group_remove_empty_display_groups in field_group.module line 665
      // Add a dummy nesting level to ensure field_group will recognize it
      // as a field.
      $build['xfield_' . $key] = [
        [
          '#theme' => 'entity_' . $key,
          '#label' => $label_value,
          '#entity_type' => $entity->getEntityTypeId(),
          '#bundle' => $entity->bundle(),
          '#entity' => $entity,
          '#view_mode' => $build['#view_mode'],
        ]
      ];
      if ('linked_label' === $key) {
        $uri = 'entity:' . $entity->getEntityTypeId() . '/' . $entity->id();
        $build['xfield_' . $key][0]['#url'] = Url::fromUri($uri);
      }
    }
  }

  /**
   * @param $variables
   * @param $hook
   */
  public function preprocessVariables(&$variables, $hook) {
    $variables['label'] = $variables['element']['#label'];
    if ('entity_linked_label' === $hook) {
      $variables['url'] = $variables['element']['#url'];
    }
  }

  /**
   * @param array $suggestions
   * @param array $variables
   * @param $hook
   */
  public function alterSuggestions(array &$suggestions, array $variables, $hook) {
    $element = $variables['element'];
    $suggestions[] = $hook . '__' . $element['#entity_type'];
    $suggestions[] = $hook . '__' . $element['#entity_type'] . '__'
      . $element['#bundle'];
    $suggestions[] = $hook . '__' . $element['#entity_type'] . '__'
      . $element['#bundle'] . '__' . $element['#view_mode'];
  }
}
