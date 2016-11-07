<?php

return array(
    'modules' => array(
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'BjyAuthorize',
        'Application',
        'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
        'BookUser',
        'Library'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{{,*.}global,{,*.}local}.php',
        ),
    ),
);
