<?php

namespace Library\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Book
{
    const DEFAULT_COVER = 'upload/covers/no-photo.png';

    /** @var  int */
    private $id;
    /** @var  string */
    private $name;
    /** @var  string */
    private $author;
    /** @var  string */
    private $description;
    /** @var  string */
    private $cover;
    /** @var  \Doctrine\Common\Collections\ArrayCollection */
    private $gallery;
    /** @var string */
    private $file;
    /** @var  ArrayCollection */
    private $reviews;
    /** @var  \BookUser\Entity\User */
    private $user;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
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
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return array
     */
    public function getReviews()
    {
        return $this->reviews->getValues();
    }

    /**
     * @param Review $review
     */
    public function addReview($review)
    {
        $this->reviews[] = $review;
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getCover()
    {
        if (file_exists('public/' . $this->cover)
            && !is_dir('public/' . $this->cover)
        ) {
            return $this->cover;
        } else {
            return self::DEFAULT_COVER;
        }
    }

    /**
     * @param string $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @param object $data
     * Helper function.
     */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key) &&
                !is_null($val)
            ) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Helper function
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}