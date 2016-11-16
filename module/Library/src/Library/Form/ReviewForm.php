<?php
namespace Library\Form;

use Library\Form\InputFilter\ReviewInputFilter;
use Zend\Form\Form;

class ReviewForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('review');
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->setInputFilter(new ReviewInputFilter());
    }

    protected function addElements()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'book',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Review',
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
            )
        ));
    }
}