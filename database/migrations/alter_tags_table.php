<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->default(0)->after('id');
            $table->unsignedTinyInteger('sort')->default(0)->after('order_column');
            $table->string('remark', 512)->nullable()->after('order_column');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('sort');
            $table->dropColumn('remark');
        });
    }
}
