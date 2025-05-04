<?php
require_once __DIR__ . '/../Controllers/apprenant.controller.php';
function handleTelechargerListe()
{
    $format = $_GET['format'] ?? 'pdf';
    $apprenants = get_all_apprenant(); // Assurez-vous que cette fonction existe et retourne tous les apprenants

    if ($format === 'excel') {
        generateExcel($apprenants);
    } elseif ($format === 'pdf') {
        generatePdf($apprenants);
    } else {
        throw new Exception("Format non supporté");
    }
}

function generateExcel($apprenants)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Photo');
    $sheet->setCellValue('B1', 'Matricule');
    $sheet->setCellValue('C1', 'Nom Complet');
    $sheet->setCellValue('D1', 'Adresse');
    $sheet->setCellValue('E1', 'Téléphone');
    $sheet->setCellValue('F1', 'Référentiel');
    $sheet->setCellValue('G1', 'Statut');

    $row = 2;
    foreach ($apprenants as $apprenant) {
        $sheet->setCellValue("A$row", 'Photo'); // Vous pouvez ajouter un lien vers l'image si nécessaire
        $sheet->setCellValue("B$row", $apprenant['matricule']);
        $sheet->setCellValue("C$row", $apprenant['prenom'] . ' ' . $apprenant['nom']);
        $sheet->setCellValue("D$row", $apprenant['adresse']);
        $sheet->setCellValue("E$row", $apprenant['telephone']);
        $sheet->setCellValue("F$row", get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A');
        $sheet->setCellValue("G$row", ucfirst($apprenant['status']));
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="apprenants.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}

function generatePdf($apprenants)
{
    $dompdf = new Dompdf();
    $html = '<table border="1">
                <tr>
                    <th>Photo</th>
                    <th>Matricule</th>
                    <th>Nom Complet</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Référentiel</th>
                    <th>Statut</th>
                </tr>';

    foreach ($apprenants as $apprenant) {
        $html .= '<tr>
                    <td>Photo</td> <!-- Vous pouvez ajouter un lien vers l\'image si nécessaire -->
                    <td>' . htmlspecialchars($apprenant['matricule']) . '</td>
                    <td>' . htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) . '</td>
                    <td>' . htmlspecialchars($apprenant['adresse']) . '</td>
                    <td>' . htmlspecialchars($apprenant['telephone']) . '</td>
                    <td>' . htmlspecialchars(get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars(ucfirst($apprenant['status'])) . '</td>
                  </tr>';
    }

    $html .= '</table>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("apprenants.pdf", ["Attachment" => true]);
    exit;
}
?>