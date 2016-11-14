<?php
namespace Library\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ReviewController extends AbstractActionController
{
    public function addAction()
    {
        $this->redirect()->toRoute('book');
    }
}