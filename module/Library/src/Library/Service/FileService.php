<?php

namespace Library\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class FileService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const UPLOAD_DIR = 'upload/covers/';

    /**
     * @param array $file
     * @return string
     * @throws \Exception
     */
    public function uploadFile(array $file)
    {
        $uploadfile = self::UPLOAD_DIR . ($file['name']);
        if (!$this->fileExists($uploadfile)) {
            if (!move_uploaded_file($file['tmp_name'], 'public/' . $uploadfile)) {
                throw new \Exception("Error while file uploading");
            }
        }
        return $uploadfile;
    }

    private function fileExists($filePath)
    {
        return file_exists($filePath);
    }
}