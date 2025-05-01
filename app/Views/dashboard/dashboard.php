<div class="row">
    <div class="col-md-6">
        <label for="excelFile">Fichier Excel</label>
        <input type="file" id="excelFile" name="excelFile" accept=".xls, .xlsx">
        <?php if (isset($_SESSION['form_errors']['excelFile'])): ?>
            <div class="error"><?= $_SESSION['form_errors']['excelFile'] ?></div>
        <?php endif; ?>
    </div>
</div>






<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = [];

    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $cells = [];
        foreach ($cellIterator as $cell) {
            $cells[] = $cell->getValue();
        }
        $rows[] = $cells;
    }

    // Supprimer la première ligne si elle contient les en-têtes
    array_shift($rows);

    foreach ($rows as $row) {
        // Assurez-vous que les indices correspondent aux colonnes de votre fichier Excel
        $prenom = $row[0];
        $nom = $row[1];
        $dateNaissance = $row[2];
        $lieuNaissance = $row[3];
        $adresse = $row[4];
        $email = $row[5];
        $telephone = $row[6];
        $tuteurNom = $row[7];
        $parente = $row[8];
        $tuteurAdresse = $row[9];
        $tuteurTelephone = $row[10];
        $referentiel_id = $row[11];

        // Insérez les données dans votre base de données
        // Exemple avec PDO
        $pdo = new PDO('mysql:host=localhost;dbname=votre_base_de_donnees', 'utilisateur', 'mot_de_passe');
        $stmt = $pdo->prepare("INSERT INTO apprenants (prenom, nom, dateNaissance, lieuNaissance, adresse, email, telephone, tuteurNom, parente, tuteurAdresse, tuteurTelephone, referentiel_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $dateNaissance, $lieuNaissance, $adresse, $email, $telephone, $tuteurNom, $parente, $tuteurAdresse, $tuteurTelephone, $referentiel_id]);
    }

    $_SESSION['flash_message'] = 'Les apprenants ont été ajoutés avec succès.';
    header('Location: ?page=apprenant&action=liste-apprenant');
    exit;
}
?>


<form action="?page=apprenant&action=import-apprenants" method="POST" enctype="multipart/form-data">
    <!-- Ajoutez le champ de téléchargement pour le fichier Excel -->
    <div class="row">
        <div class="col-md-6">
            <label for="excelFile">Fichier Excel</label>
            <input type="file" id="excelFile" name="excelFile" accept=".xls, .xlsx">
            <?php if (isset($_SESSION['form_errors']['excelFile'])): ?>
                <div class="error"><?= $_SESSION['form_errors']['excelFile'] ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-right">
        <a href="?page=apprenant&action=liste-apprenant" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-save">Importer</button>
    </div>
</form>





<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    if ($_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
        // Traitez le fichier Excel comme décrit précédemment
    } else {
        $_SESSION['form_errors']['excelFile'] = 'Erreur lors du téléchargement du fichier.';
        header('Location: ?page=apprenant&action=import-apprenants');
        exit;
    }
}
?>