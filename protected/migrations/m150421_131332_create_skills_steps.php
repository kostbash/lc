<?php

class m150421_131332_create_skills_steps extends CDbMigration
{
	public function up()
	{
        $this->createTable('oed_skills_steps', array(
            'id' => 'pk',
            'name' => "varchar(255) NOT NULL",
            'condition' => "longtext NOT NULL",
            'id_skill'=>'int(11) NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('oed_skills_steps');
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