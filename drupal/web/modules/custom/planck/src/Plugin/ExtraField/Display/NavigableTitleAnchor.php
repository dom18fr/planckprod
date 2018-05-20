<?php

namespace Drupal\planck\Plugin\ExtraField\Display;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;

/**
 * @ExtraFieldDisplay(
 *   id = "inpage_navigation_anchor",
 *   label = @Translation("Inpage navigation anchor"),
 *   bundles = {
 *     "paragraph.section_title"
 *   },
 *   weight = 0,
 *   visible = true
 * )
 */
class NavigableTitleAnchor extends ExtraFieldDisplayFormattedBase
{
    /**
     * Returns the renderable array of the field item(s).
     *
     * @param \Drupal\Core\Entity\ContentEntityInterface $entity
     *   The field's host entity.
     *
     * @return array
     *   A renderable array of field elements. If this contains children, the
     *   field output will be rendered as a multiple value field with each child
     *   as a field item.
     * @throws \Drupal\Core\TypedData\Exception\MissingDataException
     */
    public function viewElements(ContentEntityInterface $entity)
    {
        $renderable = [];
        if ('1' !== $entity->get('field_inpage_nav')->first()->getValue()['value']) {
            
            return $renderable;
        }
        $anchor = Html::getClass($entity->get('field_title')->first()->getValue()['value']);
        $renderable = [
            '#markup' => '<a id="' . $anchor . '"></a>'
        ];
        
        return $renderable;
    }
}