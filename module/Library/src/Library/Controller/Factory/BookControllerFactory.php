<?php
namespace Library\Controller\Factory;

use Library\BookController\BookController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new BookController();
        $controller->setServiceLocator($serviceLocator);
        return $controller;
    }
}