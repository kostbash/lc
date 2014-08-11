<?php

class m140811_164010_create_table_children_of_parent extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_children_of_parent', array(
                'id' => 'pk',
                'id_parent' => 'integer NOT NULL',
                'id_child' => 'integer NOT NULL',
                'child_name' => 'varchar(255) NOT NULL',
                'child_surname' => 'varchar(255) NOT NULL',
                'status' => 'boolean DEFAULT 0 NOT NULL',
            ));
	}

	public function down()
	{
            $this->dropTable('oed_children_of_parent');
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