<?php

class m150330_164429_change_base extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_skills', 'condition', "LONGTEXT NULL DEFAULT NULL");
	}

	public function down()
	{
        $this->dropColumn('oed_skills', 'condition');
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