<?php
namespace Library\Controller;

use Library\Form\BookForm;
use Library\Form\ReviewForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default');
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->where($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
            ->setParameter('userId', $auth->getIdentity()->getId())
            ->getQuery();

        $books = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        return new ViewModel(array(
            'books' => $books
        ));
    }

    public function addAction()
    {
        $form = new BookForm();
        $form->get('submit')->setValue('Add');


        /**  @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $auth = $this->getServiceLocator()
                    ->get('zfcuser_auth_service');

                $book = new \Library\Entity\Book();
                $book->exchangeArray($form->getData());
                $book->setUser(
                    $auth->getIdentity()
                );

                $em->persist($book);
                $em->flush();
                $message = 'Book succesfully created!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $message = 'Error while saving book';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }

        return array('form' => $form);
    }

    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->where($qb->expr()->eq('b.id', ':id'))
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $book = $query->getSingleResult(/*\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY*/);

        $reviewForm = new ReviewForm();
        $reviewForm->setData(
            array('book' => $book->getId())
        );
        $reviewForm->get('submit')->setValue('Add');
        return new ViewModel(
            array(
                'book' => $book,
                'reviewForm' => $reviewForm
            )
        );
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

        $form = new BookForm();
        $form->get('submit')->setValue('Save');
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

            $qb = $em->createQueryBuilder();

            $query = $qb->select('b')
                ->from('\Library\Entity\Book', 'b')
                ->where($qb->expr()->eq('IDENTITY(b.user)', ':userId'))
                ->andWhere($qb->expr()->eq('b.id', ':id'))
                ->setParameter('userId', $auth->getIdentity()->getId())
                ->setParameter('id', $id)
                ->setMaxResults(1)
                ->getQuery();

            $book = $query->getSingleResult(/*\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY*/);
            if (!$book) {
                return $this->notFoundAction();
            }

            // Fill form data.
            $form->bind($book);
            return array('form' => $form, 'id' => $id, 'book' => $book);
        } else {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $data = $form->getData();
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

                    $book = $query->getSingleResult();
                } catch (\Exception $ex) {
                    return $this->redirect()->toRoute('book', array(
                        'action' => 'index'
                    ));
                }
                $book->exchangeArray($form->getData());
                $em->persist($book);
                $em->flush();
                $message = 'Book succesfully saved!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $message = 'Error while saving book';
                $this->flashMessenger()->addErrorMessage($message);
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $auth = $this->getServiceLocator()
            ->get('zfcuser_auth_service');

        try {
            /** @var \Library\Entity\Book $book */
            $book = $em->find('Library\Entity\Book', $id);
            if ($book->getUser()->getId() != $auth->getIdentity()->getId()) {
                //FIXME add Forbidden
                return $this->notFoundAction();
            }
            $em->remove($book);
            $em->flush();
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Error while deleting book');
            return $this->redirect()->toRoute('book');
        }
        $this->flashMessenger()->addSuccessMessage('Book was successfully deleted');
        return $this->redirect()->toRoute('book');
    }
}