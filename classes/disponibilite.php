<?php

class Disponibilite {
    private $id_dispo;
    private $id_coach;
    private $jour;
    private $heure_d;
    private $heure_f;

     public function __construct(
        $id_coach,
        $jour,
        $heure_d,
        $heure_f
    ) {
        $this->id_coach = $id_coach;
        $this->jour = $jour;
        $this->heure_d = $heure_d;
        $this->heure_f = $heure_f;
    }
    
    public function save(PDO $pdo) : bool{
        $sql = "INSERT INTO disponibilite (id_coach, jour, heure_d, heure_f)
                VALUES (:id_coach, :jour, :heure_d, :heure_f)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id_coach' => $this->id_coach,
            'jour' => $this->jour,
            'heure_d' => $this->heure_d,
            'heure_f' => $this->heure_f
        ]);
    }

     public static function getByCoach(PDO $pdo, $id_coach): array {
        $stmt = $pdo->prepare(
            "SELECT * FROM disponibilite
             WHERE id_coach = :id_coach
             ORDER BY jour, heure_d"
        );
        $stmt->execute(['id_coach' => $id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>