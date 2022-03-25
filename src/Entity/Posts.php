<?php

namespace App\Entity;

use App\Libs\Model;
use App\Entity\Tags;
use App\Entity\Users;
use App\Entity\Comments;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostsRepository")
 * @ORM\Table(name="posts")
 **/
class Posts extends Model
{

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    private $id;
    /** @ORM\Column(type="string") **/
    private $title;
    /** @ORM\Column(type="string") **/
    private $chapo;
    /** @ORM\Column(type="datetime") **/
    private $created_at;
    /** @ORM\Column(type="datetime",nullable=true) **/
    private $updated_at;
    /** @ORM\Column(type="text") **/
    private $content;
    /** @ORM\Column(type="boolean") **/
    private $published;
    /** @ORM\Column(type="string") **/
    private $image;
    /**
     * Many posts have Many tags.
     * @ORM\ManyToMany(targetEntity="Tags", inversedBy="posts")
     * @ORM\JoinTable(name="posts_tags")
     */
    private $tags;



    /**
     * Many post has one user. This is the inverse side.
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="posts")
     */
    private $user;

    /**
     * One post have Many comments. This is the owning side.
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="post")
     */
    private $comments;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->created_at = new DateTime();
        $this->published = 0;
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
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of chapo
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo
     *
     * @return  self
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

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
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of published
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the value of published
     *
     * @return  self
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection|Tags[]|null
     */
    public function getTags()
    {
        return  $this->tags;
    }

    /**
     * Set many posts have Many tags.
     *
     * @return  self
     */


    public function addTag($tag): self
    {
        $found = false;
        foreach ($this->tags as $value) {
            if ($value->getId() == $tag->getId()) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->tags[] = $tag;
        }

        //var_dump($this->tags);
        return $this;
    }



    /**
     * Get many post has one user. This is the inverse side.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set many post has one user. This is the inverse side.
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get one post have Many comments. This is the owning side.
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set one post have Many comments. This is the owning side.
     *
     * @return  self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function getExcerpt(): string
    {
        return substr($this->content, 0, 50) . '...';
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
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Set many posts have Many tags.
     *
     * @return  self
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
