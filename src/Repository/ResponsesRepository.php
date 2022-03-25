<?php

namespace App\Repository;

use Exception;
use App\Libs\Session;
use App\Libs\Security;
use App\Entity\Responses;
use Doctrine\ORM\EntityRepository;

class ResponsesRepository extends EntityRepository
{
    public function create($comment_id)
    {
        if (isset($_POST['submit'])) {
            $response = new Responses();
            $content = Security::secureHtml($_POST['content']);
            //user
            $userRepository = $this->_em->getRepository('App\Entity\Users');
            $session = new Session();
            $user = $userRepository->findByEmail($session->get('profil'));
            if (!$user) {
                $session->set('errors', "le user  n'existe pas");
            }
            $response->setUser($user[0]);
            $response->setContent($content);

            $commentRepository = $this->_em->getRepository("App\Entity\Comments");
            $comment = $commentRepository->findOneComment($comment_id);
            if (!$comment) {
                throw new Exception("commentaire innexistant");
            }
            $response->setComment($comment);
            $this->_em->persist($response);
            $this->_em->flush();
        }
    }
    /******************************************************************************* */
    public function getAllResponses($comment_id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['r'], ['c'])
            ->from('App\Entity\Responses', 'r')
            ->join('r.comment', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $comment_id);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /**************************************************************************** */
    public function findAllResponses()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['r'], ['c'])
            ->from('App\Entity\Responses', 'r');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    /******************************************************** */
    public function findOneResponse($response_id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['r'])
            ->from('App\Entity\Responses', 'r')
            ->where('r.id = :id')
            ->setParameter('id', $response_id);

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /*********************************************************** */
    public function update(int $response_id)
    {
        $response = $this->findOneResponse($response_id);
        if (!$response) {
            $session = new Session();
            $session->set('errors', "la response d'id , $response_id n'esiste pas ");
        }
        if (isset($_POST['submit'])) {
            if ($_POST["disabled"] == "on") {
                $response->setDisabled(0);
            } else {
                $response->setDisabled(1);
            }
        }
        return $this->_em->flush();
    }
    /***************************************************************** */
    public function destroy(int $response_id)
    {
        $response = $this->findOneResponse($response_id);
        if (!$response) {
            $session = new Session();
            $session->set('errors', "la response recherché n'existe pas");
            header("location: admin/responses");
        }
        $this->_em->remove($response);
        return $this->_em->flush();
    }
    /*********************************************************************** */
}
