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
        $blocks = GroupOfExercises::model()->findAllByAttributes(array('id_course'=>$id));



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
                foreach($skills as $skill) {
                    $code .= "AddControlledU($skill->id)\n";
                    foreach ($percent_skills as $percent) {
                        if ($percent->id_skill == $skill->id) {
                            $code .= "SetULevel($skill->id, $percent->pass_percent)\n";
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

        foreach ($all_vars as $var) {
            $name = $var['var']->name;
            $$name = $var['user_value']->value;
            $code = preg_replace('/'.$name.'/', '\$'.$name, $code);
            $code = mb_substr($code, 47);
        }


        $code = preg_replace('/SetBlockType\((.{1,})\)/', '\$block[\'type\'] = \'$1\';', $code);
        $code = preg_replace('/SetBlockTitle\((.{1,})\)/', '\$block[\'title\'] = \'$1\';', $code);
        $code = preg_replace('/AddControlledU\((.{1,})\)/', '\$block[\'skills\'][] = Skills::model()->findByPk($1);', $code);
        $code = preg_replace('/addTask\((.{1,})\)/', '\$block[\'tasks\'][\$test_section][] = Exercises::model()->findByPk($1);', $code);
        $code = preg_replace('/SetULevel\((.{1,}),(.{1,})\)/', '\$block[\'skill_levels\'][$1] = (float)\'$2\';', $code);
        $code = preg_replace('/AddTestSection/', '\$test_section = \'section_\'.++\$count;', $code);
        $code = preg_replace('/SetTestSectionVisibleTasks\((.{1,})\)/', '\$block[\$test_section.\'_visible\'] = \'$1\';', $code);

        $code = preg_replace('/SetBlockTitle\(\)/', '\$block[\'title\'] = \' \';', $code);

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


}