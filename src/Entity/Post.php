<?php

namespace App\Entity;

use DateTime;

class Post
{
    protected $id;

    protected $createdat;

    protected $title;

    protected $content;

    protected $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCreatedat(DateTime $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getCreatedat(): ?DateTime
    {
        return $this->createdat;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }
}
