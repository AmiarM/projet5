<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Libs\Session;
use Doctrine\ORM\EntityRepository;

class commentsRepository extends EntityRepository
{
    /******************************************************** */
    public function findOneComment($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['c'])
            ->from('App\Entity\Comments', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /******************************************************************** */
    public function findAllComments()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['c'])
            ->from('App\Entity\Comments', 'c')
            ->orderBy('c.created_at', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************************* */
    public function create()
    {
        $comment = new Comments();
        //user
        $userRepository = $this->_em->getRepository('App\Entity\Users');
        $session = new Session();
        $user = $userRepository->findByEmail($session->get('profil'));
        if (!$user) {
            $session->set('errors', "le user  n'existe pas");
        }
        $comment->setUser($user[0]);
        $comment->setContent(strip_tags($_POST['comment']));
        //post
        $postRepository = $this->_em->getRepository('App\Entity\Posts');
        $post = $postRepository->findOnePost(strip_tags($_POST['id']));
        if (!$post) {
            $session->set('errors', "le post n'existe pas");
        }
        $comment->setPost($post);
        $this->_em->persist($comment);
        return $this->_em->flush();
    }

    /******************************************************************* */
    public function update(int $id)
    {
        $comment = $this->findOneComment($id);
        if (!$comment) {
            $session = new Session();
            $session->set('errors', 'le comment d\'id ,\'esiste pas ');
        }
        if (isset($_POST['submit'])) {
            if ($_POST["disabled"] == "on") {
                $comment->setDisabled(0);
            } else {
                $comment->setDisabled(1);
            }
        }
        return $this->_em->flush();
    }
    /******************************************************************** */

    public function destroy(int $id)
    {
        $comment = $this->findOneComment($id);
        if (!$comment) {
            $session = new Session();
            $session->set('errors', "le comment recherché n'existe pas");
            header("location: admin/comments");
        }
        $this->_em->remove($comment);
        return $this->_em->flush();
    }
    /******************************************************************** */

    public function getOneEnabledComment()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['c'])
            ->from('App\Entity\Comments', 'c')
            ->where('c.disabled = 0');

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /*************************************************************************** */
}
