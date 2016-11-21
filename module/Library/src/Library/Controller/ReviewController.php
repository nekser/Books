<?php
namespace Library\Controller;

use Library\Form\ReviewForm;
use Library\Service\ReviewServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;

class ReviewController extends AbstractActionController
{
    /** @var  ReviewServiceInterface */
    private $reviewService;

    /**
     * @return ReviewServiceInterface
     */
    public function getReviewService()
    {
        return $this->reviewService;
    }

    public function __construct(ReviewServiceInterface $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function addAction()
    {
        $reviewService = $this->getReviewService();
        $form = new ReviewForm();
        /**  @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $reviewService->createReview($form->getData());
                    $message = "Review was successfully added";
                    $this->flashMessenger()->addSuccessMessage($message);
                } catch (\Exception $e) {
                    $message = "Error while saving review. Info: " . $e->getMessage();
                    $this->flashMessenger()->addErrorMessage($message);
                }
            } else {
                $this->flashMessenger()->addErrorMessage($form->getMessages());
            }
            return $this->redirect()
                ->toRoute(
                    'book',
                    array(
                        'action' => 'view',
                        'id' => $request->getPost('book')
                    )
                );
        }
    }
}