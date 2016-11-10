<?php

namespace Library\Entity;

class Review
{
    /** @var  int */
    private $id;
    /** @var  string */
    private $text;
    /** @var  \BookUser\Entity\User */
    private $owner;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param \BookUser\Entity\User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
}