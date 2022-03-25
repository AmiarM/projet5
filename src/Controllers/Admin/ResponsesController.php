<?php

namespace App\Controllers\Admin;

use App\Libs\AbstractController;
use App\Libs\Security;

class ResponsesController extends AbstractController
{
    /*********************************************************************** */
    public function index()
    {
        if ($this->isAdmin()) {
            $responses = $this->entityManager->getRepository('App\Entity\Responses')->findAllResponses();
            return $this->twig->display("/admin/responses/index.html.twig", compact('responses'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************* */
    public function edit($response_id)
    {
        if ($this->isAdmin()) {
            $responsesRepository = $this->entityManager->getRepository('App\Entity\Responses');
            $response = $responsesRepository->findOneResponse($response_id);
            return $this->twig->display("/admin/responses/edit.html.twig", [
                'response' => $response
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************** */
    public function update(int $response_id)
    {
        if ($this->isAdmin()) {
            $responsesRepository = $this->entityManager->getRepository('App\Entity\Responses');
            $responsesRepository->update($response_id);
            return $this->redirect("/admin/responses");
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************************* */
    public function destroy(int $response_id)
    {
        if ($this->isAdmin()) {
            $responsesRepository = $this->entityManager->getRepository('App\Entity\Responses');
            $responsesRepository->destroy($response_id);
            return $this->redirect("/admin/responses");
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
