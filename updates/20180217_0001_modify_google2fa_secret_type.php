<?php

declare(strict_types=1);

namespace Adrenth\Security\Updates;

use Backend\Models\User;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use October\Rain\Database\Updates\Migration;
use Schema;

/** @noinspection AutoloadingIssuesInspection */

/**
 * Class ModifyGoogle2FaSecretType
 *
 * @package Adrenth\Security\Updates
 */
class ModifyGoogle2FaSecretType extends Migration
{
    public function up()
    {
        Schema::table('backend_users', function (Blueprint $table) {
            $table->text('google2fa_secret')->change();
        });

        /** @var Encrypter $encrypter */
        $encrypter = resolve(Encrypter::class);
        $users = User::query()->whereNotNull('google2fa_secret')->get();

        /** @var User $user */
        foreach ($users as $user) {
            try {
                $user->setAttribute('google2fa_secret', $encrypter->encrypt($user->getAttribute('google2fa_secret')));
                $user->save();
            } catch (Exception $e) {
            }
        }
    }

    public function down()
    {
        /** @var Encrypter $encrypter */
        $encrypter = resolve(Encrypter::class);
        $users = User::query()->whereNotNull('google2fa_secret')->get();

        /** @var User $user */
        foreach ($users as $user) {
            try {
                $user->setAttribute('google2fa_secret', $encrypter->decrypt($user->getAttribute('google2fa_secret')));
                $user->save();
            } catch (Exception $e) {
            }
        }

        Schema::table('backend_users', function (Blueprint $table) {
            $table->string('google2fa_secret')->change();
        });
    }
}
