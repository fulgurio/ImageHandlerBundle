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
     * @param string $path
     * @param string $filename
     */
    public function handle($path, $filename)
    {
        if ($path[strlen($path) - 1] != DIRECTORY_SEPARATOR)
        {
            $path .= DIRECTORY_SEPARATOR;
        }
        $imagine = new \Imagine\Gd\Imagine();
        $size = new \Imagine\Image\Box($this->getWidth(), $this->getHeight());
        $imagine->open($path . $filename)
                ->thumbnail($size)
                ->save($path . $filename);
    }

    public function getSize()
    {
        return $this->size;
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