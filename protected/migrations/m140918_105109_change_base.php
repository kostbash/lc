<?php

class m140918_105109_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_courses', 'learning_time', 'VARCHAR(255) NULL');
            $this->addColumn('oed_courses', 'difficulty', 'TINYINT NOT NULL DEFAULT 0');
            $this->addColumn('oed_courses', 'id_subject', 'INT NOT NULL DEFAULT 0');
            $this->addColumn('oed_courses', 'id_class', 'INT NOT NULL DEFAULT 0');
            
            $this->addColumn('oed_courses_and_users', 'status', 'TINYINT NOT NULL');
            $this->addColumn('oed_courses_and_users', 'activity_date', 'DATETIME NOT NULL');
            $this->addColumn('oed_courses_and_users', 'passed_date', 'DATETIME NULL');
	}

	public function down()
	{
            $this->dropColumn('oed_courses', 'learning_time');
            $this->dropColumn('oed_courses', 'difficulty');
            $this->dropColumn('oed_courses', 'id_subject');
            $this->dropColumn('oed_courses', 'id_class');
            
            $this->dropColumn('oed_courses_and_users', 'status');
            $this->dropColumn('oed_courses_and_users', 'activity_date');
            $this->dropColumn('oed_courses_and_users', 'passed_date');
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