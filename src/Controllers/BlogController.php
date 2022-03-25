<?php

namespace App\Controllers;

use App\Entity\Posts;
use App\Entity\Tags;
use App\Libs\AbstractController;
use App\Libs\Security;
use Exception;

class BlogController extends AbstractController
{
    /****************************************************** */
    public function welcome()
    {
        return $this->twig->display('/blog/welcome.html.twig');
    }
    /****************************************************** */
    public function index()
    {
        $posts = $this->entityManager->getRepository(Posts::class)->getActivatedPosts();
        return $this->twig->display('/blog/index.html.twig', compact('posts'));
    }
    /****************************************************** */
    public function show($id)
    {
        $post = $this->entityManager->getRepository(Posts::class)->findOnePost($id);
        if (!$post) {
            throw new Exception("le post d'id $id n'existe pas");
        }
        $recentsPosts = $this->entityManager->getRepository(Posts::class)->getRecentsPosts();
        $tags = $this->entityManager->getRepository(Tags::class)->findAllTags();
        $archives = $this->entityManager->getRepository(Posts::class)->getArchives();
        return $this->twig->display('blog/show.html.twig', compact('post', 'recentsPosts', 'archives', 'tags'));
    }
    /****************************************************** */
    public function tag($id)
    {
        $posts = $this->entityManager->getRepository(Tags::class)->getPostsByTag($id);
        return $this->twig->display('blog/tag.html.twig', compact('posts'));
    }
    /****************************************************** */
    public function destroy($comment_id)
    {
        $commentRepository = $this->entityManager->getRepository("App\Entity\Comments");
        $comment = $commentRepository->findOneComment($comment_id);
        if (!$comment) {
            throw new Exception("commentaire innexistant");
        }
        $post = $comment->getPost();
        if (!$post) {
            throw new Exception("post innexistant");
        }
        $commentRepository->destroy($comment_id);
        return $this->redirect("/posts/" . $post->getId());
    }
    /***************************************************** */
    public function reply($comment_id)
    {
        $commentRepository = $this->entityManager->getRepository("App\Entity\Comments");
        $comment = $commentRepository->findOneComment($comment_id);
        if (!$comment) {
            throw new Exception("commentaire innexistant");
        }
        return $this->twig->display("blog/reply.html.twig", compact('comment'));
    }
    /****************************************************** */
    public function update($comment_id)
    {
        $commentRepository = $this->entityManager->getRepository("App\Entity\Comments");
        $comment = $commentRepository->findOneComment($comment_id);
        if (!$comment) {
            throw new Exception("commentaire innexistant");
        }
        $post = $comment->getPost();
        if (!$post) {
            throw new Exception("post innexistant");
        }
        if (isset($_POST['submit'])) {
            $content = Security::secureHtml($_POST['content']);
            $comment->setContent($content);
            if ($comment->getDisabled == 0) {
                $comment->setDisabled(1);
            }
            $this->entityManager->flush();
        }
        return $this->redirect("/posts/" . $post->getId());
    }
    /***************************************************** */
    public function response($comment_id)
    {
        $commentRepository = $this->entityManager->getRepository("App\Entity\Comments");
        $comment = $commentRepository->findOneComment($comment_id);
        if (!$comment) {
            throw new Exception("commentaire innexistant");
        }
        $user = $this->entityManager->getRepository("App\Entity\Users")->findByEmail($this->session->get('profil'));
        if (!$user) {
            throw new Exception("user innexistant");
        }
        $responses = $this->entityManager->getRepository("App\Entity\Responses")->getAllResponses($comment_id);
        return $this->twig->display("blog/response.html.twig", [
            'user' => $user[0],
            'comment' => $comment,
            'responses' => $responses
        ]);
    }
    /**************************************************************** */
    public function SendResponse($comment_id)
    {
        $responsesRepository = $this->entityManager->getRepository("App\Entity\Responses");
        $responsesRepository->create($comment_id);
        $id = Security::secureHtml($_POST['id']);
        $post = $this->entityManager->getRepository("App\Entity\Posts")->getPostByComment($id);

        $this->redirect("/posts/" . $post->getId());
    }
    /*********************************************************************** */
}
