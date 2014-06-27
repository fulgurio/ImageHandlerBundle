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

use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * @Annotation
 */
class ImageHandle
{
    /**
     * Mapping name
     * @var string
     */
    private $mappingName = '';

    /**
     * Action (crop or resize)
     * @var string
     */
    private $actionName = 'resize';

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
        if (isset($options['action']))
        {
            $this->actionName = $options['action'];
        }
        if (isset($options['mapping']))
        {
            $this->mappingName = $options['mapping'];
        }
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
        if ($this->actionName != 'resize' && $this->actionName != 'crop')
        {
            throw new \Exception('Action ' . $this->actionName . ' doesn\'t exist (crop or resize available)');
        }
        if ($path[strlen($path) - 1] != DIRECTORY_SEPARATOR)
        {
            $path .= DIRECTORY_SEPARATOR;
        }
        $this->{$this->actionName}($path, $filename);
    }

    /**
     * Crop picture handle
     *
     * @param string $path
     * @param string $filename
     */
    private function crop($path, $filename)
    {
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
        $box = new Box($width, $height);
        $original->resize($box)
                ->crop($cropPoint, new \Imagine\Image\Box($this->getWidth(), $this->getHeight()))
                ->save($path . $filename);
    }

    /**
     * Resize picture handle
     *
     * @param string $path
     * @param string $filename
     */
    private function resize($path, $filename)
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
     * Mapping name getter
     *
     * @return string
     */
    public function getMappingName() {
        return $this->mappingName;
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

    /**
     * Action setter
     *
     * @param string $action
     * @return ImageResize
     */
    public function setAction($action)
    {
        $this->actionName = $action;
        return $this;
    }
}