<?php

namespace App\Entity;

class Tweet
{
    protected $id;

    protected $itemid;

    protected $raw;

    protected $plain;

    protected $timestamp;

    protected $hidden = false;

    protected $owner;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setItemid(string $itemid): self
    {
        $this->itemid = $itemid;

        return $this;
    }

    public function getItemid(): ?string
    {
        return $this->itemid;
    }

    public function setRaw(string $raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function setPlain(string $plain): self
    {
        $this->plain = $plain;

        return $this;
    }

    public function getPlain(): ?string
    {
        return $this->plain;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }
}
