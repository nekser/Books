<?php
namespace Library\Controller\Factory;

use Library\Controller\BookController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new BookController(
            $serviceLocator->getServiceLocator()
                ->get('BookService'),
            $serviceLocator->getServiceLocator()
                ->get('zfcuser_auth_service')
        );
        $controller->setServiceLocator($serviceLocator);
        return $controller;
    }
}