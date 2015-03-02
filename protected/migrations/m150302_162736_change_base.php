<?php

class m150302_162736_change_base extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_group_of_exercises', 'is_mixed', "BOOLEAN NOT NULL DEFAULT TRUE");
	}

	public function down()
	{
        $this->dropColumn('oed_group_of_exercises', 'is_mixed');
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