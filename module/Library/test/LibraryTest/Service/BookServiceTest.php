<?php
namespace LibraryTest\Service;

use Library\Service\BookService;
use LibraryTest\Traits\DoctrineTestCaseTrait;

class BookServiceTest extends \PHPUnit_Framework_TestCase
{
    use DoctrineTestCaseTrait;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage User is not found
     */
    public function testCreateBookWithoutUserThroughsException()
    {
        $em = $this->getEntityManagerMock();

        $authMock = $this->getMock(
            'Zend\Authentication\AuthenticationServiceInterface'
        );
        $authMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue(null));


        $service = new BookService($em, $authMock);

        $data = array('stub');

        $service->createBook($data);
    }

}