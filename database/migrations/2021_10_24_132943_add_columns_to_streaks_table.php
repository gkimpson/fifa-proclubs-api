<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streaks', function (Blueprint $table) {
            $table->bigInteger('club_id')->unsigned()->after('id');
            $table->integer('biggest_win_streak')->unsigned()->nullable()->after('club_id');
            $table->integer('biggest_loss_streak')->unsigned()->nullable()->after('biggest_win_streak');
            $table->integer('current_streak')->nullable()->after('biggest_loss_streak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streaks', function (Blueprint $table) {
            $table->dropColumn('club_id');
            $table->dropColumn('biggest_win_streak');
            $table->dropColumn('biggest_loss_streak');
            $table->dropColumn('current_streak');
        });
    }
}
