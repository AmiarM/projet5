<?php

namespace App\Libs;

abstract class Model
{
    protected $entityManager;
    /**
     * Hydratation des données
     * @param array $donnees Tableau associatif des données
     * @return self Retourne l'objet hydraté
     */
    public function hydrate($donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set' . ucfirst($key);

            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter.
                $this->$method($value);
            }
        }
        return $this;
    }

    public function __construct()
    {
        require dirname(dirname(__DIR__)) . '/bootstrap.php';
        $this->entityManager = $entityManager;
    }
}
