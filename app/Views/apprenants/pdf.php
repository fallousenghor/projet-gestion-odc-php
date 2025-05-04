<?php
require_once '../../vendor/autoload.php'; // Assurez-vous que le chemin est correct

use TCPDF;

function generatePDF($apprenants)
{
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre Nom');
    $pdf->SetTitle('Liste des Apprenants');
    $pdf->SetSubject('Liste des Apprenants');
    $pdf->SetKeywords('TCPDF, PDF, exemple, test, guide');

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h1>Liste des Apprenants</h1>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<tr>
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
                    <td><img src="./assets/images/dev.jpeg" alt="Photo" width="50"></td>
                    <td>' . htmlspecialchars($apprenant['matricule'] ?? '') . '</td>
                    <td>' . htmlspecialchars($apprenant['prenom'] ?? '') . ' ' . htmlspecialchars($apprenant['nom'] ?? '') . '</td>
                    <td>' . htmlspecialchars($apprenant['adresse'] ?? '') . '</td>
                    <td>' . htmlspecialchars($apprenant['telephone'] ?? '') . '</td>
                    <td>' . htmlspecialchars(get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A') . '</td>
                    <td>' . ucfirst(htmlspecialchars($apprenant['status'] ?? '')) . '</td>
                  </tr>';
    }

    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('liste_apprenants.pdf', 'D');
}
?>