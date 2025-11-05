<?php

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// Données pour la vue "Année" (Ressources)
$labels_ressources = [];
$donnees_absences = [];

// Données pour la vue "Année" (Type Cours)
$labels_typecours = [];
$donnees_typecours = [];

// Données par semestre (Ressources)
$donnees_par_semestre = [
    'S1' => ['labels' => [], 'data' => []],
    'S2' => ['labels' => [], 'data' => []],
    'S3' => ['labels' => [], 'data' => []],
    'S4' => ['labels' => [], 'data' => []],
    'S5' => ['labels' => [], 'data' => []],
    'S6' => ['labels' => [], 'data' => []],
    'Autre' => ['labels' => [], 'data' => []],
];

// données détaillées par semestre (Type Cours)
$typecours_par_semestre = [
    'S1' => ['labels' => [], 'data' => []],
    'S2' => ['labels' => [], 'data' => []],
    'S3' => ['labels' => [], 'data' => []],
    'S4' => ['labels' => [], 'data' => []],
    'S5' => ['labels' => [], 'data' => []],
    'S6' => ['labels' => [], 'data' => []],
    'Autre' => ['labels' => [], 'data' => []],
];

// --- AJOUT : Variables pour le graphique Courbe ---
$tendance_labels = []; // Les mois, ex: "2024-10"
$tendance_datasets = []; // Les datasets (1 par ressource)
// --- FIN AJOUT ---

$totalAbsences = 0;
$errorMessage = '';
$nomEtudiant = '';

