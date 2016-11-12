<?php
return array(
    'controllers' => array(
        'factories' => array(
            'BookController' => 'Library\Controller\Factory\BookControllerFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'book' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/book[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'BookController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'LibraryYamlDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'paths' => array(__DIR__ . '/entity'),
                'cache' => 'apc'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Library\Entity' => 'LibraryYamlDriver',
                )
            )
        )
    ),
);