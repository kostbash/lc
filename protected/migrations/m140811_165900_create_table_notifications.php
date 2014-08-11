<?php

class m140811_165900_create_table_notifications extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_notifications', array(
                'id' => 'pk',
                'name' => 'varchar(255) NOT NULL',
                'color' => 'varchar(10) NOT NULL',
            ));
            
            $this->insert('oed_notifications', array(
                'name' => 'Выполнено за сутки',
                'color' => '#0a0',
            ));
            $this->insert('oed_notifications', array(
                'name' => 'Отсутствие активности',
                'color' => '#a00',
            ));
            $this->insert('oed_notifications', array(
                'name' => 'Неуспешно пройден тест',
                'color' => '#a00',
            ));
            $this->insert('oed_notifications', array(
                'name' => 'Успешно пройден тест',
                'color' => '#0a0',
            ));
	}

	public function down()
	{
            $this->dropTable('oed_notifications');
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