<?php
namespace LibraryTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BookControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf/config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerClass('indexcontroller');
        $this->assertMatchedRouteName('home');
    }
}