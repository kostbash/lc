<?php

class m140811_170913_create_table_student_notifications extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_student_notifications', array(
                'id' => 'pk',
                'id_user' => 'integer NOT NULL',
                'id_type' => 'integer NOT NULL',
                'date' => 'date NOT NULL',
                'time' => 'time NOT NULL',
                'text' => 'text NOT NULL',
            ));
	}

	public function down()
	{
            $this->dropTable('oed_student_notifications');
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