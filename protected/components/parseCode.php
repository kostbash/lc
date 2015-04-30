<?php
class parseCode {

    public $code = null;

    public function __construct($code){
        $this->code = $code;
    }

    public function getFunc($name, $source) {
        preg_match_all('/'.$name.'\((.{1,})\)/', $source, $result, PREG_OFFSET_CAPTURE);
        return $result;
    }

    public function getFuncVal($name, $source) {
        $result_tmp = $this->getFunc($name, $source);
        $result = $result_tmp[0];
        foreach ($result_tmp[1] as $key=>$val) {
            $result[$key]['value'] = $val[0];
        }
        return $result;
    }



    public function parseBlockTitle($string) {
        $result = $this->getFunc('SetBlockTitle', $string);
        return $result[1][0][0];
    }

    public function getTasks($string) {
        $result = $this->getFuncVal('addTask', $string);
        foreach($result as $val) {
            $tasks[] = Exercises::model()->findByPk($val['value']);
        }
        return $tasks;
    }

    public function getTasksTest($string) {
        $tasks=array();
        preg_match_all('/AddTestSection/', $string, $result_tmp, PREG_OFFSET_CAPTURE);
        $result = $result_tmp[0];
        foreach ($result as $key=>$value) {
            $result[$key] = substr($string, $result[$key][1],
                (isset($result[$key+1])) ? $result[$key+1][1] - $result[$key][1] : strlen($this->code));
            $code = $result[$key];
            $visible_tasks = $this->getFuncVal('SetTestSectionVisibleTasks', $code);
            $visible_tasks = $visible_tasks[0]['value'];
            $tasks_tmp = $this->getTasks($code);
            shuffle($tasks_tmp);
            array_splice($tasks_tmp, $visible_tasks);
            $tasks=array_merge($tasks, $tasks_tmp);
        }
        shuffle($tasks);
        return $tasks;
    }

    public function getSkills($string) {
        $result = $this->getFuncVal('AddControlledU', $string);
        foreach($result as $val) {
            $tasks[] = Skills::model()->findByPk($val['value']);
        }
        return $tasks;
    }

    public static function GenerateCourseCode($id = false){
        if (!$id) {
            return false;
        }
        $blocks = array();


        $course = Courses::model()->findByPk($id);
        $steps = $course->LessonsGroups;
        $lessons = array();
        foreach ($steps as $step) {
            $lessons = array_merge($lessons, $step->LessonsRaw);
        }
        $lessons = array_merge($lessons, $course->Lessons);

        foreach ($lessons as $lesson) {
            $blocks = array_merge($blocks, $lesson->ExercisesGroups);
        }

        $code = "if isBlockPassed then \n inc(BlockIndex) \nendif \n\n";
        $code .= "switch(BlockIndex) {\n";
        $step = 0;
        //if (isset($blocks[0])) unset($blocks[0]); //Убрать при необходимост отображения проверочного теста
            foreach ($blocks as $block) {
                $step++;
                $code .= "case $step:\n";
                $model = GroupOfExercises::model()->findByPk($block->id);
                if ($model->type == 1) {
                    $code .= "SetBlockType(btExersice)\n";
                } else {
                    $code .= "SetBlockType(btTest)\n";
                }
                $code .= "SetBlockTitle($model->name)\n";
                $skills = $model->Skills;
                $percent_skills = $model->GroupAndSkills;
                if ($skills) {
                    foreach($skills as $skill) {
                        $code .= "AddControlledU($skill->id)\n";
                        foreach ($percent_skills as $percent) {
                            if ($percent->id_skill == $skill->id) {
                                $code .= "SetULevel($skill->id, $percent->pass_percent)\n";
                            }
                        }

                    }
                }

                if ($model->type == 1) {
                    foreach($model->Exercises as $exerce) {
                        $code .= " addTask($exerce->id)\n";
                    }
                } else {
                    foreach($model->PartsOfTest as $part) {
                        $group = PartsOfTest::model()->findByPk($part->id);
                        $code .= "AddTestSection\n";
                        $code .= "SetTestSectionVisibleTasks($part->limit)\n";

                        foreach($group->getExercises(false, false) as $exerce) {
                            $code .= " addTask($exerce->id)\n";
                        }
                    }
                }



                $code .= "break;\n";
                $code .= "\n\n";
            }


        $code .= '}';
        return $code;

    }



