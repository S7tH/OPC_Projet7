<?php

namespace AppBundle\Representation;

use JMS\Serializer\Annotation\Type;

class ProducesRepresentation extends AbstractRepresentation
{
    /**
     * @Type("array<AppBundle\Entity\Produces>")
     */
    public $data;
}
