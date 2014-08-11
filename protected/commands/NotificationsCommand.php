<?php

class NotificationsCommand extends CConsoleCommand
{
    public $students;
    
    public function run($args)
    {
        $this->students = Users::Students();
        $this->createNotifications();
    }
    
    public function createNotifications()
    {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        foreach($this->students as $id_student)
        {
            if($user = Users::model()->findByPk($id_student))
            {
                if($user->last_activity >= $yesterday)
                {
                    $notification = new StudentNotifications;
                    $notification->id_user = $id_student;
                    $notification->id_type = 1;
                    $notification->date = $yesterday;
                    $notification->time = "00:00:00";

                    $countExercises = UserExercisesLogs::model()->countByAttributes(array('id_user'=>$id_student, 'date'=>$yesterday));
                    $countLessons = UserLessonsLogs::model()->countByAttributes(array('id_user'=>$id_student, 'date'=>$yesterday));
                    $countBlockExercises=Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `oed_user_blocks_logs` log, `oed_group_of_exercises` block WHERE log.id_block = block.id AND log.id_user='$id_student' AND log.date=$yesterday AND block.type=1")->queryAll();
                    $countTest=Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `oed_user_blocks_logs` log, `oed_group_of_exercises` block WHERE log.id_block = block.id AND log.id_user='$id_student' AND log.date='$yesterday' AND block.type=2")->queryAll();
                    $text = "Заданий: $countExercises; Уроков: $countLessons; Упражнений: {$countBlockExercises[0]['count']}; Тестов: {$countTest[0]['count']};";
                    $notification->text = $text;
                    $notification->save();
                }
                else
                {
                    $datetime1 = new DateTime($yesterday, new DateTimeZone(Yii::app()->timeZone));
                    $datetime2 = new DateTime($user->last_activity, new DateTimeZone(Yii::app()->timeZone));
                    $interval = $datetime1->diff($datetime2);
                    $daysWithOutActivity = $interval->days;

                    $notification = new StudentNotifications;
                    $notification->id_user = $id_student;
                    $notification->id_type = 2;
                    $notification->date = $yesterday;
                    $notification->time = "00:00:00";
                    $notification->text = "Дней без активности: ".$daysWithOutActivity;
                    $notification->save();
                }
                
                // если есть id, значит есть и запись
                if($notification->id)
                {
                    $this->createRelationsWithNotificationForTeachers($id_student, $notification->id);
                }
            }
        }
    }
    
    public function createRelationsWithNotificationForTeachers($id_student, $id_notification)
    {
        $teachers = Users::StudentTeachers($id_student);
        foreach($teachers as $id_teacher)
        {
            $notificationTeacher = new StudentNotificationsAndTeacher();
            $notificationTeacher->id_notification = $id_notification;
            $notificationTeacher->id_teacher = $id_teacher;
            $notificationTeacher->id_student = $id_student;
            $notificationTeacher->new = 1;
            $notificationTeacher->save();
        }
    }
}
