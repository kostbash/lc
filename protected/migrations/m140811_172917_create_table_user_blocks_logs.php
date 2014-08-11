<?php

class m140811_172917_create_table_user_blocks_logs extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_user_blocks_logs', array(
                'id' => 'pk',
                'id_user' => "int(11) NOT NULL",
                'id_course' => "int(11) NOT NULL",
                'id_theme' => "int(11) NOT NULL",
                'id_lesson' => "int(11) NOT NULL",
                'id_block' => "int(11) NOT NULL",
                'date' => "date NOT NULL",
                'time' => "time NOT NULL",
                'duration' => "smallint(6) NOT NULL",
                'passed' => "boolean NOT NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_user_blocks_logs');
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