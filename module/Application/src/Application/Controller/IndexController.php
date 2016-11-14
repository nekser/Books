<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var \Library\Service\BookService $bookService */
        $bookService = $this
            ->getServiceLocator()
            ->get('BookService');

        $books = $bookService->fetchAll();

        return new ViewModel(array(
            'books' => $books
        ));
    }
}
