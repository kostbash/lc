<?php

class m140811_171750_create_table_student_notifications_and_teacher extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_student_notifications_and_teacher', array(
                'id' => 'pk',
                'id_notification' => 'integer NOT NULL',
                'id_teacher' => 'integer NOT NULL',
                'id_student' => 'integer NOT NULL',
                'new' => 'boolean DEFAULT false NOT NULL',
            ));
	}

	public function down()
	{
            $this->dropTable('oed_student_notifications_and_teacher');
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