<?php
namespace Library\Service\Factory;


use Library\Service\ReviewService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReviewServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new ReviewService();
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}