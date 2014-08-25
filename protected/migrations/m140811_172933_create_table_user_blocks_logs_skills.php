<?php

class m140811_172933_create_table_user_blocks_logs_skills extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_user_blocks_logs_skills', array(
                'id' => 'pk',
                'id_log' => "int(11) NOT NULL",
                'id_skill' => "int(11) NOT NULL",
                'achieved_percent' => "tinyint(4) NOT NULL",
                'need_percent' => "tinyint(4) NOT NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_user_blocks_logs_skills');
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