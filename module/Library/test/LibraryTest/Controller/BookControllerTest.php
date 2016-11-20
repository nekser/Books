<?php
namespace LibraryTest\Controller;

use BjyAuthorize\Exception\UnAuthorizedException;
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

    public function testIndexActionForbiddenForNonauthorizedUser()
    {
        $this->dispatch('/book');
        $this->assertResponseStatusCode(403);
        $this->assertApplicationException(UnAuthorizedException::class);
    }

    public function testIndexActionCanBeAccessed()
    {
        $mockBjy = $this->getMock('BjyAuthorize\Service\Authorize',
            array("isAllowed"),
            array(
                $this->getApplicationConfig(),
                $this->getApplication()->getServiceManager()
            )
        );

        // Bypass auth, force true
        $mockBjy->expects($this->any())
            ->method('isAllowed')
            ->will($this->returnValue(true));

        // Overriding BjyAuthorize\Service\Authorize service
        $this->getApplication()
            ->getServiceManager()
            ->setAllowOverride(true)
            ->setService('BjyAuthorize\Service\Authorize', $mockBjy);

        $this->dispatch('/book');
        $this->assertResponseStatusCode(200);
    }
}