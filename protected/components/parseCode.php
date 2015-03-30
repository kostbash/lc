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

    public function getBlocks() {
        $result_tmp = $this->getFunc('SetBlockType', $this->code);
        $result = $result_tmp[0];
        foreach ($result_tmp[1] as $key=>$val) {
            $result[$key]['type'] = $val[0];
            $result[$key]['body'] = substr($this->code, $result[$key][1],
                (isset($result[$key+1])) ? $result[$key+1][1] - $result[$key][1] : strlen($this->code));
            $result[$key]['title'] = $this->parseBlockTitle($result[$key]['body']);
//            $result[$key]['skills'] = $this->getSkills($result[$key]['body']);
//            $result[$key]['tasks'] = $this->getTasks($result[$key]['body']);

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
        $model=Courses::CourseById($id);

        $steps = $model->LessonsGroups;
        foreach ($steps as $step) {
            $lesson = GroupOfLessons::model()->findByPk($step->id);
            $lessons[] = $lesson->LessonsRaw;
        }
        foreach($lessons as $lesson_l) {
            foreach($lesson_l as $lesson) {
                $block = Lessons::model()->findByPk($lesson->id);
                $blocks[] = $block->ExercisesGroups;
            }

        }
        $block_count = count($blocks);
        $code = null;
        for ($i = 1; $i<$block_count; $i++) {
            foreach ($blocks[$i] as $block) {
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




                $code .= "\n\n";
            }

        }
        return $code;

    }
}