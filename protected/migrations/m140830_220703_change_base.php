<?php

class m140830_220703_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_generators_words', 'remote', 'BOOLEAN NOT NULL DEFAULT FALSE');
	}

	public function down()
	{
            $this->dropColumn('oed_generators_words', 'remote');
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