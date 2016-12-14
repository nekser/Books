<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 12/13/16
 * Time: 3:11 AM
 */

namespace LibraryTest\Controller;


use LibraryTest\Traits\DoctrineTestCaseTrait;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BaseControllerTestCase extends AbstractHttpControllerTestCase
{
    use DoctrineTestCaseTrait;
    /**
     * @var ServiceManager
     */
    protected $serviceLocator;

    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf/config/application.config.php'
        );
        parent::setUp();

        $this->serviceLocator =
            $this->getApplicationServiceLocator();
        $this->serviceLocator
            ->setAllowOverride(true);
    }
}