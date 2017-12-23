<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Backend\Widgets\Lists;

/**
 * Class ListInjectRowClass
 *
 * @package Adrenth\Security\Classes\EventListeners\Backend
 */
class ListInjectRowClass
{
    /**
     * @param Lists $widget
     * @param mixed $record
     * @return string
     */
    public function handle(Lists $widget, $record): string
    {
        return '';
    }
}
