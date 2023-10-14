<?php

namespace App\Service;

use App\Contract\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService implements FileUploaderInterface {
    public function __construct(private SluggerInterface $slugger, private string $cvDirectory)
    {
    }

    public function upload(UploadedFile $file): string
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $file->guessExtension();
        $filename = $this->slugger->slug($filename) . "-" . uniqid() . "." . $fileExtension;

        try {
            $file->move($this->cvDirectory, $filename);
        } catch (FileException $e) {
            throw $e->getMessage();
        }


        return $filename;
    }
}