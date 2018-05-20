<?php

namespace Drupal\planck;

/**
 * Class ThemeManager
 * @package Drupal\planck
 */
class ThemeManager 
{
    /**
     * @param $existing
     * @param $type
     * @param $theme
     * @param $path
     * @return array
     */
    public function theme($existing, $type, $theme, $path)
    {
        $themes = [];
        
        $themes['inpage_nav'] = [
            'render element' => 'element',
            'template' => 'inpage-nav',
        ];
        
        return $themes;
    }
}