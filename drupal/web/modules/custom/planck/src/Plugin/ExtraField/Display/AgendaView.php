<?php

namespace Drupal\planck\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;

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
        return views_embed_view('agenda');
    }
}