<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $em = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        $qb = $em->createQueryBuilder();

        $query = $qb->select('b')
            ->from('\Library\Entity\Book', 'b')
            ->getQuery();

        $books = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        return new ViewModel(array(
            'books' => $books
        ));
    }
}
