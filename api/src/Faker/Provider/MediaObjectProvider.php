<?php

namespace App\Faker\Provider;

use App\Entity\AbstractMediaObject;
use App\Entity\MediaObject;
use App\Entity\Modele;
use App\Entity\User;
use Faker\Provider\Base as BaseProvider;
use League\Flysystem\FilesystemOperator;

class MediaObjectProvider extends BaseProvider
{
    public function __construct(
        private FilesystemOperator $defaultStorage,
    ){}

    public function upload(string $pathFile, MediaObject $mediaObject, User $user)
    {
        if(!file_exists($pathFile)) {
            throw new \RuntimeException($pathFile . ' not found');
        }

        $this->defaultStorage->write('/', file_get_contents($pathFile));

        return $pathFile;
    }
}
