<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary()->index('user_information_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('department_id')->index('user_information_department_id_foreign');
            $table->foreign('department_id')->references('id')->on('departments');

            $table->unsignedBigInteger('position_id')->index('user_information_position_id_foreign');
            $table->foreign('position_id')->references('id')->on('positions');

            $table->unsignedBigInteger('branch_id')->index('user_information_branch_id_foreign');
            $table->foreign('branch_id')->references('id')->on('branches');
            
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->tinyInteger('gender');
            $table->bigInteger('mobile_number')->unsigned()->unique();
            $table->char('position')->nullable();
            $table->date('birth_date');
            $table->date('hired_date');
            $table->date('resigned_date')->nullable();
            $table->tinyInteger('nationality');
            $table->tinyInteger('religion');
            $table->tinyInteger('marital_status');
            $table->tinyInteger('status');
            
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
        Schema::dropIfExists('user_information');
    }
}
