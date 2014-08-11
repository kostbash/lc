<?php

class m140811_173009_create_table_user_courses_logs extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_user_courses_logs', array(
                'id' => 'pk',
                'id_user' => "int(11) NOT NULL",
                'id_course' => "int(11) NOT NULL",
                'date' => "date NOT NULL",
                'time' => "time NOT NULL",
                'duration' => "smallint(6) NOT NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_user_courses_logs');
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