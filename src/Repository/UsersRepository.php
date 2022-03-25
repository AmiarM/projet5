<?php

namespace App\Repository;

use App\Entity\Users;
use App\Libs\Security;
use App\Libs\Session;
use App\Uploader\Uploader;
use Doctrine\ORM\EntityRepository;
use Exception;

class UsersRepository extends EntityRepository
{
    /******************************************************** */
    public function findOneUser($user_id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u'])
            ->from('App\Entity\Users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user_id);

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /******************************************************************* */
    public function findAllUsers()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u'])
            ->from('App\Entity\Users', 'u')
            ->orderBy('u.created_at', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    /******************************************************************** */

    public function destroy(int $user_id)
    {
        $user = $this->findOneUser($user_id);

        if (!$user) {
            http_response_code('404');
            $_SESSION['error'] = "le user recherché n'existe pas";
            header("location: admin/users");
            exit();
        }
        $this->_em->remove($user);
        return $this->_em->flush();
    }
    /******************************************************************* */
    public function create()
    {
        $user = new Users();
        if (isset($_POST) && isset($_FILES)) {
            $uploader = new Uploader();
            $image = $uploader->upload($_FILES);
            $user->setImage($image);
            $token = md5(uniqid());
            var_dump($token);
            $passwordCrypt = password_hash(Security::secureHtml($_POST['password']), PASSWORD_DEFAULT);
            $user->setFirstName(Security::secureHtml($_POST['firstname']))
                ->setLastName(Security::secureHtml($_POST['lastname']))
                ->setUsername(Security::secureHtml($_POST['username']))
                ->setEmail(Security::secureHtml($_POST['email']))
                ->setToken($token)
                ->setPassword($passwordCrypt);
            $this->_em->persist($user);
            $this->_em->flush();
        }
    }
    /******************************************************************* */
    public function update(int $user_id)
    {
        $user = $this->findOneUser($user_id);
        if (!$user) {
            return new Exception("ce user n'existe pas");
        }
        if (isset($_POST['submit'])) {
            if ($_POST['activated'] == 'active') {
                $user->setActivated(1);
            } else {
                $user->setActivated(0);
            }

            if ($_POST['admin'] == 'admin') {
                $user->setIsAdmin(1);
            } else {
                $user->setIsAdmin(0);
            }
        }
        $this->_em->flush();
    }
    /***************************************************************** */
    public function findByEmail($email)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u'])
            ->from('App\Entity\Users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);
        $query = $queryBuilder->getQuery();
        $user = $query->getResult();
        return $user;
    }
    /******************************************************************* */
    public function getUserPassword($email)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u.password'])
            ->from('App\Entity\Users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $query = $queryBuilder->getQuery();
        $passwordDB = $query->getOneOrNullResult();
        if (isset($passwordDB['password'])) {
            return $passwordDB['password'];
        }
    }
    /****************************************************************** */
    public function isCombinaisonValide($email, $password)
    {
        $passwordDB = $this->getUserPassword($email);
        if (!$passwordDB) {
            $session = new Session();
            $session->set('errors', 'mot de passe incorrect');
            exit();
        }
        return password_verify($password, $passwordDB);
    }
    /****************************************************************** */
    public function isActivated($email)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u.activated'])
            ->from('App\Entity\Users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $query = $queryBuilder->getQuery();
        $user = $query->getOneOrNullResult();
        return  $user['activated'];
    }
    /***************************************************************** */
    public function getUserInformations($email)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(['u'])
            ->from('App\Entity\Users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
    /***************************************************************** */
    public function existsEmail($email)
    {
        return $this->findByEmail($email);
    }
    /******************************************************************** */
    public function changePassword()
    {
        $session = new Session;
        $user = $this->findByEmail($session->get('profil'));
        if (!$user) {
            $session = new Session;
            $session->set("errors", "User Innexistant");
            header('location: /register');
        }
        return $user;
    }
    /******************************************************************** */
    public function password()
    {
        $user = $this->findByEmail($_POST['email']);
        if (!$user) {
            $session = new Session;
            throw new Exception("user innexistant");
        }
        return $user;
    }
    /************************************************************************** */
    public function profile()
    {
        $session = new Session;
        $user = $this->findByEmail($session->get('profil'));
        if (!$user) {
            $session->set('errors', 'le user d\'id n\'existe pas');
        }
        if (isset($_POST['submit'])) {
            $token = md5(uniqid());
            if (isset($_FILES['pictures']) && !empty($_FILES['pictures']['size'])) {
                $uploader = new Uploader();
                $image = $uploader->upload($_FILES);
                $user[0]->setImage($image);
            }
            if ($_POST['password']) {
                $passwordCrypt = password_hash(Security::secureHtml($_POST['password']), PASSWORD_DEFAULT);
                $user[0]->setPassword($passwordCrypt);
            }
            $user[0]->setFirstName(Security::secureHtml($_POST['firstname']))
                ->setLastName(Security::secureHtml($_POST['lastname']))
                ->setUsername(Security::secureHtml($_POST['username']))
                ->setEmail(Security::secureHtml($_POST['email']))
                ->setToken($token);
            $this->_em->flush();
        }
    }
    /************************************************************************************* */
}
