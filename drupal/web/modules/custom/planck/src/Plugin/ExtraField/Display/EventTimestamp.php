<?php

namespace Drupal\planck\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * @ExtraFieldDisplay(
 *   id = "event_timestamp",
 *   label = @Translation("Event timestamp"),
 *   bundles = {
 *     "node.page"
 *   },
 *   weight = 0,
 *   visible = false
 * )
 */
class EventTimestamp extends ExtraFieldDisplayBase
{
    /**
     * Builds a renderable array for the field.
     *
     * @param \Drupal\Core\Entity\ContentEntityInterface $entity
     *   The field's host entity.
     *
     * @return array
     *   Renderable array.
     */
    public function view(ContentEntityInterface $entity)
    {
        $timestamp = null;
        try {
            $timestamp = [
                '#plain_text' => $entity->get('field_event_date')->first()->getValue()['value']
            ];
        } catch (\Exception $e) {
        }
        
        return $timestamp;
    }
}