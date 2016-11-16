<?php
namespace Library\Form\InputFilter;

use Zend\InputFilter\InputFilter;

class ReviewInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'text',
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