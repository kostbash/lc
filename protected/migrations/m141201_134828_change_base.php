<?php

class m141201_134828_change_base extends CDbMigration
{
	public function up()
	{
            $this->renameTable('oed_children_of_parent', 'oed_children');
	}

	public function down()
	{
            $this->renameTable('oed_children', 'oed_children_of_parent');
	}
}