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

        // 资源
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('type_id')->nullable()->default(0);
            $table->string('mime', 128);
            $table->string('hash', 32);
            $table->unsignedMediumInteger('size');
            $table->string('relative_url', 256);
            $table->string('original_name', 256);
            $table->string('url', 256);
            $table->string('remark', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 内容体系
        Schema::create('modules', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name', 16)->comment('名称');
            $table->string('namespace', 32)->comment('路径');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dimensions', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true);
            $table->string('name', 32)->comment('名称');
            $table->string('slug', 64)->comment('固定连接');
            $table->unsignedTinyInteger('limits')->default(1)->comment('限制数');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('module_dimension', function (Blueprint $table) {
            $table->unsignedSmallInteger('module_id')->comment('模型');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->unsignedMediumInteger('dimension_id')->comment('维度');
            $table->foreign('dimension_id')->references('id')->on('dimensions')->onDelete('cascade');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->unsignedMediumInteger('dimension_id')->after('id');
            $table->foreign('dimension_id')->references('id')->on('dimensions')->onDelete('cascade');
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
        Schema::dropIfExists('types');
        Schema::dropIfExists('files');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('dimensions');
        Schema::dropIfExists('module_dimension');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('positionable');

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign('tags_dimension_id_foreign');
            $table->dropColumn('dimension_id');
        });
    }
}