<?php

class Réservation {

     private $id_reserv;
     private $id_coach; 
     private $id_sportif;   
     private $date_r;
     private $heure;
     private $statut; 
     
     public function __construct($id_reserv) { 
        $this->id_reserv = $id_reserv; 
    } 

    public function getId() {
        return $this->id_reserv;
    }
    public function getIdCoach() {
        return $this->id_coach;
    }
    public function getIdSportif() {
        return $this->id_sportif;
    }
    public function getDate() {
        return $this->date_r;
    }
    public function getHeure() {
        return $this->heure;
    }
    public function getStatut() {
        return $this->statut;
    }

     public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function save($pdo) {
        $stmt = $pdo->prepare("UPDATE reservation SET statut = :statut WHERE id_reserv = :id");
        $stmt->execute(['statut' => $this->statut, 'id' => $this->id_reserv]);
    }

    public function load($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM reservation WHERE id_reserv = :id");
        $stmt->execute(['id' => $this->id_reserv]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return false;
        }
            $this->id_coach = $data['id_coach'];
            $this->id_sportif = $data['id_sportif'];
            $this->date_r = $data['date_r'];
            $this->heure = $data['heure'];
            $this->statut = $data['statut'];

            return true;

        }
    }
     


?>