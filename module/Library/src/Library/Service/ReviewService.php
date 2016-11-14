<?php

namespace Library\Service;


use Library\Entity\Review;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class ReviewService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param $data
     * @param $user
     * @throws \Exception
     */
    public function createReview($data, $user = null)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('em');
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');
        /** @var \Library\Entity\Book $book */
        $book = $em->getRepository('\Library\Entity\Book')
            ->find($data['book']);
        /** @var \BookUser\Entity\User $user */
        $user = $auth->getIdentity();
        if (!$user) {
            throw new \Exception('User is not found');
        }
        if (!$book) {
            throw new \Exception('Book is not found');
        }
        $review = new Review();
        $review->exchangeArray($data);
        $review->setUser($user);
        $review->setBook($book);
        $review->setCreatedAt(time());
        $em->persist($review);
        $em->flush();

    }
}