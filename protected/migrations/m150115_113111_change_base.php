<?php
Yii::import("application.models.*");
function transformationFormat($matches)
{
    return "[".$matches[1].":{".$matches[2]."}]";
}

class m150115_113111_change_base extends CDbMigration
{
	public function up()
	{
            set_time_limit(60000);
            ini_set("memory_limit","1200M");

            $answers = ExercisesListOfAnswers::model()->findAll();
            foreach($answers as $answer)
            {
                $answer->answer = preg_replace_callback("#//([\d\w]*)=\((.*)\)//#uUm", "transformationFormat", $answer->answer);
                $answer->save(false);
            }

            $exercises = Exercises::model()->findAll();
            foreach($exercises as $exercise)
            {
                $exercise->condition =  preg_replace_callback("#//([\d\w]*)=\((.*)\)//#uUm", "transformationFormat", $exercise->condition);
                $exercise->save(false);
            }
	}

	public function down()
	{
            
	}
}