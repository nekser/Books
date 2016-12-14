<?php
namespace Library\Service;

use Doctrine\ORM\EntityManagerInterface;
use Library\Entity\Book;
use Zend\Authentication\AuthenticationServiceInterface;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class BookService implements BookServiceInterface
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
     * @param int | null $userId
     * @return array
     */
    public function fetchAll($userId = null)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b');

        if ($userId) {
            $query->where($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
                ->setParameter('userId', $userId);
        }

        $books = $query->getQuery()
            ->getResult();

        return $books;
    }

    /**
     * @param $id
     * @param $userID
     * @return null | \Library\Entity\Book
     */
    public function fetch($id, $userID = null)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->where($qb->expr()->eq('b.id', ':id'))
            ->setParameter('id', $id);
        if ($userID) {
            $query->andWhere($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
                ->setParameter('userId', $userID);
        }
        $query->setMaxResults(1);

        $book = $query->getQuery()->getSingleResult();

        return $book;
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function createBook($data)
    {
        $em = $this->getEntityManager();
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getAuthService();

        $user = $auth->getIdentity();
        if (!$user) {
            throw new \Exception('User is not found');
        }

        $book = new Book();
        $book->exchangeArray($data);
        $book->setUser($user);
        $em->persist($book);
        $em->flush();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function updateBook($data)
    {
        $em = $this->getEntityManager();
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getAuthService();

        $id = $data['id'];
        try {
            $qb = $em->createQueryBuilder();

            $query = $qb->select('b')
                ->from('\Library\Entity\Book', 'b')
                ->where($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
                ->andWhere($qb->expr()->eq('b.id', ':id'))
                ->setParameter('userId', $auth->getIdentity()->getId())
                ->setParameter('id', $id)
                ->setMaxResults(1)
                ->getQuery();
            /** @var \Library\Entity\Book $book */
            $book = $query->getSingleResult();
        } catch (\Exception $e) {
            throw new \Exception("No such book or not enough permissions.", 0, $e);
        }
        if (!isset($data['cover'])) {
            $data['cover'] = $book->getCover();
        }
        $book->exchangeArray($data);
        $book->setUser($auth->getIdentity());
        try {
            $em->persist($book);
            $em->flush();
        } catch (\Exception $e) {
            throw new \Exception("Error while book saving.", 0, $e);
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function deleteBook($id)
    {
        $em = $this->getEntityManager();
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getAuthService();

        try {
            /** @var \Library\Entity\Book $book */
            $book = $em->find('Library\Entity\Book', $id);
            //TODO: Remove files

            if ($book->getUser()->getId() != $auth->getIdentity()->getId()) {
                throw new \Exception('You have not permissions to delete this book');
            }
            $em->remove($book);
            $em->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error while deleting book' . $e->getTraceAsString());
        }
    }
}