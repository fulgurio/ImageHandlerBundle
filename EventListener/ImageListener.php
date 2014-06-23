<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fulgurio\ImageHandlerBundle\EventListener;

use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of ImageListener
 *
 * @author guerar_v
 */
class ImageListener implements EventSubscriberInterface
{
    private $driver;

    const IMAGE_RESIZE_FIELD_ANNOTATION   = 'Fulgurio\ImageHandlerBundle\Annotation\ImageResize';


    function __construct(AnnotationReader $reader, PropertyMappingFactory $factory)
    {
        $this->reader = $reader;
        $this->factory = $factory;
    }


    /**
     * Add events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(Events::POST_UPLOAD => 'onPostUpload');
    }

    /**
     *
     * @param \Vich\UploaderBundle\Event\Event $event
     */
    public function onPostUpload(Event $event)
    {
        $obj = $event->getObject();
        $mappings = $this->factory->fromObject($obj);
        foreach ($mappings as $mapping) {
            $file = $mapping->getFile($obj);
            if ($file === null || !($file instanceof UploadedFile))
            {
                continue;
            }
            // determine the file's directory
            $dir = $mapping->getUploadDestination() . $mapping->getUploadDir($obj);

            $property = new \ReflectionProperty($obj, $mapping->getFileNamePropertyName());
            $uploadableField = $this->reader->getPropertyAnnotation($property, self::IMAGE_RESIZE_FIELD_ANNOTATION);
            if ($uploadableField === null)
            {
                continue;
            }
            $uploadableField->handle($dir, $mapping->getFileName($obj));
        }
    }
}
