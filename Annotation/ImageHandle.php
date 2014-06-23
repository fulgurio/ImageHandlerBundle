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

abstract class ImageHandle
{
    /**
     * Mapping name
     * @var string
     */
    private $mappingName = '';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options)
    {
        if (isset($options['mapping']))
        {
            $this->mappingName = $options['mapping'];
        }
    }

    /**
     * Picture handle
     *
     * @param string $path
     * @param string $filename
     */
    abstract public function handle($path, $filename);

    /**
     * Mapping name getter
     *
     * @return string
     */
    final public function getMappingName() {
        return $this->mappingName;
    }
}