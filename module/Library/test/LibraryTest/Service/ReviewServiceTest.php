<?php
namespace LibraryTest\Service;

use BookUser\Entity\User;
use Library\Service\ReviewService;
use LibraryTest\Bootstrap;
use Psr\Log\InvalidArgumentException;

class ReviewServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testReviewCreatedSuccesfully()
    {
        $reviewService = Bootstrap::getServiceManager()->get('ReviewService');
        $data = array(
            'text' => 'TestReviewText',
            'book' => 100000000
        );
        $user = new User();
        $user->setId(1000000000);

        $reviewService->createReview($data, $user);
    }

    public function testReviewNotCreatedWithoutUser()
    {
        $reviewService = Bootstrap::getServiceManager()->get('ReviewService');
        $data = array(
            'book' => 1000
        );
        $user = null;
        $reviewService->createReview($data, $user);
        $this->setExpectedException(InvalidArgumentException::class, 'User is not found ');
    }
}