<?php 

namespace App\Entity;

abstract class AbstractEntity
{
    protected ?int $id;

    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}

