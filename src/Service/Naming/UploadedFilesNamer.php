<?php

namespace App\Service\Naming;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Naming\Polyfill\FileExtensionTrait;

class UploadedFilesNamer implements NamerInterface
{
    use FileExtensionTrait;

    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);
        $extension = $this->getExtension($file);
        $name = $object->getUser()->getId();
        $date = $object->getCreatedAt()->format('Y-m-d');
        $name = $name . '-' . $date . '.' . $extension;
        return $name;
    }

}