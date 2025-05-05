<?php

require_once __DIR__ . '/../../vendor/autoload.php';
// require_once __DIR__ . '/../utils/utils.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use TCPDF;

function generateApprenantsPDF($apprenants)
{

    $pdf = new TCPDF();


    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre Nom');
    $pdf->SetTitle('Liste des Apprenants');
    $pdf->SetSubject('Liste des Apprenants');
    $pdf->SetKeywords('TCPDF, PDF, exemple, test, guide');


    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


    $pdf->AddPage();


    $pdf->SetFont('helvetica', '', 12);

    $pdf->Cell(0, 10, 'Liste des Apprenants', 0, 1, 'C');
    $pdf->Ln(10);


    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(20, 10, 'Photo', 1);
    $pdf->Cell(30, 10, 'Matricule', 1);
    $pdf->Cell(40, 10, 'Nom Complet', 1);
    $pdf->Cell(40, 10, 'Adresse', 1);
    $pdf->Cell(30, 10, 'Téléphone', 1);
    $pdf->Cell(30, 10, 'Référentiel', 1);
    $pdf->Cell(20, 10, 'Statut', 1);
    $pdf->Ln();


    $pdf->SetFont('helvetica', '', 10);
    foreach ($apprenants as $apprenant) {
        $pdf->Cell(20, 10, 'Photo', 1);
        $pdf->Cell(30, 10, $apprenant['matricule'] ?? '', 1);
        $pdf->Cell(40, 10, $apprenant['prenom'] . ' ' . $apprenant['nom'], 1);
        $pdf->Cell(40, 10, $apprenant['adresse'] ?? '', 1);
        $pdf->Cell(30, 10, $apprenant['telephone'] ?? '', 1);
        $pdf->Cell(30, 10, get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A', 1);
        $pdf->Cell(20, 10, ucfirst($apprenant['status'] ?? ''), 1);
        $pdf->Ln();
    }


    $pdf->Output('liste_apprenants.pdf', 'D');
}

function import_apprenants_from_excel($filePath, $promotion_id)
{
    if (!file_exists($filePath)) {
        return ['success' => false, 'message' => 'Fichier introuvable'];
    }

    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = [];

    foreach ($sheet->getRowIterator() as $row) {
        $cells = $row->getCellIterator();
        $cells->setIterateOnlyExistingCells(false);
        $rowData = [];

        foreach ($cells as $cell) {
            $rowData[] = $cell->getValue();
        }

        $rows[] = $rowData;
    }

    $headers = array_shift($rows);
    $requiredColumns = [
        'prenom',
        'nom',
        'dateNaissance',
        'lieuNaissance',
        'adresse',
        'email',
        'telephone',
        'tuteurNom',
        'parente',
        'tuteurAdresse',
        'tuteurTelephone',
        'referentiel'
    ];

    foreach ($requiredColumns as $col) {
        if (!in_array($col, $headers)) {
            return ['success' => false, 'message' => 'Colonne manquante: ' . $col];
        }
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $rowIndex => $rowData) {
        $apprenantData = array_combine($headers, $rowData);

        $apprenantData = array_map(function ($value) {
            return $value !== null ? trim($value) : '';
        }, $apprenantData);

        $apprenantData['email'] = filter_var($apprenantData['email'], FILTER_SANITIZE_EMAIL);

        $validation = validate_apprenant_data($apprenantData);

        if (!$validation['isValid']) {
            $errors[] = 'Ligne ' . ($rowIndex + 2) . ': ' . implode(', ', $validation['errors']);
            continue;
        }

        $referentiel_id = getReferentielIdByName($apprenantData['referentiel']);
        if (!$referentiel_id) {

            // $referentiel_id = createReferentiel($apprenantData['referentiel']);
        }

        $apprenant = [
            'prenom' => $apprenantData['prenom'],
            'nom' => $apprenantData['nom'],
            'date_naissance' => $apprenantData['dateNaissance'],
            'lieu_naissance' => $apprenantData['lieuNaissance'],
            'adresse' => $apprenantData['adresse'],
            'email' => $apprenantData['email'],
            'telephone' => $apprenantData['telephone'],
            'status' => 'active',
            'tuteur' => [
                'nom' => $apprenantData['tuteurNom'],
                'lien_parente' => $apprenantData['parente'],
                'adresse' => $apprenantData['tuteurAdresse'],
                'telephone' => $apprenantData['tuteurTelephone']
            ],
            'promotion_id' => $promotion_id,
            'referentiel_id' => $referentiel_id,
            'documents' => []
        ];

        if (!save_apprenant($apprenant)) {
            $errors[] = 'Ligne ' . ($rowIndex + 2) . ': Erreur lors de l\'enregistrement';
        } else {
            $imported++;
        }
    }

    return [
        'success' => true,
        'imported' => $imported,
        'errors' => $errors
    ];
}

function generate_example_excel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'prenom',
        'nom',
        'dateNaissance',
        'lieuNaissance',
        'adresse',
        'email',
        'telephone',
        'tuteurNom',
        'parente',
        'tuteurAdresse',
        'tuteurTelephone',
        'referentiel'
    ];

    $sheet->fromArray($headers, null, 'A1');

    $exampleData = [
        ['Fallou', 'Senghor', '1998-01-15', 'Fatick', 'Dakar 12 Rue ', 'fallousenghor@gmail.com', '771234567', 'Pape', 'Frere', '12 Rue Dakar', '771234568', 'DEV WEB/MOBILE'],
    ];

    foreach ($exampleData as $rowIndex => $row) {
        $sheet->fromArray($row, null, 'A' . ($rowIndex + 2));
    }

    foreach (range('A', 'L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setWidth(20);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="exemple_apprenants.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

function handle_upload_excel()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_excel'])) {
        $file = $_FILES['import_excel']['tmp_name'];
        if (!is_uploaded_file($file)) {
            setSessionMessage('flash_message', ApprenantTexts::INVALID_FILE->value);
            header('Location: ?page=apprenant&action=inscription-groupee');
            exit;
        }

        $activePromo = getActivePromotion();
        if (!$activePromo) {
            setSessionMessage('flash_message', ApprenantTexts::NO_ACTIVE_PROMOTION->value);
            header('Location: ?page=promotions');
            exit;
        }

        $result = import_apprenants_from_excel($file, $activePromo['id']);

        if ($result['success']) {
            setSessionMessage('flash_message', ApprenantTexts::IMPORT_SUCCESS->value . " " . $result['imported'] . " apprenants importés.");
            if (!empty($result['errors'])) {
                setSessionMessage('flash_message', " Erreurs rencontrées : " . implode(', ', $result['errors']));
            }
        } else {
            setSessionMessage('flash_message', ApprenantTexts::IMPORT_FAILED->value . " " . $result['message']);
        }

        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    } else {
        setSessionMessage('flash_message', ApprenantTexts::NO_FILE_SENT->value);
        header('Location: ?page=apprenant&action=inscription-groupee');
        exit;
    }
}