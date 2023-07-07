<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::statement($this->updateView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->revertUpdatedView());
    }

    /**
     * Returns the SQL to create the view.
     *
     * @return void
     */
    private function updateView(): string
    {
        return <<<SQL
            ALTER 
                ALGORITHM=MERGE
            VIEW `user_prediction_scores` AS
                SELECT user_predictions.id,
                       user_predictions.gameweek_id,
                       user_predictions.user_id,
                       user_predictions.fixture_id,
                (
                    CASE
                        WHEN user_predictions.home_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home') AND user_predictions.away_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away') THEN 3
                        WHEN (user_predictions.home_score > user_predictions.away_score AND JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home') > JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away'))
                        OR (user_predictions.home_score < user_predictions.away_score AND JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home') < JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away'))
                        OR (user_predictions.home_score = user_predictions.away_score AND JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home') = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away'))
                            THEN 1
                        ELSE 0
                    END
                ) AS points
                FROM user_predictions
                INNER JOIN fixtures ON fixtures.id = user_predictions.fixture_id
            SQL;
    }

    /**
     * Returns the SQL to drop the view.
     *
     * @return void
     */
    private function revertUpdatedView(): string
    {
        return <<<SQL
            ALTER 
                ALGORITHM=MERGE
            VIEW `user_prediction_scores` AS
                SELECT user_predictions.id,
                       user_predictions.gameweek_id,
                       user_predictions.user_id,
                       user_predictions.fixture_id,
                (
                    CASE
                        WHEN user_predictions.home_score = fixtures.home_goals AND user_predictions.away_score = fixtures.away_goals THEN 3
                        WHEN (user_predictions.home_score > user_predictions.away_score AND fixtures.home_goals > fixtures.away_goals)
                        OR (user_predictions.home_score < user_predictions.away_score AND fixtures.home_goals < fixtures.away_goals)
                        OR (user_predictions.home_score = user_predictions.away_score AND fixtures.home_goals = fixtures.away_goals)
                            THEN 1
                        ELSE 0
                    END
                ) AS points
                FROM user_predictions
                INNER JOIN fixtures ON fixtures.id = user_predictions.fixture_id
            SQL;
    }
};
