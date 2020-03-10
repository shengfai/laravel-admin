<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 菜单
        Schema::create('menus', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('parent_id')->default(0)->comment('父节点');
            $table->string('name', 32)->default('')->comment('名称');
            $table->string('code', 32)->default('')->index()->comment('节点代码');
            $table->unsignedInteger('permission_id')->default(0);
            $table->string('icon', 32)->nullable()->default('')->comment('菜单图标');
            $table->string('url', 60)->default('')->comment('链接');
            $table->string('params', 128)->default('')->nullable()->comment('链接参数');
            $table->string('target', 30)->default('_self')->comment('链接打开方式');
            $table->unsignedSmallInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 配置
        Schema::create('settings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 64)->comment('配置名')->unique();
            $table->string('value', 256)->comment('配置值');
            $table->timestamps();
        });
        
        // 日志
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('type')->default(1)->comment('日志类别');
            $table->unsignedSmallInteger('behavior_id')->default(0)->nullable();
            $table->morphs('owner');
            $table->ipAddress('ip')->comment('操作者IP');
            $table->string('user_agent', 512)->comment('操作者UserAgent');
            $table->string('method', 32)->comment('操作方法');
            $table->string('host', 128)->comment('主机');
            $table->string('node', 128)->comment('当前节点');
            $table->mediumText('params')->comment('提交数据');
            $table->string('action', 64)->comment('操作行为');
            $table->string('remark', 256)->nullable()->comment('备注');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
        Schema::drop('settings');
        Schema::dropIfExists('logs');
    }
}