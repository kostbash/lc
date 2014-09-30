<?php

class m140930_110022_change_base extends CDbMigration
{
	public function up()
	{
            $this->insert('oed_mail_templates', array(
                'template_name' => 'reset_password',
                'template_subject' => 'Сброс пароля',
                'template_body' => 'Здравствуйте, пароль вашего аккаунта на сайте {site_name} был изменен. <br>Ваш новый пароль: {new_password}',
            ));
	}

	public function down()
	{
		echo "m140930_110022_change_base does not support migration down.\n";
		return false;
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