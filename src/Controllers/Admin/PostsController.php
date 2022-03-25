<?php

namespace App\Controllers\Admin;

use App\Entity\Posts;
use App\Libs\Validator;
use App\Libs\AbstractController;
use Exception;

class PostsController extends AbstractController
{
    /*********************************************************************** */
    public function index()
    {
        if ($this->isAdmin()) {
            $posts = $this->entityManager->getRepository(Posts::class)->findAllPosts();
            return $this->twig->display("admin/posts/index.html.twig", compact('posts'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }

    /********************************************************************** */
    public function create()
    {
        if ($this->isAdmin()) {
            if (isset($_POST['submit'])) {
                $errors = $this->validate();
                if ($errors) {
                    $errors = $this->session->set('errors', $errors);
                    $this->redirect('/admin/posts/create');
                }
                $postsRepository = $this->entityManager->getRepository('App\Entity\Posts');
                $postsRepository->create();
                $this->redirect('/admin/posts');
            }
            $tags = $this->entityManager->getRepository('App\Entity\Tags')->findAllTags();
            $messages = $this->session->getFlashMessage('errors');
            return $this->twig->display("admin/posts/create.html.twig", [
                'tags' => $tags,
                'messages' => $messages
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }

    /********************************************************************** */
    public function show(int $post_id)
    {
        if ($this->isAdmin()) {
            $postsRepository = $this->entityManager->getRepository('App\Entity\Posts');
            $post = $postsRepository->findOnePost($post_id);
            if (!$post) {
                $this->session->set("errors", "le post d'id $post_id n'éxiste pas");
                exit();
            }
            return $this->twig->display('admin/posts/show.html.twig', compact('post'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /********************************************************************* */
    public function update(int $post_id)
    {
        if ($this->isAdmin()) {
            $post = $this->entityManager->getRepository('App\Entity\Posts')->findOnePost($post_id);
            if (!$post) {
                $this->session->set('errors', "le post recherché n'existe pas");
                $this->redirect('/admin/posts');
                exit();
            }
            if (isset($_POST['submit'])) {
                $errors = $this->validate();
                if ($errors) {
                    $errors = $this->session->set('errors', $errors);
                    $this->redirect("/admin/posts/update/$post_id");
                }
                $postRepository = $this->entityManager->getRepository('App\Entity\Posts');
                $postRepository->update($post_id);
                $this->session->set('success', "post modifié avec succès!");
                $this->redirect('/admin/posts');
            }
            $tags = $this->entityManager->getRepository("App\Entity\Tags")->findAllTags();
            $messages = $this->session->getFlashMessage('errors');
            return $this->twig->display("admin/posts/update.html.twig", [
                'post' => $post,
                'messages' => $messages,
                'tags' => $tags
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /********************************************************************* */
    public function destroy(int $post_id)
    {


        if ($this->isAdmin()) {
            $postRepository = $this->entityManager->getRepository(Posts::class);
            $post = $postRepository->findOnePost($post_id);
            if (!$post) {
                return "le post d'id $post_id n'éxiste pas";
                exit();
            }
            $this->entityManager->remove($post);
            $this->entityManager->flush();
            header("location: /admin/posts");
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /********************************************************************* */
    public function validate()
    {
        if ($this->isAdmin()) {
            $validator = new Validator($_POST);
            $errors = $validator->validate([
                'title' => ['required', 'min:3'],
                'chapo' => ['required', 'min:3'],
                'content' => ['required', 'min:5'],
            ]);
            return $errors;
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /******************************************************************** */
    public function published($post_id)
    {
        if ($this->isAdmin()) {
            $postRepository = $this->entityManager->getRepository("App\Entity\Posts");
            $postRepository->published($post_id);
            return $this->redirect('/admin/posts');
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /******************************************************************** */
    public function details($post_id)
    {
        $post = $this->entityManager->getRepository("App\Entity\Posts")->findOnePost($post_id);
        if (!$post) {
            throw new Exception("le post d'id $post_id n'existe pas");
        }
        return $this->twig->display("admin/posts/details.html.twig", compact('post'));
    }
    /******************************************************************* */
}
