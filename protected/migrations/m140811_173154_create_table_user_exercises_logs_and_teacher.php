<?php

class m140811_173154_create_table_user_exercises_logs_and_teacher extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_user_exercises_logs_and_teacher', array(
                'id' => 'pk',
                'id_log' => "int(11) NOT NULL",
                'id_teacher' => "int(11) NOT NULL",
                'id_student' => "int(11) NOT NULL",
                'new' => "boolean NOT NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_user_exercises_logs_and_teacher');
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