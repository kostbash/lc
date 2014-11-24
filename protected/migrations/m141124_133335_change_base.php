<?php

class m141124_133335_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_courses_and_users', 'last_activity_date', 'DATETIME NOT NULL');
        $this->addColumn('oed_user_and_exercise_groups', 'last_activity_date', 'DATETIME NOT NULL');
        $this->addColumn('oed_user_and_lessons', 'last_activity_date', 'DATETIME NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('oed_courses_and_users', 'last_activity_date');
        $this->dropColumn('oed_user_and_exercise_groups', 'last_activity_date');
        $this->dropColumn('oed_user_and_lessons', 'last_activity_date');
    }
}