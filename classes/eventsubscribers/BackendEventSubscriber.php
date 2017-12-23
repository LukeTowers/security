<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventSubscribers;

use Adrenth\Security\Classes\Contracts\EventSubscriber;
use Adrenth\Security\Classes\EventListeners\Backend\PageBeforeDisplay;
use Adrenth\Security\Classes\EventListeners\Backend\FormExtendFields;
use Adrenth\Security\Classes\EventListeners\Backend\ListExtendColumns;
use Adrenth\Security\Classes\EventListeners\Backend\ListInjectRowClass;
use Adrenth\Security\Classes\EventListeners\Backend\UserLogin;
use October\Rain\Events\Dispatcher;

/**
 * Class BackendEventSubscriber
 *
 * @package Adrenth\Security\Classes\EventSubscribers
 */
class BackendEventSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen('backend.form.extendFields', FormExtendFields::class);
        $dispatcher->listen('backend.list.injectRowClass', ListInjectRowClass::class);
        $dispatcher->listen('backend.list.extendColumns', ListExtendColumns::class);
        $dispatcher->listen('backend.user.login', UserLogin::class);
        $dispatcher->listen('backend.page.beforeDisplay', PageBeforeDisplay::class);
    }
}
