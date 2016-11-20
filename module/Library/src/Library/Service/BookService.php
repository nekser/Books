<?php
namespace Library\Service;

use Doctrine\ORM\AbstractQuery;
use Library\Entity\Book;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class BookService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param int | null $userId
     * @return array
     */
    public function fetchAll($userId = null)
    {
        $sm = $this->getServiceLocator();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $sm->get('em');
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
     * @param $checkIdentity
     * @return null | \Library\Entity\Book
     */
    public function fetch($id, $checkIdentity = false)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('em');
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->where($qb->expr()->eq('b.id', ':id'))
            ->setParameter('id', $id);
        if ($checkIdentity) {
            $query->andWhere($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
                ->setParameter('userId', $auth->getIdentity()->getId());
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
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()->get('em');

        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

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

    public function updateBook($data)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('em');
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

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
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('em');
        /** @var ZfcUserAuthentication $auth */
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

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