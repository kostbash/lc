<?php
return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'guest',
        'bizRule' => null,
        'data' => null
    ),
    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'admin',
        'children' => array(
            'teacher',
            'parent',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'student' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'student',
        'bizRule' => null,
        'data' => null
    ),
    'teacher' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'teacher',
        'children' => array(
            'student',
            'observer',
            'editor',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'parent' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'parent',
        'children' => array(
            'student',
            'observer',
            'editor',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'observer' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'observer',
        'bizRule' => null,
        'data' => null
    ),
    'manager' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'manager',
        'bizRule' => null,
        'data' => null
    ),
    'editor' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'editor',
        'bizRule' => null,
        'data' => null
    ),
);