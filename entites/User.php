<?php

namespace entites;

class User
{
    private string $login;
    private string $pass;
    private string $email;
    private string $confirm;
    private string $descr;
    private string $data;
    private int $index;
    private string $admin;

    function __construct(array $data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = stripslashes(strip_tags(htmlspecialchars(trim($val))));
            }
        }
    }

    public function getAdmin(): ?string
    {
        return $this->admin;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getConfirm(): ?string
    {
        return $this->confirm;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getDesc(): ?string
    {
        return $this->descr;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function setPass($pass): void
    {
        $this->pass = $pass;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function setDesc($desc): void
    {
        $this->descr = $desc;
    }

    public function setAdmin($admin): void
    {
        $this->admin = $admin;
    }

    public function setIndex($index): void
    {
        $this->index = $index;
    }

}