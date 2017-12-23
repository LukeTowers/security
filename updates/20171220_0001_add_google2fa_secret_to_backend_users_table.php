<?php

declare(strict_types=1);

namespace Adrenth\Security\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/** @noinspection AutoloadingIssuesInspection */

/**
 * Class AddGoogle2FaSecretToBackendUsersTable
 *
 * @package Adrenth\Security\Updates
 */
class AddGoogle2FaSecretToBackendUsersTable extends Migration
{
    public function up()
    {
        Schema::table('backend_users', function (Blueprint $table) {
            $table->string('google2fa_secret')
                ->after('password')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('backend_users', function (Blueprint $table) {
            $table->dropColumn('google2fa_secret');
        });
    }
}
