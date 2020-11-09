<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks()
 */
class Role
{
    /**
     * @MongoDB\Id(strategy="INCREMENT")
     * @var mixed
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @var mixed
     */
    private $name;

    /**
     * @MongoDB\Field(type="string")
     * @var mixed
     */
    private $code;

    /**
     * @MongoDB\ReferenceMany(targetDocument=App\Document\User::class)
     * @var mixed
     */
    private $users;

    /**
     * @MongoDB\Field(type="date")
     * @var mixed
     */
    private $createdAt;

    /**
     * @MongoDB\Field(type="date")
     * @var mixed
     */
    private $updatedAt;

    /**
     * Get the value of id
     *
     * @return  mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     *
     * @return  mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  mixed  $name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code
     *
     * @return  mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @param  mixed  $code
     *
     * @return  self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of users
     *
     * @return  mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set the value of users
     *
     * @param  mixed  $users
     *
     * @return  self
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return  mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param  mixed  $createdAt
     *
     * @return  self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return  mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param  mixed  $updatedAt
     *
     * @return  self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
