<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, EquatableInterface, \Serializable
{
    protected $id;

    protected $name;

    protected $username;

    protected $password;

    protected $email;

    protected $twitterid;

    protected $roles;


    public function __construct()
    {
        $this->roles = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setTwitterid(string $twitterid): self
    {
        $this->twitterid = $twitterid;

        return $this;
    }

    public function getTwitterid(): ?string
    {
        return $this->twitterid;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        $role = strtoupper($role);
        $roles = $this->getRoles();

        if (empty($roles)) {
            $this->roles[] = $role;
        } elseif (!in_array($role, $roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        $role = strtoupper($role);
        $roles = $this->getRoles();

        return in_array($role, $roles, true);
    }



    /*
     * Implements UserInterface
     */

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }



    /*
     * implements EquatableInterface
     */

    public function isEqualTo(UserInterface $user)
    {
    }



    /*
     * implements Serializable
     */

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->godmode,
            $this->active,
            $this->name,
            $this->identification,
            $this->username,
            $this->policy,
            $this->password,
            $this->email,
            $this->phone,
            // $this->identificationtype,
            // $this->usertype,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->godmode,
            $this->active,
            $this->name,
            $this->identification,
            $this->username,
            $this->policy,
            $this->password,
            $this->email,
            $this->phone,
            // $this->identificationtype,
            // $this->usertype,
        ) = unserialize($serialized);
    }
}
