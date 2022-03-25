<?php

namespace App\Repository;

use App\Entity\Tags;
use App\Entity\Posts;
use App\Libs\Session;
use App\Uploader\Uploader;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

class PostsRepository extends EntityRepository
{

    public function getCommentsByPost()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['c'])
            ->from('App\Entity\Comments', 'c');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************** */
    public function getActivatedPosts()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'])
            ->from('App\Entity\Posts', 'p')
            ->where('p.published =1');

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************** */
    public function findOnePost($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'])
            ->from('App\Entity\Posts', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /******************************************************************* */
    public function findAllPosts()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'])
            ->from('App\Entity\Posts', 'p')
            ->orderBy('p.created_at', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************************* */
    public function create()
    {
        $post = new posts();
        $uploader = new Uploader();
        $image = $uploader->upload($_FILES);
        $post->setImage($image)
            ->setTitle(strip_tags($_POST['title']))
            ->setChapo(strip_tags($_POST['chapo']))
            ->setContent(strip_tags($_POST['content']));
        $tagRepository = $this->_em->getRepository(Tags::class);
        foreach ($_POST['tags'] as $tag_id) {
            $tag = $tagRepository->findOneTag($tag_id);
            $post->addTag($tag);
        }
        $session = new Session();
        $userRepository = $this->_em->getRepository('App\Entity\Users');
        $user = $userRepository->findByEmail($session->get('profil'));
        $post->setUser($user[0]);
        $this->_em->persist($post);
        $this->_em->flush();
    }
    /******************************************************************* */
    public function update(int $id)
    {
        $post = $this->findOnePost($id);
        if (!$post) {
            return new Exception("le post d'id $id n'existe pas");
        }
        if (isset($_POST['submit'])) {
            if (isset($_FILES['pictures']) && !empty($_FILES['pictures']['size'])) {
                $uploader = new Uploader();
                $image = $uploader->upload($_FILES);
                $post->setImage($image);
            }
            $post->setTitle(strip_tags($_POST['title']))
                ->setChapo(strip_tags($_POST['chapo']))
                ->setContent(strip_tags($_POST['content']))
                ->setUpdatedAt(new DateTime());

            $tagRepository = $this->_em->getRepository(Tags::class);
            foreach ($_POST['tags'] as $tag_id) {
                $tag = $tagRepository->findOneTag($tag_id);
                $post->addTag($tag);
            }


            $this->_em->flush();
        }
    }
    /******************************************************************** */

    public function destroy(int $id)
    {
        $post = $this->findOnePost($id);
        if (!$post) {
            http_response_code('404');
            $_SESSION['error'] = "le post recherché n'existe pas";
            header("location: admin/posts");
            exit();
        }
        $this->_em->remove($post);
        return $this->_em->flush();
    }

    /************************************************************* */
    public function getRecentsPosts()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'])
            ->from('App\Entity\Posts', 'p')
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(3);
        $query = $queryBuilder->getQuery();
        $posts = $query->getResult();
        return  $posts;
    }
    /************************************************************* */
    public function getArchives()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'])
            ->from('App\Entity\Posts', 'p')
            ->orderBy('p.created_at', 'ASC')
            ->setMaxResults(3);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /************************************************************ */
    public function published($id)
    {
        $post = $this->findOnePost($id);
        if (!$post) {
            $session = new Session;
            $session->set("errors", "le post d'id $id n'existe pas");
        }
        if (isset($_POST['submit'])) {
            if ($_POST['status'] == "published") {
                $post->setPublished(1);
            } else {
                $post->setPublished(0);
            }
        }
        $this->_em->flush();
    }
    /******************************************************************** */
    public function getPostByComment($comment_id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'], ['c'])
            ->from('App\Entity\Posts', 'p')
            ->join('p.comments', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $comment_id);
        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /********************************************************************* */
}
