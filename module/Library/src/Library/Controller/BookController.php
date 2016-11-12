<?php
namespace Library\Controller;

use Library\Form\BookForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
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

                $book = new \Library\Entity\Book();
                $book->exchangeArray($form->getData());
//                $book->setCreated(time());
                $em->persist($book);
                $em->flush();
                $message = 'Book succesfully created!';
                $this->flashMessenger()->addMessage($message);
                return $this->redirect()->toRoute('book');
            } else {
                $message = 'Error while saving book';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }

        return array('form' => $form);
    }

    public function updateAction()
    {

    }

    public function removeAction()
    {

    }
}