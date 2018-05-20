<?php

namespace Drupal\planck;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;

/**
 * Class ViewModeHandler
 * @package Drupal\planck\ViewModeHandler
 */
class ViewModeHandler 
{

    /**
     * @param $view_mode
     * @param EntityInterface $entity
     * @param $context
     */
    public function entityViewModeAlter(&$view_mode, EntityInterface $entity, $context)
    {
        /** @var FieldableEntityInterface $entity */
        try {
            if (
                'node' !== $entity->getEntityTypeId()
                || 'page' !== $entity->bundle()
                || null === $entity->get('field_page_type')->first()
                || 'event' !== $entity->get('field_page_type')->first()->getValue()['value']
                || 'teaser' !== $view_mode
            ) {
                return;
            }
        } catch (MissingDataException $e) {
            return;
        }

        $view_mode = 'teaser_event';
    }
}