<?php

namespace AppBundle\Representation;

use JMS\Serializer\Annotation\Type;

class ProductRepresentation extends AbstractRepresentation
{
    /**
     * @Type("array<AppBundle\Entity\Product>")
     */
    public $data;
}