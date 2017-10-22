<?php

/**
 * 2017_06_02_105232_changes_for_v450.php
 * Copyright (c) 2017 thegrumpydictator@gmail.com
 * This software may be modified and distributed under the terms of the
 * Creative Commons Attribution-ShareAlike 4.0 International License.
 *
 * See the LICENSE file for details.
 */

declare(strict_types=1);


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class ChangesForV450
 */
class ChangesForV450 extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

    /**
     * Run the migrations.
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @return void
     */
    public function up()
    {
        // add "foreign_amount" to transactions
        Schema::table(
            'transactions', function (Blueprint $table) {
            $table->decimal('foreign_amount', 22, 12)->nullable()->after('amount');
        }
        );

        // add foreign transaction currency id to transactions (is nullable):
        Schema::table(
            'transactions', function (Blueprint $table) {
            $table->integer('foreign_currency_id', false, true)->default(null)->after('foreign_amount')->nullable();
            $table->foreign('foreign_currency_id')->references('id')->on('transaction_currencies')->onDelete('set null');
        }
        );
    }
}
