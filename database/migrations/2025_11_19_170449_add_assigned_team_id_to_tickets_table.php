<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedTeamIdToTicketsTable extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_team_id')->nullable()->after('category');
            $table->foreign('assigned_team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_team_id']);
            $table->dropColumn('assigned_team_id');
        });
    }
}