<?php
namespace BookUserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserController extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf/config/application.config.php'
        );
        parent::setUp();
    }

    public function testRoleIsUserAfterRegistration()
    {
        $this->markTestIncomplete();
    }
}