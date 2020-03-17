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
        
        // 分类
        Schema::create('types', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true);
            $table->unsignedMediumInteger('parent_id')->default(0);
            $table->string('model_type', 32)->index()->comment('适用模型');
            $table->string('name', 32)->comment('名称');
            $table->string('identifier', 32)->nullable()->comment('标识');
            $table->string('cover_pic', 128)->nullable()->comment('封面');
            $table->string('description')->nullable()->comment('描述');
            $table->boolean('is_recommend')->default(false)->comment('推荐');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('user_id');
            $table->timestamps();
		});
        
        // 推荐位
        Schema::create('positions', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name', 32)->comment('名称');
            $table->string('cover_pic', 64)->default('')->nullable()->comment('封面');
            $table->string('description', 128)->default('')->nullable()->comment('描述');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 推荐内容
        Schema::create('positionable', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('position_id');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->morphs('positionable');
            $table->string('title', 64)->comment('名称');
            $table->string('cover_pic', 64)->default('')->nullable()->comment('封面/Logo');
            $table->string('description', 512)->default('')->nullable()->comment('描述');
            $table->unsignedTinyInteger('sort')->default(0);
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
        Schema::dropIfExists('types');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('positionable');
    }
}