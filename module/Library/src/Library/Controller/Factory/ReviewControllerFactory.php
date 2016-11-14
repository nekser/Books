<?php
namespace Library\Controller\Factory;

use Library\Controller\ReviewController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReviewControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new ReviewController();
        $controller->setServiceLocator($serviceLocator);
        return $controller;
    }
}