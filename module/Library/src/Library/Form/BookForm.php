<?php
namespace Library\Form;

use Library\Form\InputFilter\BookInputFilter;
use Zend\Form\Element\File;
use Zend\Form\Form;

class BookForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('book');
        $this->setAttribute('method', 'post');
        $this->setAttribute(
            'enctype',
            'multipart/form-data'
        );
        $this->addElements();
        $this->setInputFilter(new BookInputFilter());
    }

    protected function addElements()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'user',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));

        $coverFile = new File('image-file');
        $coverFile->setLabel('Cover Image Upload')
            ->setAttribute('id', 'image-file');
        $this->add($coverFile);

        $bookFile = new File('book-file');
        $bookFile->setLabel('Book File Upload')
            ->setAttribute('id', 'book-file');
        $this->add($bookFile);

        $this->add(array(
            'name' => 'author',
            'type' => 'Text',
            'options' => array(
                'label' => 'Author',
            ),
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Description',
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