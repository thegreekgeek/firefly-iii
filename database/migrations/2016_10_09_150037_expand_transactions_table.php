<?php

/**
 * 2016_10_09_150037_expand_transactions_table.php
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
 * Class ExpandTransactionsTable
 */
class ExpandTransactionsTable extends Migration
{
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        //
    }

    /**
     * Run the migrations.
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up()
    {
        Schema::table(
            'transactions', function (Blueprint $table) {
            $table->smallInteger('identifier', false, true)->default(0);
        }
        );
    }
}
