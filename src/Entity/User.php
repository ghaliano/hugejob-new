<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use phpDocumentor\Reflection\Types\Boolean;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface, Serializable
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     * @Assert\NotBlank
     * @Assert\Email(message="email invalide")
     * @ORM\Column(name="email", type="string", unique=true, length=255, nullable=true)
     */

    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=true)
     */
    private $lastname;

    /**
     * @var string|null
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    /**
     * @Assert\NotBlank
     */
    private $plainPassword;


    /**
     * @var string|null
     * @Assert\NotBlank
     * @ORM\Column(name="username", type="string", unique=true, length=50)
     */
    private $username;
    /**
     * @var string|null
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     * */
    private $token;

    /**
     * @var Boolean|null
     * @ORM\Column(name="enable", type="boolean")
     * */
    private $enable = false;

    /**
     * @var string|null
     * @ORM\Column(name="roles", type="string", length=255, nullable=true)
     *
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="user", cascade={"persist"})
     */
    private $companies ;

    public function __construct()
    {
        $this->companies = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function addCompany(Company $company)
    {
        $company->setUser($this);
        $this->companies[] = $company;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param mixed $companies
     */
    public function setCompanies($companies): void
    {
        $this->companies = $companies;
    }



    public function getRoles()
    {
        return json_decode($this->roles);
    }

    public function setRoles($roles)
    {
         $this->roles  = json_encode($roles) ;
    }

    public function addRole($role){
        $roles = $this->getRoles();
        $roles[] = $role;
        $this->setRoles($roles);
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->password,
            $this->username
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->password,
            $this->username
            ) = unserialize($serialized);
    }

    public function __toString()
    {
        return (string)$this->firstname . " " . $this->lastname;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
    public function getEnable()
    {
        return $this->enable;
    }

    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    public function getSalt(){

    }
}
