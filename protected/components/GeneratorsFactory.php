<?php
require_once 'generators/ExamplesAndTasks.php';
require_once 'generators/ListOfWords.php';

class GeneratorsFactory {
    private $generator;
    
    function __construct($id_generator, $attributes=array())
    {
        $model = Generators::model()->findByPk($id_generator);
        if($model)
        {
            switch($id_generator)
            {
                case 1 : 
                    $this->generator = new ExampleAndTasks($model, $attributes);
                    break;
                case 2 :
                    $this->generator = new ListOfWords($model, $attributes);
                    break;
            }
        } else {
            die("Не существует генератора с id = $id_generator");
        }
    }
    
    function saveSettings() 
    {
        return $this->generator->saveSettings();
    }
    
    function generateExercises() 
    {
        return $this->generator->generateExercises();
    }
}