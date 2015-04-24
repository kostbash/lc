<?php

class m150422_131120_course_title extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_courses', 'title', "VARCHAR( 255 ) NOT NULL DEFAULT '{name} - Курсис'");
	}

	public function down()
	{
        $this->dropColumn('oed_courses', 'title');
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