<?php
class AbsenceModel {
    private $absences = [
        ["id" => 1, "date" => "2025-09-22", "eval" => "non", "status" => "À justifier"],
        ["id" => 2, "date" => "2025-09-21", "eval" => "oui", "status" => "Justifié"]
    ];

    public function getAbsences() {
        return $this->absences;
    }

    public function justify($id) {
        foreach ($this->absences as &$absence) {
            if ($absence["id"] == $id) {
                $absence["status"] = "Justifié";
            }
        }
    }

    public function unlock($id) {
        foreach ($this->absences as &$absence) {
            if ($absence["id"] == $id) {
                $absence["status"] = "Déverrouillé";
            }
        }
    }
}
