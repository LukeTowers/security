<?php

declare(strict_types=1);

namespace Adrenth\Security;

use Adrenth\Security\Classes\EventSubscribers\BackendEventSubscriber;
use Backend\Controllers\Auth;
use Backend\Controllers\Users;
use Backend\Models\User;
use Config;
use Event;
use Illuminate\Http\Request;
use Input;
use Route;
use System\Classes\PluginBase;

/**
 * Class Plugin
 *
 * @package Adrenth\Security
 */
class Plugin extends PluginBase
{
    /**
     * This plugin should have elevated privileges.
     *
     * @var bool
     */
    public $elevated = true;

    /**
     * {@inheritdoc}
     */
    public function pluginDetails(): array
    {
        return [
            'name' => 'adrenth.security::lang.plugin.name',
            'description' => 'adrenth.security::lang.plugin.description',
            'author' => 'Alwin Drenth',
            'icon' => 'icon-link',
            'homepage' => 'http://octobercms.com/plugin/adrenth-security',
        ];
    }

    public function boot()
    {
        Users::extend(function (Users $controller) {
            $controller->addViewPath(plugins_path('adrenth/security/controllers/users'));

            $controller->addDynamicMethod('onSetupTwoFactorAuthenticationPopup', function () use ($controller) {
                return $controller->makePartial('google2fa_secret_popup', [
                    'user' => User::findOrFail(Input::get('id'))
                ]);
            });
        });

        Event::subscribe(BackendEventSubscriber::class);
    }

    /**
     * {@inheritdoc}
     */
    public function registerPermissions(): array
    {
        return [
            'adrenth.security.access_redirects' => [
                'label' => 'adrenth.security::lang.permission.access_redirects.label',
                'tab' => 'adrenth.security::lang.permission.access_redirects.tab',
            ],
        ];
    }
}
