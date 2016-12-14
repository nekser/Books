<?php
namespace LibraryTest\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Library\Service\ReviewService;
use LibraryTest\Traits\DoctrineTestCaseTrait;

class ReviewServiceTest extends \PHPUnit_Framework_TestCase
{
    use DoctrineTestCaseTrait;

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Book is not found
     */
    public function testReviewsNotCreatedWhenBookIsInvalid()
    {
        $em = $this->getEntityManagerMock();

        $bookRepositoryMock = $this->getMock(ObjectRepository::class);
        $bookRepositoryMock->expects($this->any())
            ->method('find')
            ->with($this->anything())
            ->willReturn(null);


        $em->expects($this->any())
            ->method('getRepository')
            ->with('\Library\Entity\Book')
            ->willReturn($bookRepositoryMock);

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

        $service = new ReviewService($em, $authMock);

        $data = array('book' => 'stub');

        $service->createReview($data);


        $this->assertTrue(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage User is not found
     */
    public function testReviewNotCreatedWhenUserIsInvalid()
    {
        $em = $this->getEntityManagerMock();
        $authMock = $this->getMock(
            'Zend\Authentication\AuthenticationServiceInterface'
        );
        $authMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue(null));

        $service = new ReviewService($em, $authMock);

        $data = array('stub');

        $service->createReview($data);

        $this->assertTrue(true);
    }
}