<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    /**
     * Returns the SQL to create the view.
     *
     * @return void
     */
    private function createView(): string
    {
        return <<<SQL
            CREATE OR REPLACE VIEW `user_backed_team_correct_results`
            AS
                SELECT users.id AS user_id,
                       backed_team_correct_results.group_id,
                       MAX(backed_team_correct_results.backed_team_id) AS team_id,
                       COUNT(backed_team_correct_results.id) AS correct_scores
                
                FROM users
                
                INNER JOIN (
                    SELECT user_predictions.id,
                           user_predictions.user_id AS user_id,
                           gameweeks.group_id AS group_id,
                           backed_teams.team_id AS backed_team_id
                    FROM user_predictions
                
                    INNER JOIN gameweeks ON gameweeks.id = user_predictions.gameweek_id
                    INNER JOIN `groups` ON `groups`.id = gameweeks.group_id
                    INNER JOIN backed_teams ON backed_teams.group_id = `groups`.id AND backed_teams.user_id = user_predictions.user_id
                    INNER JOIN fixtures ON fixtures.id = user_predictions.fixture_id
                    WHERE user_predictions.home_score = fixtures.home_goals
                        AND user_predictions.away_score = fixtures.away_goals
                        AND (fixtures.home_team_id = backed_teams.team_id OR away_team_id = backed_teams.team_id)
                ) backed_team_correct_results ON users.id = backed_team_correct_results.user_id
                
                GROUP BY backed_team_correct_results.group_id,
                         backed_team_correct_results.user_id
            SQL;
    }

    /**
     * Returns the SQL to drop the view.
     *
     * @return void
     */
    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `user_backed_team_correct_results`;
        SQL;
    }
};