    public function getBlock($id_course = 11) {
        $code = $this->code;
        $user_variables = Variables::model()->findAllByAttributes(array('id_course'=>$id_course));

        foreach ($user_variables as $variable) {
           if (!$vars = VarUserValue::model()->findByAttributes(
               array(
                   'variable_id'=>$variable->id,
                   'user_id'=>Yii::app()->user->id,
                   'id_course'=>$id_course
               )))
           {
               $var = new VarUserValue();
               $var->variable_id=$variable->id;
               $var->id_course = $id_course;
               $var->user_id = Yii::app()->user->id;
               $var->value = $variable->default_value;
               if ($var->save()) {
                   $all_vars[] = array(
                       'var' => $variable,
                       'user_value' => $var,
                   );
               }

           } else {
               $all_vars[] = array(
                   'var' => $variable,
                   'user_value' => $vars,
               );
           }
        }





        $block = array();
        $test_section = 'tasks';
        $count = 0;

        if ($all_vars) {
            foreach ($all_vars as $var) {
                $name = $var['var']->name;
                $$name = $var['user_value']->value;
                $code = preg_replace('/'.$name.'/', '\$'.$name, $code);
            }
        }



        $code = preg_replace('/SetBlockType\((.{1,})\)/', '\$block[\'type\'] = \'$1\';', $code);
        $code = preg_replace('/SetBlockTitle\((.{1,})\)/', '\$block[\'title\'] = \'$1\';', $code);
        $code = preg_replace('/AddControlledU\((.{1,})\)/', '\$block[\'skills\'][] = Skills::model()->findByPk($1);', $code);
        $code = preg_replace('/addTask\((.{1,})\)/', '\$block[\'tasks\'][\$test_section][] = Exercises::model()->findByPk($1);', $code);
        $code = preg_replace('/SetULevel\((.{1,}),(.{1,})\)/', '\$block[\'skill_levels\'][$1] = (float)\'$2\';', $code);
        $code = preg_replace('/AddTestSection/', '\$test_section = \'section_\'.++\$count;', $code);
        $code = preg_replace('/SetTestSectionVisibleTasks\((.{1,})\)/', '\$block[\$test_section.\'_visible\'] = \'$1\';', $code);
        $code = preg_replace('/if isBlockPassed then([\s\S]*)endif/m', '', $code);

        $code = preg_replace('/SetBlockTitle\(\)/', '\$block[\'title\'] = \' \';', $code);

        $code = preg_replace('/ShowMessage\((.{1,})\)/', '\$block[\'message\'] = $1;', $code);

        $code = preg_replace('/TasksTrackedCount\((.{1,})\)/', '\$this->TasksTrackedCount($1)', $code);
        $code = preg_replace('/TasksControlledCount\((.{1,})\)/', '\$this->TasksControlledCount($1)', $code);
        $code = preg_replace('/TasksTrainedCount\((.{1,})\)/', '\$this->TasksTrainedCount($1)', $code);
        $code = preg_replace('/TasksToTrainRepeatCount\((.{1,})\)/', '\$this->TasksToTrainRepeatCount($1)', $code);
        $code = preg_replace('/TasksToControlRepeatedCount\((.{1,})\)/', '\$this->TasksToControlRepeatedCount($1)', $code);

        //echo '<pre>'.$code;
        eval ($code);
        //echo '<pre>'; print_r($block); exit;
        if (empty($block)) {
            return false;
        }
        if (isset($block['tasks'])) {
            if (isset($block['tasks']['tasks'])) {
                $block['tasks'] = $block['tasks']['tasks'];
            } else {
                $tasks = array();
                foreach ($block['tasks'] as $key=>$val) {

                    shuffle($block['tasks'][$key]);
                    array_splice($block['tasks'][$key], $block[$key.'_visible']);
                    $tasks=array_merge($tasks, $block['tasks'][$key]);
                }
                shuffle($tasks);
                $block['tasks'] = $tasks;

            }
        } else {
            $block['tasks'] = false;
        }

        return $block;
    }


