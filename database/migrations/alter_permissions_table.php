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

class AlterPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('title', 64)->nullable()->comment('权限名称')->after('name');
            $table->string('remark', 128)->nullable()->comment('备注')->after('guard_name');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->comment('状态')->after('guard_name');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序')->after('guard_name');
            $table->string('remark', 128)->nullable()->comment('备注')->after('guard_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('remark');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('remark');
            $table->dropColumn('sort');
            $table->dropColumn('status');
        });
    }
}
