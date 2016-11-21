<?php

namespace Library\Service;


interface ReviewServiceInterface
{
    /**
     * @param $data
     * @param $user
     * @throws \InvalidArgumentException
     */
    public function createReview($data, $user = null);
}