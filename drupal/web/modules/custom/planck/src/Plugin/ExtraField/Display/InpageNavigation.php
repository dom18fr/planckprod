<?php

namespace Drupal\planck\Plugin\ExtraField\Display;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Drupal\paragraphs\ParagraphInterface;

/**
 * @ExtraFieldDisplay(
 *   id = "inpage_navigation",
 *   label = @Translation("Inpage navigation"),
 *   bundles = {
 *     "node.page"
 *   },
 *   weight = 0,
 *   visible = true
 * )
 */
class InpageNavigation extends ExtraFieldDisplayBase
{

    /**
     * Builds a renderable array for the field.
     *
     * @param \Drupal\Core\Entity\ContentEntityInterface $entity
     *   The field's host entity.
     *
     * @return array
     *   Renderable array.
     * @throws MissingDataException
     */
    public function view(ContentEntityInterface $entity)
    {
        /** @var EntityReferenceFieldItemList $paragraphItemList */
        $paragraphItemList = $entity->get('field_paragraphs');
        $paragraphs = $paragraphItemList->referencedEntities();
        $items = [];
        foreach ($paragraphs as $delta => $paragraph) {
            /** @var ParagraphInterface $paragraph */
            if (
                'section_title' !== $paragraph->bundle()
                || '1' !== $paragraph->get('field_inpage_nav')->first()->getValue()['value']
            ) {
                continue;
            }
            $items[] = $this->getItem($paragraph); 
        }
        if (0 === count($items)) {
        
            return null;
        }
        
        return [
            '#theme' => 'inpage_nav',
            'items' => $items,
        ];
    }

    /**
     * @param ParagraphInterface $paragraph
     * @return array
     */
    protected function getItem($paragraph) {
        $title = null;
        $icon = null;
        $anchor = null;
        try {
            $titleItem = $paragraph->get('field_title')->first();
            $title = null !== $titleItem ? $titleItem->getValue()['value'] : null;
            $anchor = Html::getClass($title);
        } catch (\Exception $e) {}
        try {
            $iconItem = $paragraph->get('field_logo')->first();
            $icon = null !== $iconItem ? $iconItem->getValue()['value'] : null;
        } catch (\Exception $e) {}
        $item = [
            'title' => [
                '#markup' => $title,
            ],
            'icon' => [
                '#markup' => $icon,
            ],
            'anchor' => [
                '#markup' => $anchor,
            ],
        ];
        
        return $item;
    }
}