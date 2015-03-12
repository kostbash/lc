<?php

class m150312_161456_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_users', 'is_dub', "BOOLEAN NOT NULL DEFAULT FALSE");
    }

    public function down()
    {
        $this->dropColumn('oed_users', 'is_dub');
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