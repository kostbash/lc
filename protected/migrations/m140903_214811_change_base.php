<?php

class m140903_214811_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_generators_templates_variables', 'values', 'VARCHAR(500) NULL');
            $this->addColumn('oed_generators_templates_variables', 'values_type', 'TINYINT(50) NOT NULL DEFAULT 1');
	}

	public function down()
	{
            $this->dropColumn('oed_generators_words', 'values');
            $this->dropColumn('oed_generators_words', 'values_type');
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