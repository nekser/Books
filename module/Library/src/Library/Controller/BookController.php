<?php
namespace Library\Controller;

use Library\Form\BookForm;
use Library\Form\ReviewForm;
use Library\Service\BookServiceInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController
{
    /** @var  BookServiceInterface */
    private $bookService;

    /**
     * @return BookServiceInterface
     */
    public function getBookService()
    {
        return $this->bookService;
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

    public function __construct(BookServiceInterface $bookService, AuthenticationServiceInterface $authService)
    {
        $this->bookService = $bookService;
        $this->authenticationService = $authService;
    }

    public function indexAction()
    {
        $sm = $this->getServiceLocator();

        $auth = $this->getAuthService();

        $bookService = $this->getBookService();

        $books = $bookService->fetchAll($auth->getIdentity()->getId());

        return new ViewModel(array(
            'books' => $books
        ));
    }

    public function addAction()
    {
        $auth = $this->getAuthService();
        $bookService = $this->getBookService();

        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        /**  @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData(array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            ));
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $data['cover'] = str_replace("public/", "", $data['image-file']['tmp_name']);
                    $data['file'] = str_replace("public/", "", $data['book-file']['tmp_name']);
                    $bookService->createBook($data);
                } catch (\Exception $e) {
                    $message = 'Error while saving new book';
                    $this->flashMessenger()->addErrorMessage($message . " Info: " . $e->getMessage());
                    return array('form' => $form);
                }
                $message = 'Book succesfully created!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $this->flashMessenger()->addErrorMessage($form->getMessages());
            }
        }

        return array('form' => $form);
    }

    public function viewAction()
    {
        $bookService = $this->getBookService();

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
        $bookService = $this->getBookService();

        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->notFoundAction();
        }
        $auth = $this->getAuthService();

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
                $files = $request->getFiles()->toArray();
                $cover = $files["image-file"];
                $bookFile = $files["book-file"];
                try {
                    $data = $form->getData();
                    //FIXME:
                    if ($cover["name"] !== "") {
                        $data['cover'] = str_replace("public/", "", $data['image-file']['tmp_name']);
                    }
                    //FIXME:
                    if ($bookFile["name"] !== "") {
                        $data['file'] = str_replace("public/", "", $data['book-file']['tmp_name']);
                    }
                    $bookService->updateBook($data);
                } catch (\Exception $e) {
                    $message = 'Error while saving book' . $e->getMessage();
                    $this->flashMessenger()->addErrorMessage($message);
                    return array('form' => $form, 'id' => $id);
                }
                $message = 'Book succesfully saved!';
                $this->flashMessenger()->addSuccessMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $this->flashMessenger()->addErrorMessage($form->getMessages());
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    public function deleteAction()
    {
        $bookService = $this->getBookService();

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