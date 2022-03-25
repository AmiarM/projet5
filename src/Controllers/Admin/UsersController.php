<?php

namespace App\Controllers\Admin;

use App\Entity\Users;
use App\Libs\Security;
use App\Libs\Validator;
use App\Libs\AbstractController;
use Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class UsersController extends AbstractController
{
    protected $usersRepository;


    /********************************************************************* */
    public function index()
    {
        if ($this->isAdmin()) {
            $session = new Session();
            $users = $this->entityManager->getRepository(Users::class)->findAllUsers();
            return $this->twig->display('/admin/users/index.html.twig', compact('users'));
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /********************************************************************* */
    public function create()
    {
        if ($this->isAdmin()) {
            if (isset($_POST['submit'])) {
                $errors = $this->validate();
                if ($errors) {
                    $errors = $this->session->set('errors', $errors);
                    $this->redirect('/admin/users/create');
                }
                $email = Security::secureHtml($_POST['email']);
                if ($this->entityManager->getRepository(Users::class)->existsEmail($email)) {
                    $this->redirect('/login');
                } else {
                    $usersRepository = $this->entityManager->getRepository('App\Entity\Users');
                    $usersRepository->create();
                    $this->session->set("success", "User Ajouté avac succées");
                    //$this->session->set("profil", $email);
                    $user = $usersRepository->findByEmail($email);

                    if (!$user) {
                        throw new Exception("user innexistant");
                    }
                    //envoi du mail
                    $this->sendMail(
                        $user[0]->getEmail(),
                        "valider votre inscription",
                        "
                            pour valider votre compte veuillez utiliser l'url suivante:<br>
                            http://blogpro/register/confirm/" . $user[0]->getId() . "/" . $user[0]->getToken() . "
                        ",
                        "confirmer votre inscription",
                        "/login"
                    );
                    $this->redirect('/admin/users');
                }
            }
            $messages = $this->session->getFlashMessage("errors");
            return $this->twig->display('admin/users/create.html.twig', [
                'messages' => $messages,
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /********************************************************************* */
    public function update(int $user_id)
    {
        if ($this->isAdmin()) {
            $user = $this->entityManager->getRepository('App\Entity\Users')->findOneUser($user_id);
            if (!$user) {
                $this->session->set('errors', "le user recherché n'existe pas");
                $this->redirect('/admin/users');
                exit();
            }
            if (isset($_POST['submit']) && isset($_FILES)) {
                $usersRepository = $this->entityManager->getRepository('App\Entity\Users');
                $usersRepository->update($user_id);
                $this->session->set("success", "user modifié avac succées");
                $this->redirect('/admin/users');
            }
            $messages = $this->session->getFlashMessage("errors");
            return $this->twig->display('admin/users/update.html.twig', [
                'user' => $user,
                'messages' => $messages
            ]);
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /******************************************************************* */
    public function destroy(int $user_id)
    {


        if ($this->isAdmin()) {
            $tagsRepository = $this->entityManager->getRepository('App\Entity\Users');
            $tagsRepository->destroy($user_id);
            header("location: /admin/users");
        } else {
            echo "accèes interdit à la ressource";
        }
    }
    /************************************************************* */
    public function changePassword()
    {
        $usersRepository = $this->entityManager->getRepository("App\Entity\Users");
        $user = $usersRepository->changePassword();
        if ($user[0]->getIsAdmin() == 1) {
            $this->redirect('/admin/users');
        } else {
            $this->redirect("/profile");
        }
    }
    /************************************************************* */
    public function validate()
    {
        $validator = new Validator($_POST);
        $errors = $validator->validate([
            'firstname' => ['required', 'min:3'],
            'lastname' => ['required', 'min:3'],
            'username' => ['required', 'min:5'],
            'email' => ['required', 'min:10'],
            'password' => ['required', 'min:8'],
        ]);
        return $errors;
    }
    /************************************************************* */
}
