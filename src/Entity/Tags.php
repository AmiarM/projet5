<?php

namespace App\Entity;

use DateTime;


use App\Libs\Model;
use App\Entity\Posts;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagsRepository")
 * @ORM\Table(name="tags")
 **/
class Tags extends Model
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    private $id;
    /** @ORM\Column(type="string") **/
    private $name;

    /** @ORM\Column(type="datetime") **/
    private $created_at;
    /**
     * Many tags have Many posts.
     * @ORM\ManyToMany(targetEntity="Posts", mappedBy="tags")
     */
    private $posts;

    public function addPost(Posts $post)
    {
        $this->posts[] = $post;
    }
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->created_at = new DateTime();
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
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Get many tags have Many posts.
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set many tags have Many posts.
     *
     * @return  self
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }
}
