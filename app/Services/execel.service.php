<?php
function readCSVFile($filePath)
{
    $apprenants = [];

    if (($handle = fopen($filePath, "r")) !== FALSE) {

        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if (empty(array_filter($data)))
                continue;

            $apprenants[] = [
                'prenom' => $data[0] ?? '',
                'nom' => $data[1] ?? '',
                'dateNaissance' => $data[2] ?? '',
                'lieuNaissance' => $data[3] ?? '',
                'adresse' => $data[4] ?? '',
                'email' => $data[5] ?? '',
                'telephone' => $data[6] ?? '',
                'tuteurNom' => $data[7] ?? '',
                'parente' => $data[8] ?? '',
                'tuteurAdresse' => $data[9] ?? '',
                'tuteurTelephone' => $data[10] ?? '',
                'referentiel_id' => $data[11] ?? ''
            ];
        }
        fclose($handle);
    }

    return $apprenants;
}

function generateCSVTemplate()
{
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="modele_import_apprenants.csv"');
    header('Cache-Control: max-age=0');

    $output = fopen('php://output', 'w');


    fputcsv($output, [
        'Prénom(s)',
        'Nom',
        'Date de naissance (YYYY-MM-DD)',
        'Lieu de naissance',
        'Adresse',
        'Email',
        'Téléphone',
        'Nom du tuteur',
        'Lien de parenté',
        'Adresse du tuteur',
        'Téléphone du tuteur',
        'ID Référentiel'
    ]);


    fputcsv($output, [
        'Jean',
        'Dupont',
        '2000-01-15',
        'Paris',
        '123 Rue Exemple',
        'jean.dupont@example.com',
        '0123456789',
        'Marie Dupont',
        'Mère',
        '123 Rue Exemple',
        '0987654321',
        '1'
    ]);

    fclose($output);
    exit;
}