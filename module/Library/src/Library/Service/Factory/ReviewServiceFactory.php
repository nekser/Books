<?php
namespace Library\Service\Factory;

use Library\Service\ReviewService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReviewServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new ReviewService(
            $serviceLocator->get('em'),
            $serviceLocator->get('zfcuser_auth_service')
        );
        return $service;
    }
}