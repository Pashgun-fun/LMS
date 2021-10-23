<?php

namespace models;


class Article
{
    private string $title;
    private string $text;
    private string $user;
    private string $date;

    function __construct(array $data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = stripslashes(strip_tags(htmlspecialchars(trim($val))));
            }
        }
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
