<?php

namespace App\Repository;

use App\Entity\Tags;
use Doctrine\ORM\EntityRepository;

class TagsRepository extends EntityRepository
{
    /************************************************ */
    public function findOneTag($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['t'])
            ->from('App\Entity\Tags', 't')
            ->where('t.id = :id')
            ->setParameter('id', $id);
        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /************************************************* */
    public function findAllTags()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['t'])
            ->from('App\Entity\Tags', 't')
            ->orderBy('t.created_at', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************************** */

    public function destroy(int $id)
    {
        $tag = $this->findOneTag($id);
        if (!$tag) {
            http_response_code('404');
            $_SESSION['error'] = "le tag recherché n'existe pas";
            header("location: /tags");
            exit();
        }
        $this->_em->remove($tag);
        return $this->_em->flush();
    }
    /******************************************************************** */
    public function create()
    {
        $tag = new Tags();
        $tag->hydrate($_POST);
        $tag->setName(strip_tags($tag->getName()));
        $this->_em->persist($tag);
        return $this->_em->flush();
    }
    /******************************************************************* */
    public function update(int $id)
    {
        $tag = $this->findOneTag($id);
        if (!$tag) {
            http_response_code('404');
            $_SESSION['error'] = "le poste recherché n'existe pas";
            header("location: /posts");
            exit();
        }
        if (isset($_POST['submit'])) {
            $tag->hydrate($_POST);
            $tag->setName(strip_tags($tag->getName()));
        }
        return $this->_em->flush();
    }
    /******************************************************************** */
    public function getPostsByTag($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['p'], ['t'])
            ->from('App\Entity\Posts', 'p')
            ->join('p.tags', 't')
            ->where('t.id = :id')
            ->setParameter('id', $id);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /************************************************************************/
}
