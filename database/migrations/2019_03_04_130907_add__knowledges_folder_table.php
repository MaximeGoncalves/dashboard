<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKnowledgesFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Knowledges_Folder', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('Knowledges_Categories_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Knowledges_Folder', function (Blueprint $table) {
            //
        });
    }
}
