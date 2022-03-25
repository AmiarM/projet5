<?php

namespace App\Controllers\Admin;

use App\Libs\AbstractController;
use App\Libs\Security;

class CommentsController extends AbstractController
{
    /*********************************************************************** */
    public function index()
    {
        if ($this->isAdmin()) {
            $comments = $this->entityManager->getRepository('App\Entity\Comments')->findAllComments();
            return $this->twig->display("/admin/comments/index.html.twig", compact('comments'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************* */
    public function edit($comment_id)
    {
        if ($this->isAdmin()) {
            $commentsRepository = $this->entityManager->getRepository('App\Entity\Comments');
            $comment = $commentsRepository->findOneComment($comment_id);
            return $this->twig->display("/admin/comments/edit.html.twig", [
                'comment' => $comment
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************** */
    public function update(int $comment_id)
    {
        if ($this->isAdmin()) {
            $commentsRepository = $this->entityManager->getRepository('App\Entity\Comments');
            $commentsRepository->update($comment_id);
            return $this->redirect("/admin/comments");
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************* */
    public function destroy(int $comment_id)
    {
        if ($this->isAdmin()) {
            $tagsRepository = $this->entityManager->getRepository('App\Entity\Comments');
            $tagsRepository->destroy($comment_id);
            return $this->redirect("/admin/comments");
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************** */
    public function create()
    {
        if ($this->isAdmin()) {
            if (isset($_POST['submit'])) {
                $commentsRepository = $this->entityManager->getRepository("App\Entity\Comments");
                $commentsRepository->create();
                $this->redirect("/posts/" . Security::secureHtml($_POST['id']));
            }
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************** */
}
