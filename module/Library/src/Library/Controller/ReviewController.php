<?php
namespace Library\Controller;

use Library\Entity\Review;
use Library\Form\ReviewForm;
use Zend\Mvc\Controller\AbstractActionController;

class ReviewController extends AbstractActionController
{
    public function addAction()
    {
        $form = new ReviewForm();

        /**  @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $auth = $this->getServiceLocator()
                    ->get('zfcuser_auth_service');
                $bookId = $request->getPost('book');
                $qb = $em->createQueryBuilder();
                $query = $qb->select('b')
                    ->from('\Library\Entity\Book', 'b')
                    ->where($qb->expr()->eq('b.id', ':id'))
                    ->setParameter('id', $bookId)
                    ->setMaxResults(1)
                    ->getQuery();
                $book = $query->getSingleResult();
                if ($book) {
                    $review = new Review();
                    $review->exchangeArray($form->getData());
                    $review->setUser($auth->getIdentity());
                    $review->setBook($book);
                    $review->setCreatedAt(time());
                    $em->persist($review);
                    $em->flush();
                    $message = 'Review succesfully added!';
                    $this->flashMessenger()->addSuccessMessage($message);
                } else {
                    /** @var \Zend\Http\Response $response */
                    $response = $this->getResponse();
                    $response->setStatusCode(400);
                }
            } else {
                $message = 'Error while adding review';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }

        return $this->redirect()
            ->toRoute(
                'book',
                array(
                    'action' => 'view',
                    'id' => $review->getBook()->getId()
                )
            );
    }
}