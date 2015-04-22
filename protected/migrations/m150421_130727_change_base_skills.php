<?php

class m150421_130727_change_base_skills extends CDbMigration
{
	public function up()
	{
        $this->addColumn('oed_skills', 'n1', "INT UNSIGNED NOT NULL DEFAULT '0'");
        $this->addColumn('oed_skills', 'n2', "INT UNSIGNED NOT NULL DEFAULT '0'");
        $this->addColumn('oed_skills', 'n3', "INT UNSIGNED NOT NULL DEFAULT '0'");
        $this->addColumn('oed_skills', 'skillsGroup', "INT( 11 ) NOT NULL DEFAULT '0'");
	}

	public function down()
	{
        $this->dropColumn('oed_courses', 'n1');
        $this->dropColumn('oed_courses', 'n2');
        $this->dropColumn('oed_courses', 'n3');
        $this->dropColumn('oed_courses', 'skillsGroup');
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