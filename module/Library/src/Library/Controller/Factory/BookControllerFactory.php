<?php
namespace Library\Controller\Factory;

use Library\Controller\BookController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new BookController($serviceLocator->get('BookService'));
        $controller->setServiceLocator($serviceLocator);
        return $controller;
    }
}