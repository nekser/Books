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
        $sm = $this->getServiceLocator();

        $auth = $sm
            ->get('zfcuser_auth_service');

        /** @var \Library\Service\BookService $bookService */
        $bookService = $sm->get('BookService');

        $books = $bookService->fetchAll($auth->getIdentity()->getId());

        return new ViewModel(array(
            'books' => $books
        ));
    }

    public function addAction()
    {
        $sm = $this->getServiceLocator();

        $auth = $sm
            ->get('zfcuser_auth_service');

        /** @var \Library\Service\BookService $bookService */
        $bookService = $sm->get('BookService');

        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        /**  @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $bookService->createBook($form->getData());
                } catch (\Exception $e) {
                    $message = 'Error while saving new book';
                    $this->flashMessenger()->addErrorMessage($message . "Info: " . $e->getMessage());
                    return array('form' => $form);
                }
                $message = 'Book succesfully created!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $message = 'Input is not valid';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }

        return array('form' => $form);
    }

    public function viewAction()
    {
        /** @var \Library\Service\BookService $bookService */
        $bookService = $this->getServiceLocator()
            ->get('BookService');

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }

        /** @var \Library\Entity\Book $book */
        $book = $bookService->fetch($id);
        if (!$book) {
            return $this->notFoundAction();
        }

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
        /** @var \Library\Service\BookService $bookService */
        $bookService = $this->getServiceLocator()
            ->get('BookService');

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
            $book = $bookService->fetch($id, true);
            if (!$book) {
                return $this->notFoundAction();
            }

            $form->bind($book);
            return array('form' => $form, 'id' => $id, 'book' => $book);
        } else {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $bookService->updateBook($form->getData());
                } catch (\Exception $e) {
                    $message = 'Error while saving book' . $e->getMessage();
                    $this->flashMessenger()->addErrorMessage($message);
                    return array('form' => $form, 'id' => $id);
                }
                $message = 'Book succesfully saved!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $message = "Invalid input";
                $this->flashMessenger()->addErrorMessage($message);
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    public function deleteAction()
    {
        /** @var \Library\Service\BookService $bookService */
        $bookService = $this->getServiceLocator()
            ->get('BookService');

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }
        try {
            $bookService->deleteBook($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Error. Info: ' . $e->getMessage());
            return $this->redirect()->toRoute('book');
        }
        $this->flashMessenger()->addSuccessMessage('Book was successfully deleted');
        return $this->redirect()->toRoute('book');
    }
}