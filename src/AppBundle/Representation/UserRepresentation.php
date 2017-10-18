<?php

namespace AppBundle\Representation;

use JMS\Serializer\Annotation\Type;

class UserRepresentation extends AbstractRepresentation
{
    /**
     * @Type("array<AppBundle\Entity\User>")
     */
    public $data;
}
