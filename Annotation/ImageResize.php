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

use Fulgurio\ImageHandlerBundle\Annotation\ImageHandle;

/**
 * @Annotation
 */
class ImageResize implements ImageHandle
{
    private $size = array('width' => 50, 'height' => 50);

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options)
    {
        if (isset($options['width']))
        {
            $this->size['width'] = $options['width'];
        }
        if (isset($options['height']))
        {
            $this->size['height'] = $options['height'];
        }
    }

    /**
     * Picture handle
     *
     * @param object $entity
     * @param string $property
     */
    public function handle($entity, $property)
    {
        //@todo: getter
        $getter = 'get' . ucfirst($property);
        $filename = $entity->getId() . DIRECTORY_SEPARATOR . $entity->$getter();
        //@todo : image path
        if (is_dir(__DIR__ . '/../../../../web/'))
        {
            $path = __DIR__ . '/../../../../web/uploads/';
        }
        $imagine = new \Imagine\Gd\Imagine();
        $size = new \Imagine\Image\Box($this->getWidth(), $this->getHeight());
        $imagine->open($path . $filename)
                ->thumbnail($size)
                ->save($path . $filename);
    }

    /**
     * Getter picture width
     *
     * @return number
     */
    public function getWidth()
    {
        return $this->size['width'];
    }

    /**
     * Get picture height
     *
     * @return number
     */
    public function getHeight()
    {
        return $this->size['height'];
    }
}