<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Backend\Controllers\Users;
use Backend\Models\User;
use Backend\Widgets\Form;

/**
 * Class FormExtendFields
 *
 * @package Adrenth\Security\Classes\EventListeners
 */
class FormExtendFields
{
    /**
     * @param Form $form
     */
    public function handle(Form $form): void
    {
        // TODO: Check whether 2fa is enabled or not

        if ($form->model instanceof User
            && $form->getController() instanceof Users
        ) {
            $form->addTabFields([
                '2fa_setup_button' => [
                    'label' => '',
                    'type' => 'partial',
                    'context' => [
                        'myaccount'
                    ]
                ],
            ]);
        }
    }
}
