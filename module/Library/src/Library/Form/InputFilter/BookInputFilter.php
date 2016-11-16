<?php
namespace Library\Form\InputFilter;

use Zend\InputFilter\InputFilter;

class BookInputFilter extends InputFilter
{
    public function __construct()
    {
        //name
        $this->add(array(
            'name' => 'name',
            'required' => 'true',
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        //cover
        $this->add(array(
            'name' => 'image-file',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'filesize',
                    'options' => array(
                        'max' => 204800
                    )
                ),
                array(
                    'name' => 'filemimetype',
                    'options' => array(
                        'mimeType' => 'image/png,image/x-png,image/pjpeg,image/jpeg',
                        'magicFile' => false
                    )
                ),
                array(
                    'name' => 'fileimagesize',
                    'options' => array(
                        'maxWidth' => 1000,
                        'maxHeight' => 1000
                    )
                )
            ),
            'filters' => array(
                array(
                    'name' => 'filerenameupload',
                    'options' => array(
                        'target' => './data/upload/covers/cover',
                        'randomize' => true,
                        'use_upload_extension' => true
                    )
                )
            )
        ));
        //author
        $this->add(array(
            'name' => 'author',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 5,
                        'max' => 100
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        //description
        $this->add(array(
            'name' => 'description',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 5,
                        'max' => 300
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

    }
}