<?php
require_once "Modele/AbsenceModele.php";

class AbsencePresenter {
    private $model;

    public function __construct() {
        $this->model = new AbsenceModel();
    }

    public function showDashboard($username) {
        $absences = $this->model->getAbsences();
        include "Vue/dashboardView.php";
    }

    public function justify($id) {
        $this->model->justify($id);
    }

    public function unlock($id) {
        $this->model->unlock($id);
    }
}
