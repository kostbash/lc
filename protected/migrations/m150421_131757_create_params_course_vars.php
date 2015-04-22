<?php

class m150421_131757_create_params_course_vars extends CDbMigration
{
	public function up()
	{
        $this->createTable('oed_params_course_vars', array(
            'id' => 'int(11) NOT NULL',
            'id_course'=>'pk',
            'MaxTasksCountInBlock'=>'INT UNSIGNED NOT NULL DEFAULT \'25\'',
            'MaxBlocksCountInLesson'=>'INT UNSIGNED NOT NULL DEFAULT \'5\'',
            'Threshold'=>'INT UNSIGNED NOT NULL DEFAULT \'100\'',
            'FailReps'=>'INT UNSIGNED NOT NULL DEFAULT \'3\'',
            'TheoryShowReps'=>'INT UNSIGNED NOT NULL DEFAULT \'3\'',
        ));
	}

	public function down()
	{
        $this->dropTable('params_course_vars');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}