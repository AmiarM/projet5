<?php

namespace App\Entity;

use DateTime;
use App\Libs\Model;
use App\Entity\Responses;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @ORM\Table(name="users")
 **/
class Users extends Model
{
    /** @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     **/
    private $id;
    /** @ORM\Column(type="string") **/
    private $lastName;
    /** @ORM\Column(type="string") **/
    private $firstName;
    /** @ORM\Column(type="string", length=180, unique=true) **/
    private $username;
    /** @ORM\Column(type="string", length=180, unique=true) **/
    private $email;
    /** @ORM\Column(type="string") **/
    private $password;
    /** @ORM\Column(type="integer") **/
    private $activated;
    /** @ORM\Column(type="string") **/
    private $isAdmin;
    /** @ORM\Column(type="datetime") **/
    private $created_at;
    /** @ORM\Column(type="string") **/
    private $token;


    /** @ORM\Column(type="string") **/
    private $image;

    /**
     * One user have Many comments. This is the owning side.
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="user")
     */
    private $comments;
    /**
     * One user have Many comments. This is the owning side.
     * @ORM\OneToMany(targetEntity="Responses", mappedBy="user")
     */
    private $responses;
    /**
     * One user have Many posts. This is the owning side.
     * @ORM\OneToMany(targetEntity="Posts", mappedBy="user")
     */
    private $posts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->activated = 0;
        $this->created_at = new DateTime();
        $this->isAdmin = 0;
    }





    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of activated
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set the value of activated
     *
     * @return  self
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of isAdmin
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set the value of isAdmin
     *
     * @return  self
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get one user have Many comments. This is the owning side.
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set one user have Many comments. This is the owning side.
     *
     * @return  self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get one user have Many posts. This is the owning side.
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set one user have Many posts. This is the owning side.
     *
     * @return  self
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . " " . $this->lastName;
    }
    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}
