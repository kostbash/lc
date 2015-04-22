<?php

class m150421_131604_create_second_skills extends CDbMigration
{
	public function up()
	{
        $this->createTable('oed_exercise_and_second_skills', array(
            'id' => 'pk',
            'id_skill'=>'int(11) NOT NULL',
            'id_exercise'=>'int(11) NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('oed_exercise_and_second_skills');
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