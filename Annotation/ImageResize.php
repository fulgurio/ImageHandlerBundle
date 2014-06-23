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
class ImageResize extends ImageHandle
{
    /**
     * Picture size
     * @var array
     */
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
        parent::__construct($options);
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

    /**
     * Size getter (width and height in an array)
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Picture width getter
     *
     * @return number
     */
    public function getWidth()
    {
        return $this->size['width'];
    }

    /**
     * Picture width setter
     *
     * @param number $width
     * @return ImageResize
     */
    public function setWidth($width)
    {
        $this->size['width'] = $width;
        return $this;
    }

    /**
     * Picture height getter
     *
     * @return number
     */
    public function getHeight()
    {
        return $this->size['height'];
    }

    /**
     * Picture height setter
     *
     * @param number $height
     * @return ImageResize
     */
    public function setHeight($height)
    {
        $this->size['height'] = $height;
        return $this;
    }
}