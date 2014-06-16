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

use Doctrine\Common\Annotations\Reader;//This thing read annotations

class AnnotationDriver {

    const IMAGE_RESIZE_FIELD_ANNOTATION = 'Fulgurio\ImageHandlerBundle\Annotation\ImageResize';

    /**
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;


    /**
     * Constructor
     *
     * @param \Doctrine\Common\Annotations\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     *
     * @param \ReflectionClass $class
     * @return array
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = array();
        foreach ($class->getProperties() as $property)
        {
            $uploadableField = $this->reader->getPropertyAnnotation($property, self::IMAGE_RESIZE_FIELD_ANNOTATION);
            if ($uploadableField === null)
            {
                continue;
            }
            $metadata[$property->name] = $uploadableField;
        }
        return $metadata;
    }
}
