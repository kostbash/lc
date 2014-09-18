<?php

class m140918_105759_create_course_classes extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_course_classes', array(
                'id' => 'pk',
                'name' => "varchar(255) NOT NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_course_classes');
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