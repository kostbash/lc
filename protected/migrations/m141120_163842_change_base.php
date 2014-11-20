<?php

class m141120_163842_change_base extends CDbMigration
{
    public function up()
    {
        $this->insert('oed_mail_templates', array(
            'template_name' => 'break_family_ties',
            'template_subject' => 'Ребенок убрал вас из своих родителей',
            'template_body' => 'Ребенок с псевдонимом "{username_child}" указал что вы больше не его родитель. Кого ты воспитала ?',
        ));
        $this->alterColumn('oed_users', 'id_recovery_question', 'int(11) NULL');
        $this->alterColumn('oed_users', 'recovery_answer', 'varchar(100) NULL');
        $this->addColumn('oed_users', 'email', 'varchar(50) NULL');
    }

    public function down()
    {
        $this->dropColumn('oed_users', 'email');
    }
}