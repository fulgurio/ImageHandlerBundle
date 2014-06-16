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

interface ImageHandle
{
    /**
     * Picture handle
     *
     * @param objet $entity
     * @param string $property
     */
    public function handle($entity, $property);
}