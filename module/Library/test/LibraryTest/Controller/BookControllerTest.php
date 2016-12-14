<?php
namespace LibraryTest\Controller;

use BjyAuthorize\Exception\UnAuthorizedException;
use Library\Controller\BookController;

class BookControllerTest extends BaseControllerTestCase
{

    public function testIndexActionForbiddenForNonAuthorizedUser()
    {
        $this->dispatch('/book');
        $this->assertResponseStatusCode(403);
        $this->assertApplicationException(UnAuthorizedException::class);
    }

    public function testIndexAction()
    {
        $em = $this->getEntityManagerMock();
        $this->serviceLocator->setService('em', $em);

        $ZfcUserMock = $this->getMock('ZfcUser\Entity\User');
        $ZfcUserMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('1'));
        $authMock = $this->getMock(
            'Zend\Authentication\AuthenticationServiceInterface'
        );
        $authMock->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        $authMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($ZfcUserMock));
        $this->serviceLocator->setService(
            'zfcuser_auth_service', $authMock
        );

        $bookServiceMock = $this->getMockBuilder('Library\Service\BookService')
            ->disableOriginalConstructor()
            ->setMethods(array('fetchAll'))
            ->getMock();

        $bookServiceMock->expects($this->any())
            ->method('fetchAll')
            ->will($this->returnValue(true));

        $this->serviceLocator->setService('BookService', $bookServiceMock);

        $controller = new BookController($bookServiceMock, $authMock);

        $result = $controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertFalse(is_null($result->getVariable('books')));

        $books = $result->getVariable('books');
        $this->assertTrue($books);
    }
}