<?php

class m140918_105818_create_course_subjects extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_course_subjects', array(
                'id' => 'pk',
                'name' => "varchar(255) NOT NULL",
                'order'=>'int(11) NOT NULL',
            ));
	}

	public function down()
	{
            $this->dropTable('oed_course_subjects');
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