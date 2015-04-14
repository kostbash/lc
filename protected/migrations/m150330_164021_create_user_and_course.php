<?php

class m150330_164021_create_user_and_course extends CDbMigration
{
	public function up()
	{
        $this->createTable('oed_user_and_course', array(
            'id' => 'pk',
            'user_id' => "int(11) NOT NULL",
            'course_id' => "int(11) NOT NULL",
            'block_number' => "int(11) NOT NULL",
        ));
	}

	public function down()
	{
        $this->dropTable('oed_user_and_course');
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