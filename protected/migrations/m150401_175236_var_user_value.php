<?php

class m150401_175236_var_user_value extends CDbMigration
{
    public function up()
    {
        $this->createTable('oed_var_user_value', array(
            'id' => 'pk',
            'user_id' => "varchar(255) NOT NULL",
            'variable_id' => "int(11) NOT NULL",
            'id_course' => "int(11) NOT NULL",
            'value' => "varchar(255) NOT NULL",
        ));
    }

    public function down()
    {
        $this->dropTable('oed_var_user_value');
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