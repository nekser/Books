<?php
namespace Library\Service\Factory;

use Library\Service\FileService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new FileService();
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}