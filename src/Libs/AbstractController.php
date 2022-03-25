<?php

namespace App\Libs;

use App\Libs\Twig;
use App\Entity\Users;
use PHPMailer\PHPMailer\PHPMailer;


require ROOT . '/src/PHPMailer/src/Exception.php';
require ROOT . '/src/PHPMailer/src/PHPMailer.php';
require ROOT . '/src/PHPMailer/src/SMTP.php';

abstract class AbstractController
{
    /**************************************************************** */
    protected $twig;
    protected $entityManager;
    protected $usersRepository;
    protected $session;

    /**************************************************************** */
    public function __construct()
    {
        $this->twig = Twig::getTwig();
        require dirname(dirname(__DIR__)) . '/bootstrap.php';
        $this->entityManager = $entityManager;
        $this->session = new Session();
        $this->session->start();
        $this->twig->addGlobal('session', $this->entityManager->getRepository(Users::class)->getUserInformations($this->session->get('profil')));
    }
    /**************************************************************** */
    protected function isAdmin()
    {
        if ($this->session->get('profil')) {
            $user = $this->entityManager->getRepository("App\Entity\Users")->findByEmail($this->session->get('profil'));
            if (!$user) {
                $this->session->set("profil", "Aucun utilisateur connecté");
            }
            if ($user[0]->getIsAdmin() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return $this->redirect("/login");
        }
    }
    /**************************************************************** */
    static function redirect($page)
    {
        header("Location: $page");
        exit();
    }
    /*************************************************************** */
    public function sendMail($destinataire, $subject, $messages, $altBody, $redirect)
    {
        //------smtp settings
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "localhost";
        $mail->Port = 1025;
        $mail->SMTPAutoTLS = false;
        //------email settings
        $mail->isHTML(true);
        $mail->SetFrom("mohamed.amiar@gmail.com", "amiar");
        $mail->AddAddress($destinataire);
        $mail->Subject  = $subject;
        $mail->Body     = $messages;
        $mail->AltBody = $altBody;

        if (!$mail->Send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent.';
            $this->redirect($redirect);
        }
    }
}
