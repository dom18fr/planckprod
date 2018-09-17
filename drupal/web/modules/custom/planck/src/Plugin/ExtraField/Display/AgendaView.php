<?php

namespace Drupal\planck\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * @ExtraFieldDisplay(
 *   id = "agenda_view",
 *   label = @Translation("Agenda view"),
 *   bundles = {
 *     "paragraph.agenda"
 *   },
 *   weight = 0,
 *   visible = true
 * )
 */
class AgendaView extends ExtraFieldDisplayFormattedBase
{
    /**
     * @param ContentEntityInterface $entity
     * @return array
     */
    public function viewElements(ContentEntityInterface $entity)
    {
        $arg = 'all';
        $selector = null;
        /** @var ParagraphInterface $entity */
        /** @var NodeInterface $parentEntity */
        $parentEntity = $entity->getParentEntity();
        try {
            if (
                'node' === $parentEntity->getEntityTypeId()
                && null !== $parentEntity->get('field_page_type')->first()
                && 'group' === $parentEntity->get('field_page_type')->first()->getValue()['value']
            ) {
                $arg = $parentEntity->id();
            } else {
                $selector = [
                    '#type' => 'select',
                    '#options' => $this->getGroupOptions(),
                    '#weight' => 0,
                    '#attributes' => [
                        'data-agenda' => 'band-selector',
                    ],
                ];
            }
        } catch (MissingDataException $e) {
        }
        $view = views_embed_view('agenda');
        $view['#arguments'] = [$arg];
        return [
            [
                'selector' => $selector,
                'view' => [
                    $view,
                    '#weight' => 1,
                ],
                '#attached' => [
                    'library' => 'planck/agenda'
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getGroupOptions()
    {
        $entityQuery = \Drupal::entityQuery('node');
        $entityQuery->condition('field_page_type', 'group');
        $result = $entityQuery->execute();
        $groups = Node::loadMultiple(array_values($result));
        $options = [
            'all' => t('-- Choose a band --'),
        ];
        foreach ($groups as $group) {
            /** @var NodeInterface $group */
            $options[$group->id()] = $group->getTitle();
        }
        
        return $options;
    }
}