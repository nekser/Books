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
                        'target' => 'public/upload/covers/cover',
                        'randomize' => true,
                        'use_upload_extension' => true
                    )
                )
            )
        ));
        //book
        $this->add(array(
            'name' => 'book-file',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'filesize',
                    'options' => array(
                        'max' => 2048000
                    )
                ),
                array(
                    'name' => 'filemimetype',
                    'options' => array(
                        'mimeType' => 'application/epub+zip',
                        'magicFile' => false
                    )
                )
            ),
            'filters' => array(
                array(
                    'name' => 'filerenameupload',
                    'options' => array(
                        'target' => 'public/upload/books/book',
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
                        'max' => 30000
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