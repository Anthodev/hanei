<?php

namespace App\Document;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @UniqueEntity("username")
 * @MongoDB\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @MongoDB\Id(strategy="INCREMENT")
     * @Serializer\Expose
     * @var mixed
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @Serializer\Expose
     * @var mixed
     */
    protected $username;

    /**
     * @MongoDB\Field(type="string")
     * @Serializer\Expose
     * @var mixed
     */
    protected $email;

    /**
     * @MongoDB\Field(type="string")
     * @Serializer\Expose
     * @var mixed
     */
    protected $plainPassword;

    /**
     * @MongoDB\Field(type="string")
     * @Serializer\Expose
     * @var mixed
     */
    protected $password;

    /**
     * @MongoDB\ReferenceOne(targetDocument=App\Document\Role::class)
     * @Serializer\Expose
     * @var mixed
     */
    protected $role;

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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()->getCode()];
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
