<?php

namespace App\Contract;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface {
    public function upload(UploadedFile $file): string;
}