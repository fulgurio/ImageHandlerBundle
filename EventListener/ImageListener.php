<?php
/*
 * This file is part of the FulgurioImageHandlerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\ImageHandlerBundle\EventListener;

use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * Description of ImageListener
 *
 * @author guerar_v
 */
class ImageListener implements EventSubscriberInterface
{
    /**
     * Annotation reader object
     * @var Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * Property mapping object
     * @var Vich\UploaderBundle\Mapping\PropertyMappingFactory
     */
    private $factory;

    /**
     * Container object
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;


    const IMAGE_RESIZE_FIELD_ANNOTATION   = 'Fulgurio\ImageHandlerBundle\Annotation\ImageHandle';


    /**
     * Constructor
     *
     * @param \Doctrine\Common\Annotations\Reader $reader
     * @param \Vich\UploaderBundle\Mapping\PropertyMappingFactory $factory
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    function __construct(AnnotationReader $reader, PropertyMappingFactory $factory, ContainerInterface $container)
    {
        $this->container = $container;
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
            $config = $this->container->getParameter('fulgurio_image_handler.mappings');
            $mappingName = $uploadableField->getMappingName() != '' ? $uploadableField->getMappingName() : $mapping->getMappingName();
            if ($mappingName != '' && isset($config[$mappingName]))
            {
                if (isset($config[$mappingName]['width']))
                {
                    $uploadableField->setWidth($config[$mappingName]['width']);
                }
                if (isset($config[$mappingName]['height']))
                {
                    $uploadableField->setHeight($config[$mappingName]['height']);
                }
                if (isset($config[$mappingName]['action']))
                {
                    $uploadableField->setAction($config[$mappingName]['action']);
                }
            }
            $uploadableField->handle($dir, $mapping->getFileName($obj));
        }
    }
}
