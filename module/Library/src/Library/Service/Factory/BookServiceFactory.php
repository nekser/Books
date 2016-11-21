<?php
namespace Library\Service\Factory;

use Library\Service\BookService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new BookService(
            $serviceLocator->get('em'),
            $serviceLocator->get('zfcuser_auth_service')
        );
        return $service;
    }
}