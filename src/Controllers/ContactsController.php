<?php

namespace App\Controllers;

use App\Libs\Validator;
use App\Libs\AbstractController;

class ContactsController extends AbstractController
{
    public function create()
    {
        if (isset($_POST['submit'])) {
            $errors = $this->validate();
            if ($errors) {
                $errors = $this->session->set('errors', $errors);
                $this->redirect("/contact");
            }
            $subject = strip_tags($_POST['subject']);
            $message = strip_tags($_POST['message']);
            $contactRepositoty = $this->entityManager->getRepository('App\Entity\Contacts');
            $contactRepositoty->create();
            $this->session->set('success', 'message envoyé avec succès!');

            $this->sendMail(
                "mohamed.amiar@gmail.com",
                $subject,
                $message,
                "vous avez un message",
                "/contact"
            );
        }
        $success = $this->session->getFlashMessage('success');
        $errors = $this->session->getFlashMessage('errors');
        return $this->twig->display("/blog/contact.html.twig", [
            'success' => $success,
            'errors' => $errors
        ]);
    }

    public function validate()
    {
        $post = $_POST;
        $validator = new Validator($post);
        $errors = $validator->validate([
            'firstname' => ['required', 'min:3'],
            'lastname' => ['required', 'min:3'],
            'email' => ['required', 'min:10'],
            'subject' => ['required', 'min:2'],
            'message' => ['required', 'min:10'],
        ]);
        return $errors;
    }
}
