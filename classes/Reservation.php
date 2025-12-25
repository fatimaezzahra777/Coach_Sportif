<?php

class Réservation {

     private $id_reserv;  
     private $date_r;
     private $heure;
     private $statut; 
     
     public function __construct($id_reserv, $date_r, $heure, $statut) { 
        $this->id = $id; 
        $this->date_r = $date_r; 
        $this->heure = $heure; 
        $this->statut= $statut;
    } 

    public function getId() {
        return $this->id_reserv;
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

    
     

}
?>