    function TasksTrackedCount($u, $result = true, $block_type = 'all', $period = 0) {
        $skill_exercises = ExerciseAndSkills::model()->findAllByAttributes(array('id_skill'=>$u));
        $sql = 'SELECT COUNT(id) as count FROM oed_user_exercises_logs WHERE id_user = '.Yii::app()->user->id . ' and (';

        foreach($skill_exercises as $key => $skill_and_exercise) {
            if ($block_type != 'all') {
                $group_and_ex = GroupAndExercises::model()->findByAttributes(array('id_exercise'=>$skill_and_exercise->id_exercise));
                $block = GroupOfExercises::model()->findByPk($group_and_ex->id_group);
                if ($block) {
                    if ($block_type == 'btExercise' and $block->type != 1) {
                        continue;
                    }
                    if ($block_type == 'btTest' and $block->type != 2) {
                        continue;
                    }
                }

            }


            $sql .= 'id_exercise = ' . $skill_and_exercise->id_exercise;
            $count = $key;
            if (isset($skill_exercises[++$count])) {
                $sql .= ' or ';
            }
        }

        $sql .= ') and `right` = ' . $result;
        if ($period != 0) {
            $sql .= ' and TO_DAYS(NOW()) - TO_DAYS(date) <= '.$period;
        }


        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result[0]['count'];
    }

    public function TasksControlledCount($u, $result=true, $period=0) {
        return $this->TasksTrackedCount($u, $result, 'btTest', $period);
    }

    public function TasksTrainedCount($u, $result=true, $period=0) {
        return $this->TasksTrackedCount($u, $result, 'btExercise', $period);
    }

    public function TasksToTrainRepeatCount($u) {
        $result = 0;
        $block_type = 'btExercise';

        $skill_exercises = ExerciseAndSkills::model()->findAllByAttributes(array('id_skill'=>$u));
        $sql = 'SELECT * FROM oed_user_exercises_logs WHERE id_user = '.Yii::app()->user->id . ' and (';

        foreach($skill_exercises as $key => $skill_and_exercise) {
            if ($block_type != 'all') {
                $group_and_ex = GroupAndExercises::model()->findByAttributes(array('id_exercise'=>$skill_and_exercise->id_exercise));
                $block = GroupOfExercises::model()->findByPk($group_and_ex->id_group);
                if ($block) {
                    if ($block_type == 'btExercise' and $block->type != 1) {
                        continue;
                    }
                    if ($block_type == 'btTest' and $block->type != 2) {
                        continue;
                    }
                }

            }


            $sql .= 'id_exercise = ' . $skill_and_exercise->id_exercise;
            $count = $key;
            if (isset($skill_exercises[++$count])) {
                $sql .= ' or ';
            }
        }

        $sql .= ') and `right` = ' . $result;


        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if (!$result) {
            return -1;
        }
        $date = $result[count($result)-1]['date'] . ' ' . $result[count($result)-1]['time'];
        $P = 7 * (count($result) + 1);
        if (time() - strtotime($date) < $P) {
            return 0;
        }

        return 10/(count($result)+1);

    }

    public function TasksToControlRepeatedCount($u) {
        $result = 0;
        $block_type = 'btTest';

        $skill_exercises = ExerciseAndSkills::model()->findAllByAttributes(array('id_skill'=>$u));
        $sql = 'SELECT * FROM oed_user_exercises_logs WHERE id_user = '.Yii::app()->user->id . ' and (';

        foreach($skill_exercises as $key => $skill_and_exercise) {
            if ($block_type != 'all') {
                $group_and_ex = GroupAndExercises::model()->findByAttributes(array('id_exercise'=>$skill_and_exercise->id_exercise));
                $block = GroupOfExercises::model()->findByPk($group_and_ex->id_group);
                if ($block) {
                    if ($block_type == 'btExercise' and $block->type != 1) {
                        continue;
                    }
                    if ($block_type == 'btTest' and $block->type != 2) {
                        continue;
                    }
                }

            }


            $sql .= 'id_exercise = ' . $skill_and_exercise->id_exercise;
            $count = $key;
            if (isset($skill_exercises[++$count])) {
                $sql .= ' or ';
            }
        }

        $sql .= ') and `right` = ' . $result;


        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if (!$result) {
            return -1;
        }
        $date = $result[count($result)-1]['date'] . ' ' . $result[count($result)-1]['time'];
        $P = 7 * (count($result) + 1);
        if (time() - strtotime($date) < $P) {
            return 0;
        }

        return 10/(count($result)+1);

    }

}