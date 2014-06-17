<?php
/*
 * This file is part of the FulgurioImageHandlerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\ImageHandlerBundle\Annotation;

use Fulgurio\ImageHandlerBundle\Annotation\ImageResize;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * @Annotation
 */
class ImageCrop extends ImageResize
{
    public function handle($entity, $property)
    {
        //@todo : chemin de l'image
        //@todo: getter
        $getter = 'get' . ucfirst($property);
        $filename = $entity->$getter();
        //@todo : image path
        $path = $this->getAbsUploadPath();
        $imagine = new \Imagine\Gd\Imagine();
        $original = $imagine->open($path . $filename);
        $originalSize = $original->getSize();
        // landscape
        if ($originalSize->getWidth() > $originalSize->getHeight())
        {
            $width  = $originalSize->getWidth() * ($this->getHeight() / $originalSize->getHeight());
            $height =  $this->getHeight();
            //we center the crop in relation to the width
            $cropPoint = new Point(($width - $this->getWidth()) / 2, 0);
        }
        else
        {
            $width  = $this->getWidth();
            $height = $originalSize->getHeight() * ($this->getWidth() / $originalSize->getWidth());
            //we center the crop in relation to the height
            $cropPoint = new Point(0, ($height - $this->getHeight()) / 2);
        }
        $box= new Box($width, $height);
        $original->resize($box)
                ->crop($cropPoint, new \Imagine\Image\Box($this->getWidth(), $this->getHeight()))
                ->save($path . $filename);
    }
}