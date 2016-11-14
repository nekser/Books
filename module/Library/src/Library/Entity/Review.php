<?php

namespace Library\Entity;

class Review
{
    /** @var  int */
    private $id;
    /** @var  string */
    private $text;
    /** @var  \BookUser\Entity\User */
    private $user;
    /** @var  \Library\Entity\Book */
    private $book;
    /** @var  int */
    private $created_at;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return \BookUser\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \BookUser\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param int $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

}