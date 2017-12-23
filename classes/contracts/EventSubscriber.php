<?php

namespace Adrenth\Security\Classes\Contracts;

use October\Rain\Events\Dispatcher;

/**
 * Interface EventSubscriber
 *
 * @package Adrenth\Security\Classes\Contracts
 */
interface EventSubscriber
{
    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe(Dispatcher $dispatcher): void;
}
