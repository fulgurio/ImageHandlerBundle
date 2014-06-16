<?php

namespace Fulgurio\ImageHandlerBundle\Event;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Fulgurio\ImageHandlerBundle\Annotation\AnnotationDriver;

//use Vich\UploaderBundle\EventListener\DoctrineUploaderListener as EventSubscriber;

//use Vich\UploaderBundle\Adapter\AdapterInterface;
//use Vich\UploaderBundle\Handler\UploadHandler;
//use Vich\UploaderBundle\Metadata\MetadataReader;

class DoctrineUploaderListener implements EventSubscriber
{
    /**
     * Constructs a new instance of UploaderListener.
     *
     * @param AdapterInterface $adapter  The adapter.
     * @param MetadataReader   $metadata The metadata reader.
     * @param UploadHandler    $handler  The upload handler.
     */
    public function __construct(AnnotationDriver $driver)//AdapterInterface $adapter, MetadataReader $metadata, UploadHandler $handler)
    {
        $this->driver = $driver;
//        $this->adapter = $adapter;
//        $this->metadata = $metadata;
//        $this->handler = $handler;
    }

    /**
     * The events the listener is subscribed to.
     *
     * @return array The array of events.
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove',
        );
    }

    /**
     * Checks for file to upload.
     *
     * @param EventArgs $event The event.
     */
    public function prePersist(EventArgs $event)
    {
        echo __METHOD__, '<br />';die();
//        $object = $this->adapter->getObjectFromArgs($event);
//
//        if ($this->metadata->isUploadable($this->adapter->getClassName($object))) {
//            $this->handler->handleUpload($object);
//        }
//        parent::prePersist($event);
    }

    /**
     * Update the file and file name if necessary.
     *
     * @param EventArgs $event The event.
     */
    public function preUpdate(EventArgs $event)
    {
        $entity = $event->getEntity();
        $changes = $event->getEntityChangeSet();
        $data = $this->driver->loadMetadataForClass(new \ReflectionClass($entity));
        foreach ($data as $name => $handle) {
            if (isset($changes[$name])) {
                $handle->handle($entity, $name);
            }
        }
    }

    /**
     * Populates uploadable fields from filename properties.
     *
     * @param EventArgs $event The event.
     */
    public function postLoad(EventArgs $event)
    {
//        echo __METHOD__, '<br />';die();
//        parent::postLoad($event);
//        $object = $this->adapter->getObjectFromArgs($event);
//
//        if ($this->metadata->isUploadable($this->adapter->getClassName($object))) {
//            $this->handler->handleHydration($object);
//        }
    }

    /**
     * Removes the file if necessary.
     *
     * @param EventArgs $event The event.
     */
    public function postRemove(EventArgs $event)
    {
        echo __METHOD__, '<br />';die();
//        parent::postRemove($event);
//        $object = $this->adapter->getObjectFromArgs($event);
//
//        if ($this->metadata->isUploadable($this->adapter->getClassName($object))) {
//            $this->handler->handleDeletion($object);
//        }
    }
}
