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
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        return $books;
    }

    /**
     * @param $id
     * @return null | \Library\Entity\Book
     */
    public function fetch($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('em');

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->where($qb->expr()->eq('b.id', ':id'))
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $book = $query->getSingleResult();

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

    public function updateBook()
    {

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