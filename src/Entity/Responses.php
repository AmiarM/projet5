<?php

namespace App\Entity;

use App\Libs\Model;
use App\Entity\Comments;
use App\Entity\Users;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponsesRepository")
 * @ORM\Table(name="responses")
 **/
class Responses extends Model
{
  /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    private $id;
  /** @ORM\Column(type="text") **/
    private $content;
  /** @ORM\Column(type="datetime") **/
    private $created_at;
  /** @ORM\Column(type="boolean") **/
    private $disabled;
  /**
   * Many Response have one user. This is the owning side.
   * @ORM\ManyToOne(targetEntity="Users", inversedBy="responses")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
    private $user;

  /**
   * Many Response have one Comment. This is the owning side.
   * @ORM\ManyToOne(targetEntity="Comments", inversedBy="responses")
   * @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
   */
    private $comment;


    public function __construct()
    {
        $this->disabled = 1;
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
   * Get the value of disabled
   */
    public function getDisabled()
    {
        return $this->disabled;
    }

  /**
   * Set the value of disabled
   *
   * @return  self
   */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

  /**
   * Get many Comment have one user. This is the owning side.
   */
    public function getUser()
    {
        return $this->user;
    }

  /**
   * Set many Comment have one user. This is the owning side.
   *
   * @return  self
   */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getExcerpt(): string
    {
        return substr($this->content, 0, 200) . '...';
    }

  /**
   * Get many Response have one Comment. This is the owning side.
   */
    public function getComment()
    {
        return $this->Comment;
    }

  /**
   * Set many Response have one Comment. This is the owning side.
   *
   * @return  self
   */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
}
