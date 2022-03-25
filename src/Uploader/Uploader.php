<?php

namespace App\Uploader;

class Uploader
{
    public function getFileName(array $infosFile)
    {
        //formatage du nom
        $fileName = $infosFile['pictures']['name'];
        $search  = array('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ');
        $replace = array('AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $fileName = str_replace($search, $replace, $fileName);
        $fileUpload = uniqid() . $fileName;
        return $fileUpload;
    }
    /******************************************************************************** */
    public function upload(array $infosFile)
    {
        $fileUpload = $this->getFileName($infosFile);
        $types = [
            "image/jpeg", "image/jpg", "image/png", "image/gif"
        ];
        //on vérifie si le type de fichier est correcte
        if (!in_array($infosFile['pictures']['type'], $types)) {
            $erreur = "le fichier n'est pas valide";
        }
        //s'il n'ya pas d'erreur on lance l'upload
        if (!isset($erreur)) {
            move_uploaded_file($infosFile['pictures']['tmp_name'], ROOT . "/public/uploads/" . $fileUpload);
        } else {
            echo $erreur;
        }
        return $fileUpload;
    }
    /****************************************************************************** */
}
