<?php

require_once __DIR__ . '/Utilisateur.php';

class Coach extends Utilisateur {
    private $id_coach;
    private $biographie;
    private $discipline;
    private $experience;
    private $certif;
    private $photo;


    public function __construct($id, $nom, $email, $telephone, $role, $id_coach, $biographie, $discipline, $experience, $certif, $photo, $passwordHash = null){
        parent::__construct($id, $nom, $email, $telephone, $role, $passwordHash);
         $this->id_coach   = $id_coach;
         $this->biographie = $biographie;
         $this->discipline = $discipline;
         $this->experience = $experience;
         $this->certif     = $certif;
         $this->photo      = $photo;
    }

    public function getIdCoach(): int {
        return $this->id_coach;
    }
    public function getBiographie(): string {
        return $this->biographie;
    }
    public function getDiscipline(): string {
        return $this->discipline;
    }
    public function getCertif(): string {
        return $this->certif;
    }
    public function getExperience(): string {
        return $this->experience;
    }
    public function getPhoto(): string {
        return $this->photo;
    }   
}

?>

