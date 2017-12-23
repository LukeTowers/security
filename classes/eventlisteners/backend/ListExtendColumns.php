<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Backend\Widgets\Lists;
use System\Models\EventLog;

/**
 * Class ListExtendColumns
 *
 * @package Adrenth\Security\Classes\EventListeners\Backend
 */
class ListExtendColumns
{
    /**
     * @param Lists $widget
     */
    public function handle(Lists $widget): void
    {
        if ($widget->model instanceof EventLog) {
            $widget->addColumns([
                'level' => [
                    'label' => 'Level',
                ]
            ]);
        }
    }
}
