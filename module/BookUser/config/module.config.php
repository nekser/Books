<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'BookUserYamlDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'paths' => array(__DIR__ . '/entity'),
                'cache' => 'apc'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'BookUser\Entity' => 'BookUserYamlDriver',
                )
            )
        )
    ),
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'BookUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
        'book_user_default_role' => 'user'
    ),
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'BookUser\Entity\Role',
            ),
        ),
    ),
);