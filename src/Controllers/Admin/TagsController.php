<?php

namespace App\Controllers\Admin;

use App\Entity\Tags;
use App\Libs\Validator;
use App\Libs\AbstractController;

class TagsController extends AbstractController
{
    /*************************************************************** */
    public function index()
    {
        if ($this->isAdmin()) {
            $tagsRepository = $this->entityManager->getRepository('App\Entity\Tags');
            $tags = $tagsRepository->findAllTags();
            return $this->twig->display('admin/tags/index.html.twig', compact('tags'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /*************************************************************** */
    public function create()
    {
        if ($this->isAdmin()) {
            if (isset($_POST['submit'])) {
                $errors = $this->validate();
                if ($errors) {
                    $errors = $this->session->set('errors', $errors);
                    $this->redirect('/admin/tags/create');
                }
                $tagsRepository = $this->entityManager->getRepository('App\Entity\Tags');
                $tagsRepository->create();
                $this->session->set("success", "Tag Ajouté avac succées");
                $this->redirect('/admin/tags');
            }
            $messages = $this->session->getFlashMessage("errors");
            return $this->twig->display('admin/tags/create.html.twig', [
                'messages' => $messages,
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /**************************************************************** */
    public function update(int $tag_id)
    {
        if ($this->isAdmin()) {
            $tag = $this->entityManager->getRepository('App\Entity\Tags')->findOneTag($tag_id);
            if (!$tag) {
                $this->session->set('errors', "le tag recherché n'existe pas");
                $this->redirect('/admin/tags');
                exit();
            }
            if (isset($_POST['submit'])) {
                $errors = $this->validate();
                if ($errors) {
                    $errors = $this->session->set('errors', $errors);
                    $this->redirect("/admin/tags/update/$tag_id");
                }
                $tagsRepository = $this->entityManager->getRepository('App\Entity\Tags');
                $tagsRepository->update($tag_id);
                $this->session->set("success", "Tag modifié  avac succées");
                $this->redirect('/admin/tags');
            }
            $messages = $this->session->getFlashMessage("errors");
            return $this->twig->display('admin/tags/update.html.twig', [
                'tag' => $tag,
                'messages' => $messages
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /**************************************************************** */
    public function destroy(int $tag_id)
    {


        if ($this->isAdmin()) {
            $tagsRepository = $this->entityManager->getRepository('App\Entity\Tags');
            $tagsRepository->destroy($tag_id);
            $this->redirect('/admin/tags');
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /*************************************************************** */
    public function validate()
    {
        if ($this->isAdmin()) {
            $validator = new Validator($_POST);
            $errors = $validator->validate([
                'name' => ['required', 'min:3']
            ]);
            return $errors;
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /*************************************************************** */
    public function tag($tag_id)
    {
        if ($this->isAdmin()) {
            $posts = $this->entityManager->getRepository(Tags::class)->getPostsByTag($tag_id);
            return $this->twig->display('admin/tags/tag.html.twig', compact('posts'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /*************************************************************** */
}
