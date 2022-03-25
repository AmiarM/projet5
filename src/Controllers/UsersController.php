<?php

namespace App\Controllers;

use App\Entity\Users;
use App\Libs\Session;
use App\Libs\Security;
use App\Libs\Validator;
use App\Libs\AbstractController;
use Exception;

class UsersController extends AbstractController
{
    /******************************************************************** */
    public function login()
    {
        if ($this->session->get('profil')) {
            $this->redirect("/posts");
        } else {
            $errors = $this->validate();
            if ($errors) {
                $errors = $this->session->set('errors', $errors);
                $this->redirect("/login");
            }
            if (isset($_POST) && !empty($_POST)) {
                $email = Security::secureHtml($_POST['email']);
                $password = Security::secureHtml($_POST['password']);
                if ($this->entityManager->getRepository(Users::class)->isCombinaisonValide($email, $password)) {
                    if ($this->entityManager->getRepository(Users::class)->isActivated($email)) {
                        $this->session->set('profil', $email);
                        $user = $this->entityManager->getRepository(Users::class)->getUserInformations($this->session->get('profil'));
                        // if ($user) {
                        //     if (!empty($_POST["remember"])) {
                        //         setcookie("email", $email, time() + (10 * 365 * 24 * 60 * 60));
                        //         setcookie("password", $password, time() + (10 * 365 * 24 * 60 * 60));
                        //     } else {
                        //         if (isset($_COOKIE["email"])) {
                        //             setcookie("email", "");
                        //         }
                        //         if (isset($_COOKIE["password"])) {
                        //             setcookie("password", "");
                        //         }
                        //     }
                        // }
                        if ($user->getIsAdmin() == 1) {
                            $this->redirect('/admin/posts');
                        } else {
                            $this->redirect('/posts');
                        }
                    } else {
                        $this->session->set('errors', "Le compte avec l'email " . $_POST['email'] . "n'est pas activé");
                        $this->redirect("/login");
                    }
                }
            }
        }
        $messages = $this->session->getFlashMessage('errors');
        return $this->twig->display('/auth/login.html.twig', [
            'messages' => $messages
        ]);
    }
    /**************************************************************** */
    public function register()
    {
        if ($this->session->get('profil')  && $this->entityManager->getRepository(Users::class)->isActivated($this->session->get('profil')) == 0) {
            $this->redirect("/login");
        } else {
            $errors = $this->validateRegister();
            if ($errors) {
                $errors = $this->session->set('errors', $errors);
                $this->redirect("/register");
            }
            if (isset($_POST) && !empty($_POST)) {
                ;
                $email = Security::secureHtml($_POST['email']);
                if ($this->entityManager->getRepository(Users::class)->existsEmail($email)) {
                    $this->redirect('/login');
                } else {
                    $tagsRepository = $this->entityManager->getRepository('App\Entity\Users');
                    $tagsRepository->create();
                    $this->session->set('profil', $email);
                    $user = $this->entityManager->getRepository('App\Entity\Users')->findByEmail($this->session->get('profil'));
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
                }
            }
        }
        $messages = $this->session->getFlashMessage('errors');
        return $this->twig->display('/auth/register.html.twig', [
            "messages" => $messages
        ]);
    }

    /******************************************************************* */
    public function logout()
    {
        unset($_SESSION['profil']);
        $this->redirect('/');
    }
    /******************************************************************** */
    public function profile()
    {
        $session = new Session();
        $session->start();
        $user = $this->entityManager->getRepository(Users::class)->getUserInformations($this->session->get('profil'));
        if ($user->getIsAdmin() == 1) {
            return $this->twig->display("/admin/profile/profile.html.twig", [
                'user' => $user
            ]);
        } else {
            return $this->twig->display("/auth/profile.html.twig", [
                'user' => $user
            ]);
        }
        if (!$user) {
            $_SESSION['errors'] = 'l\'utilisateur n\'existe pas';
        }
        return $this->twig->display("/auth/profile.html.twig", [
            'user' => $user
        ]);
    }
    /****************************************************************************************** */
    public function changePassword()
    {
        if (isset($_POST['submit'])) {
            $usersRepository = $this->entityManager->getRepository("App\Entity\Users");
            $usersRepository->changePassword();
            $this->redirect("/profile");
        }
    }
    /****************************************************************************************** */
    public function validate()
    {
        $validator = new Validator($_POST);
        $errors = $validator->validate([
            'email' => ['required', 'min:3'],
            'password' => ['required', 'min:3']
        ]);
        return $errors;
    }
    /****************************************************************************************** */
    public function validateRegister()
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
    /******************************************************************************************* */
    public function password()
    {
        if (isset($_POST['submit'])) {
            $password = md5(uniqid());
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $usersRepository = $this->entityManager->getRepository('App\Entity\Users');
            $user = $usersRepository->password();
            $user[0]->setPassword($hashedPassword);
            $this->entityManager->flush();
            $this->sendMail(
                strip_tags($_POST['email']),
                "Votre nouveau mot de passe",
                "bonjour voici votre mot de passe:" . "  " . $password,
                "mot de passe oublié",
                "/login"
            );
        }
        return $this->twig->display("/auth/forgot_password.html.twig");
    }

    /****************************************************************************************** */
    public function registerValidation($user_id)
    {
        $usersRepository = $this->entityManager->getRepository("App\Entity\Users");
        $user = $usersRepository->findOneUser($user_id);
        if (!$user) {
            echo "user innéxistant";
        }
        $user->setActivated(1);
        $this->entityManager->flush();
        $this->redirect("/login");
    }
    public function changeProfile()
    {
        $usersRepository = $this->entityManager->getRepository("App\Entity\Users");
        $usersRepository->profile();
        $user = $usersRepository->findByEmail($this->session->get('profil'));

        if (!$user) {
            throw new Exception("ce user n'xiste pas");
        }
        if ($user[0]->getIsAdmin() == 1) {
            return $this->redirect("/admin/users");
        } else {
            return $this->redirect("/posts");
        }
    }
    /****************************************************************************** */
}
