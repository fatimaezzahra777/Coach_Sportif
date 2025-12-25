<?php

class Sportif extends Utilisateur {
    private $id_sportif;
    private $niveau;

    public function __construct($id, $nom, $email, $telephone, $role, $id_sportif, $niveau, $passwordHash = null){
        parent::__construct($id, $nom, $email, $telephone, $role, $passwordHash = null);
        $this->niveau=$niveau;
    }

    public function getIdSportif() {
        return $this->id_sportif;
    }
    public function getNiveau() {
        return $this->niveau;
    }
}

?>