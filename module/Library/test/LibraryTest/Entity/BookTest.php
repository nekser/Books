<?php
namespace LibraryTest\Entity;

use Library\Entity\Book;

class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testBookInitialState()
    {
        $book = new Book();
        $this->assertNull($book->getId());
        $this->assertNull($book->getName());
        $this->assertNull($book->getAuthor());
        $this->assertNull($book->getDescription());
        $this->assertNull($book->getFile());
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $book = new Book();
        $data = array(
            'author' => 'TestAuthor',
            'description' => 'TestDescription',
            'name' => 'TestName',
        );

        $book->exchangeArray($data);
        $this->assertSame($data['name'], $book->getName());
        $this->assertSame($data['author'], $book->getAuthor());
        $this->assertSame($data['description'], $book->getDescription());
    }

    public function testValuesDoNotChangeOnUpdateWhenTheyNull()
    {
        $book = new Book();
        $data = array(
            'author' => 'TestAuthor',
            'description' => 'TestDescription',
            'name' => 'TestName',
            'cover' => 'TestCover'
        );

        $book->exchangeArray($data);
        $updateData = array(
            'author' => 'UpdateAuthor',
        );
        $book->exchangeArray($updateData);
        $this->assertSame($data['name'], $book->getName());
        $this->assertSame($updateData['author'], $book->getAuthor());
        $this->assertSame($data['description'], $book->getDescription());
        $this->assertSame($data['cover'], $book->getCover());
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $book = new Book();
        $data = array(
            'author' => 'TestAuthor',
            'description' => 'TestDescription',
            'name' => 'TestName',
            'cover' => 'TestCover'
        );

        $book->exchangeArray($data);
        $copyArray = $book->getArrayCopy();

        $this->assertSame($data['name'], $copyArray['name']);
        $this->assertSame($data['author'], $copyArray['author']);
        $this->assertSame($data['description'], $copyArray['description']);
        $this->assertSame($data['cover'], $copyArray['cover']);
    }
}