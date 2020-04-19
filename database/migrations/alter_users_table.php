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

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->default(1)->comment('用户类型')->after('id');
            $table->char('phone', 15)->comment('手机号（086+138xxxxxxxx）')->unique()->after('name');
            $table->unsignedTinyInteger('status')->default(1)->comment('用户状态')->after('password');
            $table->string('remark', 128)->nullable()->comment('备注')->after('password');
            $table->unsignedBigInteger('spread_userid')->default(0)->comment('推荐人')->after('password');
            $table->unsignedTinyInteger('registered_channel')->default(1)->nullable()->comment('注册通道')->after('password');
            $table->string('district', 64)->nullable()->comment('商圈')->after('password');
            $table->string('city', 32)->nullable()->comment('城市')->after('password');
            $table->string('province', 32)->nullable()->comment('省份')->after('password');
            $table->string('country', 32)->nullable()->comment('国家')->after('password');
            $table->date('birthdate')->nullable()->comment('出生日期')->after('password');
            $table->unsignedTinyInteger('gender')->default(0)->nullable()->comment('性别')->after('password');
            $table->string('signature', 128)->nullable()->comment('个性签名')->after('password');
            $table->smallInteger('notification_count')->unsigned()->default(0)->after('password');
            $table->string('avatar', 200)->nullable()->comment('头像')->after('password');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropColumn('notification_count');
            $table->dropColumn('signature');
            $table->dropColumn('gender');
            $table->dropColumn('birthdate');
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('registered_channel');
            $table->dropColumn('spread_userid');
            $table->dropColumn('remark');
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
}
