<?php

use App\Constants\LeaveStatuses;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('user_leaves_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('approver_id')->index('user_leaves_approver_id_foreign')->nullable();
            $table->foreign('approver_id')->references('id')->on('users');

            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->tinyInteger('half_day');
            $table->tinyInteger('post_meridiem');
            $table->tinyInteger('status')->default(LeaveStatuses::FOR_REVIEW);
            $table->tinyInteger('initial_approver');
            $table->tinyInteger('type');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_leaves');
    }
};
