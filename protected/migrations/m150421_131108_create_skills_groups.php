<?php

class m150421_131108_create_skills_groups extends CDbMigration
{
	public function up()
	{
        $this->createTable('oed_skills_groups', array(
            'id' => 'pk',
            'name' => "varchar(255) NOT NULL",
            'id_course'=>'int(11) NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('oed_skills_groups');
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