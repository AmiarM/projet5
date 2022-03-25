<?php

namespace App\Repository;

use App\Entity\Contacts;

use Doctrine\ORM\EntityRepository;

class ContactsRepository extends EntityRepository
{
    /************************************************************************************* */
    public function create()
    {
        $contact = new Contacts();
        $contact->setFirstName(strip_tags($_POST['firstname']))
            ->setLastName(strip_tags($_POST['lastname']))
            ->setEmail(strip_tags($_POST['email']))
            ->setSubject(strip_tags($_POST['subject']))
            ->setMessage(strip_tags($_POST['message']))
            ->setIpAdress($_SERVER['REMOTE_ADDR']);
        $this->_em->persist($contact);
        $this->_em->flush();
    }
}
