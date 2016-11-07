<?php
return array(
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