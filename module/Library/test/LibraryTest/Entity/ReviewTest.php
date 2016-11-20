<?php
namespace LibraryTest\Entity;


use Library\Entity\Review;

class ReviewTest extends \PHPUnit_Framework_TestCase
{
    public function testReviewInitialState()
    {
        $review = new Review();
        $this->assertNull($review->getId());
        $this->assertNull($review->getText());
        $this->assertNull($review->getUser());
        $this->assertNull($review->getBook());
        $this->assertNull($review->getCreatedAt());
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $review = new Review();
        $data = array(
            'text' => 'TestText',
        );

        $review->exchangeArray($data);
        $this->assertSame($data['text'], $review->getText());
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $review = new Review();
        $data = array(
            'text' => 'TestText',
        );

        $review->exchangeArray($data);
        $copyArray = $review->getArrayCopy();

        $this->assertSame($data['text'], $copyArray['text']);
    }
}