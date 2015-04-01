<?php

class m150401_175215_variables extends CDbMigration
{
    public function up()
    {
        $this->createTable('oed_variables', array(
            'id' => 'pk',
            'name' => "varchar(255) NOT NULL",
            'type' => "varchar(255) NOT NULL",
            'id_course' => "int(11) NOT NULL",
            'default_value' => "varchar(255) NOT NULL",
        ));
    }

    public function down()
    {
        $this->dropTable('oed_variables');
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