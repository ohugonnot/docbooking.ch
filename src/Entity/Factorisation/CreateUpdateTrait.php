<?php

namespace App\Entity\Factorisation;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait CreateUpdateTrait
{
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $create_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $updated_at;

    public function getCreateAt(): ?DateTime
    {
        return $this->create_at;
    }
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }
}