<?php

class m140826_154745_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_students_of_teacher', 'confirm', 'VARCHAR(26) NULL');
            $this->addColumn('oed_students_of_teacher', 'regect', 'VARCHAR(26) NULL');
            $this->addColumn('oed_children_of_parent', 'confirm', 'VARCHAR(26) NULL');
            $this->addColumn('oed_children_of_parent', 'regect', 'VARCHAR(26) NULL');
            $this->insert('oed_mail_templates', array(
                'template_name' => 'deal_from_teacher',
                'template_subject' => 'Приглашение от учителя',
                'template_body' => 'Пользователь с эл. почтой "{email_teacher}" предлагает обучаться у него.<p>{confirm_link}</p><p>{regect_link}</p>',
            ));
            $this->insert('oed_mail_templates', array(
                'template_name' => 'deal_from_parent',
                'template_subject' => 'Приглашение от родителя',
                'template_body' => 'Пользователь с электронной почтой "{email_parent}" отправил запрос на подключение к вашему аккаунту.Подтвердите запрос только если вы узнаете адрес эл.почты и полностью уверены, что это один из ваших родителей. В дальнейшем он сможет просматривать все ваши действия в системе и выдавать вам задания.<p>{confirm_link}</p><p>{regect_link}</p>',
            ));
	}

	public function down()
	{
            $this->dropColumn('oed_students_of_teacher', 'confirm');
            $this->dropColumn('oed_students_of_teacher', 'regect');
            $this->dropColumn('oed_children_of_parent', 'confirm');
            $this->dropColumn('oed_children_of_parent', 'regect');
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