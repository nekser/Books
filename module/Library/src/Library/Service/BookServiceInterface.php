<?php
namespace Library\Service;

interface BookServiceInterface
{
    /**
     * @param int | null $userId
     * @return array
     */
    public function fetchAll($userId = null);

    /**
     * @param $id
     * @param $checkIdentity
     * @return null | \Library\Entity\Book
     */
    public function fetch($id, $checkIdentity = false);

    /**
     * @param $data
     * @throws \Exception
     */
    public function createBook($data);

    /**
     * @param $data
     * @throws \Exception
     */
    public function updateBook($data);

    /**
     * @param $id
     * @throws \Exception
     */
    public function deleteBook($id);
}