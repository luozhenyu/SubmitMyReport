<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');

            //submission作者
            $table->unsignedInteger('owner_id');
            //submission所属assignment
            $table->unsignedInteger('assignment_id');

            //检查后mark_user_id为非NULL值
            $table->unsignedInteger('mark_user_id')->nullable();
            $table->integer('score')->nullable();
            $table->text('remark')->nullable();

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
        Schema::dropIfExists('submissions');
    }
}
