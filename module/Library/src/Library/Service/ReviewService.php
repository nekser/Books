<?php

namespace Library\Service;

use Doctrine\ORM\EntityManagerInterface;
use Library\Entity\Review;
use Zend\Authentication\AuthenticationServiceInterface;

class ReviewService implements ReviewServiceInterface
{
    /** @var  EntityManagerInterface */
    private $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /** @var AuthenticationServiceInterface */
    private $authenticationService;

    /**
     * @return AuthenticationServiceInterface
     */
    public function getAuthService()
    {
        return $this->authenticationService;
    }

    public function __construct(EntityManagerInterface $entityManager, AuthenticationServiceInterface $authenticationService)
    {
        $this->entityManager = $entityManager;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $data
     * @param $user
     * @throws \InvalidArgumentException
     */
    public function createReview($data, $user = null)
    {
        /** @var \BookUser\Entity\User $user */
        $user = $this->getAuthService()
            ->getIdentity();
        if (!$user) {
            throw new \InvalidArgumentException('User is not found');
        }

        /** @var \Library\Entity\Book $book */
        $book = $this->getEntityManager()
            ->getRepository('\Library\Entity\Book')
            ->find($data['book']);
        if (!$book) {
            throw new \InvalidArgumentException('Book is not found');
        }
        $review = new Review();
        $review->exchangeArray($data);
        $review->setUser($user);
        $review->setBook($book);
        $review->setCreatedAt(time());

        $this->getEntityManager()->persist($review);
        $this->getEntityManager()->flush();
    }
}