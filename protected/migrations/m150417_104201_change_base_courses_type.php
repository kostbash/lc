<?php

class m150417_104201_change_base_courses_type extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_courses', 'type', "TINYINT( 4 ) NOT NULL DEFAULT 1");
	}

	public function down()
	{
        $this->dropColumn('oed_courses', 'type');
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