// on détermine le groupe de semestres actif. Par défaut S1/S2.
$semestreGroup = 'S1S2';

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['idUtilisateur']) && !empty($_GET['idUtilisateur'])) {
        $idEtudiantSelectionne = $_GET['idUtilisateur'];

        // requête pour récupérer les ressources et le nombre d'absences par ressource
        $AbsencesParRessources = $conn->prepare("SELECT 
                                                    ressource, 
                                                    COUNT(*) as nb_absences 
                                                FROM Absence 
                                                WHERE idUtilisateur = :idEtudiant
                                                GROUP BY ressource");
        $AbsencesParRessources->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $AbsencesParRessources->execute();
        $ressources = $AbsencesParRessources->fetchAll(PDO::FETCH_ASSOC);

        // requête pour récupérer les types de cours et le nombre d'absences par type de cours
        $AbsencesParTypeDeCours = $conn->prepare("SELECT 
                                                    typecours, 
                                                    COUNT(*) as nb_absences 
                                                FROM Absence 
                                                WHERE idUtilisateur = :idEtudiant
                                                GROUP BY typecours");
        $AbsencesParTypeDeCours->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $AbsencesParTypeDeCours->execute();
        $parTypesTD_TP_CM = $AbsencesParTypeDeCours->fetchAll(PDO::FETCH_ASSOC);

        // récupérer les semestres de l'étudiant grace aux absences
        $SemestresEtudiant = $conn->prepare("SELECT typecours,
                                CASE
                                    WHEN ressource LIKE 'R1%' OR ressource LIKE 'S1%' THEN 'S1'
                                    WHEN ressource LIKE 'R2%' OR ressource LIKE 'S2%' THEN 'S2'
                                    WHEN ressource LIKE 'R3%' OR ressource LIKE 'S3%' THEN 'S3'
                                    WHEN ressource LIKE 'R4%' OR ressource LIKE 'S4%' THEN 'S4'
                                    WHEN ressource LIKE 'R5%' OR ressource LIKE 'S5%' THEN 'S5'
                                    WHEN ressource LIKE 'R6%' OR ressource LIKE 'S6%' THEN 'S6'
                                    ELSE 'Autre'
                                END as semestre,
                                COUNT(*) as nb_absences
                            FROM Absence
                            WHERE idUtilisateur = :idEtudiant
                            GROUP BY semestre, typecours");
        $SemestresEtudiant->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $SemestresEtudiant->execute();
        $typesParSemestre = $SemestresEtudiant->fetchAll(PDO::FETCH_ASSOC);


        // --- AJOUT : REQUÊTE 4 (Tendance par mois et ressource) ---
        $sql_tendance = "SELECT
                            COALESCE(ressource, 'Non défini') as ressource,
                            TO_CHAR(date, 'YYYY-MM') as mois,
                            COUNT(*) as nb_absences
                        FROM Absence
                        WHERE idUtilisateur = :idEtudiant
                          AND date IS NOT NULL
                        GROUP BY ressource, mois
                        ORDER BY mois ASC, ressource ASC";
        $stmt_tendance = $conn->prepare($sql_tendance);
        $stmt_tendance->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $stmt_tendance->execute();
        $tendance_data = $stmt_tendance->fetchAll(PDO::FETCH_ASSOC);
        // --- FIN AJOUT ---


        foreach ($ressources as $ligne) {
            $ressource_nom = $ligne['ressource'];
            $nb_absences = (int)$ligne['nb_absences'];

            $labels_ressources[] = $ressource_nom;
            $donnees_absences[] = $nb_absences;
            $totalAbsences += $nb_absences;

            // Trier la ressource dans le bon semestre
            if (strpos($ressource_nom, 'R1') === 0 || strpos($ressource_nom, 'S1') === 0) {
                $donnees_par_semestre['S1']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S1']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R2') === 0 || strpos($ressource_nom, 'S2') === 0) {
                $donnees_par_semestre['S2']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S2']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R3') === 0 || strpos($ressource_nom, 'S3') === 0) {
                $donnees_par_semestre['S3']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S3']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R4') === 0 || strpos($ressource_nom, 'S4') === 0) {
                $donnees_par_semestre['S4']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S4']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R5') === 0 || strpos($ressource_nom, 'S5') === 0) {
                $donnees_par_semestre['S5']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S5']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R6') === 0 || strpos($ressource_nom, 'S6') === 0) {
                $donnees_par_semestre['S6']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S6']['data'][] = $nb_absences;
            } else {
                $donnees_par_semestre['Autre']['labels'][] = $ressource_nom;
                $donnees_par_semestre['Autre']['data'][] = $nb_absences;
            }
        }


        foreach ($parTypesTD_TP_CM as $ligne) {
            $labels_typecours[] = $ligne['typecours'];
            $donnees_typecours[] = (int)$ligne['nb_absences'];
        }


        foreach ($typesParSemestre as $ligne) {
            $sem = $ligne['semestre'];
            $type = $ligne['typecours'];
            $nb = (int)$ligne['nb_absences'];

            if (isset($typecours_par_semestre[$sem])) {
                $typecours_par_semestre[$sem]['labels'][] = $type;
                $typecours_par_semestre[$sem]['data'][] = $nb;
            }
        }

        // préparation des données pour le graphique courbe
        $tendance_ressources_helper = [];
        foreach ($tendance_data as $ligne) {
            $ressource = $ligne['ressource'];
            $mois = $ligne['mois'];
            $nb = (int)$ligne['nb_absences'];

            if (!in_array($mois, $tendance_labels)) {
                $tendance_labels[] = $mois;
            }
            if (!isset($tendance_ressources_helper[$ressource])) {
                $tendance_ressources_helper[$ressource] = [];
            }
            $tendance_ressources_helper[$ressource][$mois] = $nb;
        }

        $i = 0;
        foreach ($tendance_ressources_helper as $ressource_nom => $data_par_mois) {
            $dataset = [
                'label' => $ressource_nom,
                'data' => [],
                'fill' => false,
                'borderColor' => "hsla(" . ($i * 360 / count($tendance_ressources_helper)) . ", 70%, 50%, 0.8)", // Couleur dynamique
                'tension' => 0.1
            ];

            foreach ($tendance_labels as $mois) {
                $dataset['data'][] = $data_par_mois[$mois] ?? 0; // Ajoute 0 si pas d'absence ce mois-ci
            }

            $tendance_datasets[] = $dataset;
            $i++;
        }

        // Détection du groupe actif (en 1ere année, 2ème ou 3ème grace aux semestres)
        if (!empty($donnees_par_semestre['S5']['data']) || !empty($donnees_par_semestre['S6']['data'])) {
            $semestreGroup = 'S5S6';
        } elseif (!empty($donnees_par_semestre['S3']['data']) || !empty($donnees_par_semestre['S4']['data'])) {
            $semestreGroup = 'S3S4';
        }

        // récupération du nom de l'étudiant
        $stmtNom = $conn->prepare("SELECT nom, prénom FROM Utilisateur WHERE idUtilisateur = :id");
        $stmtNom->execute(['id' => $idEtudiantSelectionne]);
        $etudiant = $stmtNom->fetch();
        if ($etudiant) {
            $nomEtudiant = htmlspecialchars($etudiant['prénom'] . ' ' . $etudiant['nom']);
        }

    } else {
        header('Location: Page_Selection_Etudiant_Statistique.php');
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = "Erreur de connexion à la base de données.";
}

?>