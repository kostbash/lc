<?php

class m150330_182144_change_base extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_courses', 'code', "LONGTEXT NULL DEFAULT NULL");
	}

	public function down()
	{
        $this->dropColumn('oed_courses', 'code');
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