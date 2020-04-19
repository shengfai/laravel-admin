<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->ipAddress('ip')->nullable()->comment('操作者IP')->after('causer_type');
            $table->string('user_agent', 512)->nullable()->comment('操作者UserAgent')->after('causer_type');
            $table->string('method', 32)->nullable()->comment('操作方法')->after('causer_type');
            $table->string('host', 128)->nullable()->comment('主机')->after('causer_type');
            $table->string('node', 128)->nullable()->comment('当前节点')->after('causer_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('ip');
            $table->dropColumn('user_agent');
            $table->dropColumn('method');
            $table->dropColumn('host');
            $table->dropColumn('node');
        });
    }
}
