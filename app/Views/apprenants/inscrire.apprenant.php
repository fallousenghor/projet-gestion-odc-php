<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajout Apprenant</title>
</head>

<body>
    <div class="container">
        <?php
        $flashMessage = getSessionMessage('flash_message');
        $formErrors = getSessionMessage('form_errors') ?? [];
        $formData = getFormData() ?? [];

        if ($flashMessage): ?>
        <?php
            $isSuccess = $flashMessage && (strpos($flashMessage, 'succès') !== false || strpos($flashMessage, 'Succès') !== false);
            ?>
        <div class="alert alert-<?= $isSuccess ? 'success' : 'danger' ?>">
            <?= $flashMessage ?>
        </div>
        <?php
           
            if ($isSuccess) {
                setFormData([]);
                setSessionMessage('form_errors', []);
            }
            ?>
        <?php endif; ?>
        <form action="?page=apprenant&action=upload-excel" method="post" enctype="multipart/form-data"
            onsubmit="return resetFormOnSuccess(event)">
            <div class="form-section">
                <div class="section-title">Importation via CSV</div>

                <label for="import_csv">Fichier CSV</label>
                <input type="file" id="import_csv" name="import_csv" accept=".csv">

                <label for="referentiel_id">Référentiel</label>
                <select id="referentiel_id" name="referentiel_id" required>
                    <option value="">Sélectionnez un référentiel</option>
                    <?php if (isset($referentiels) && is_array($referentiels)): ?>
                    <?php foreach ($referentiels as $referentiel): ?>
                    <option value="<?= htmlspecialchars($referentiel['id']) ?>">
                        <?= htmlspecialchars($referentiel['titre']) ?>
                    </option>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <option disabled>Aucun référentiel disponible</option>
                    <?php endif; ?>
                </select>

                <div class="form-text">
                    Téléchargez notre
                    <a href="?page=apprenant&action=upload-excel&download_template=1" class="link-primary">modèle
                        CSV</a>
                    pour garantir un format correct.
                </div>

                <button type="submit" name="upload_excel" class="btn btn-save">Importer le fichier</button>


                <?php if (getSessionMessage('upload_message')): ?>
                <div class="alert alert-success mt-2">
                    <?= getSessionMessage('upload_message'); ?>
                </div>
                <?php endif; ?>
            </div>
        </form>



        <div class="titre">
            <h1>Ajout apprenant</h1>
        </div>

        <form action="?page=apprenant&action=save-apprenant" method="POST" enctype="multipart/form-data">


            <div class="form-section">
                <div class="section-title">Informations de l'apprenant</div>
                <div class="row">
                    <div>
                        <label for="prenom">Prénom(s)</label>
                        <input type="text" id="prenom" name="prenom"
                            value="<?= htmlspecialchars($formData['prenom'] ?? '') ?>">
                        <?php if (isset($formErrors['prenom'])): ?>
                        <div class="error">
                            <?= $formErrors['prenom'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($formData['nom'] ?? '') ?>">
                        <?php if (isset($formErrors['nom'])): ?>
                        <div class="error">
                            <?= $formErrors['nom'] ?>
                        </div><?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="dateNaissance">Date de naissance</label>
                        <input type="text" id="dateNaissance" name="dateNaissance"
                            value="<?= htmlspecialchars($formData['dateNaissance'] ?? '') ?>">
                        <?php if (isset($formErrors['dateNaissance'])): ?>
                        <div class="error">
                            <?= $formErrors['dateNaissance'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <label for="lieuNaissance">Lieu de naissance</label>
                        <input type="text" id="lieuNaissance" name="lieuNaissance"
                            value="<?= htmlspecialchars($formData['lieuNaissance'] ?? '') ?>">
                        <?php if (isset($formErrors['lieuNaissance'])): ?>
                        <div class="error">
                            <?= $formErrors['lieuNaissance'] ?>
                        </div><?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse"
                            value="<?= htmlspecialchars($formData['adresse'] ?? '') ?>">
                        <?php if (isset($formErrors['adresse'])): ?>
                        <div class="error">
                            <?= $formErrors['adresse'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email"
                            value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
                        <?php if (isset($formErrors['email'])): ?>
                        <div class="error">
                            <?= $formErrors['email'] ?>
                        </div><?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone"
                            value="<?= htmlspecialchars($formData['telephone'] ?? '') ?>">
                        <?php if (isset($formErrors['telephone'])): ?>
                        <div class="error">
                            <?= $formErrors['telephone'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <div class="upload-box">
                            <label for="documents">Ajouter des documents</label>
                            <input type="file" id="documents" name="documents[]" multiple>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Infos tuteur -->
            <div class="form-section">
                <div class="section-title">Informations du tuteur</div>
                <div class="row">
                    <div>
                        <label for="tuteurNom">Prénom(s) & nom</label>
                        <input type="text" id="tuteurNom" name="tuteurNom"
                            value="<?= htmlspecialchars($formData['tuteurNom'] ?? '') ?>">
                        <?php if (isset($formErrors['tuteurNom'])): ?>
                        <div class="error">
                            <?= $formErrors['tuteurNom'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <label for="parente">Lien de parenté</label>
                        <input type="text" id="parente" name="parente"
                            value="<?= htmlspecialchars($formData['parente'] ?? '') ?>">
                        <?php if (isset($formErrors['parente'])): ?>
                        <div class="error">
                            <?= $formErrors['parente'] ?>
                        </div><?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="tuteurAdresse">Adresse</label>
                        <input type="text" id="tuteurAdresse" name="tuteurAdresse"
                            value="<?= htmlspecialchars($formData['tuteurAdresse'] ?? '') ?>">
                        <?php if (isset($formErrors['tuteurAdresse'])): ?>
                        <div class="error">
                            <?= $formErrors['tuteurAdresse'] ?>
                        </div><?php endif; ?>
                    </div>
                    <div>
                        <label for="tuteurTelephone">Téléphone</label>
                        <input type="text" id="tuteurTelephone" name="tuteurTelephone"
                            value="<?= htmlspecialchars($formData['tuteurTelephone'] ?? '') ?>">
                        <?php if (isset($formErrors['tuteurTelephone'])): ?>
                        <div class="error">
                            <?= $formErrors['tuteurTelephone'] ?>
                        </div><?php endif; ?>
                    </div>
                </div>
            </div>


            <div class="form-section">
                <div class="section-title">Référentiel</div>
                <label for="referentiel_id">Référentiel</label>
                <select id="referentiel_id" name="referentiel_id">
                    <option value="">Sélectionnez un référentiel</option>
                    <?php if (isset($referentiels) && is_array($referentiels)): ?>
                    <?php foreach ($referentiels as $referentiel): ?>
                    <option value="<?= htmlspecialchars($referentiel['id']) ?>"
                        <?= isset($formData['referentiel_id']) && $formData['referentiel_id'] == $referentiel['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($referentiel['titre']) ?>
                    </option>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <option disabled>Aucun référentiel disponible</option>
                    <?php endif; ?>
                </select>
                <?php if (isset($formErrors['referentiel_id'])): ?>
                <div class="error">
                    <?= $formErrors['referentiel_id'] ?>
                </div><?php endif; ?>
            </div>


            <div class="text-right">
                <a href="?page=apprenant&action=liste-apprenant" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-save">Enregistrer</button>
            </div>
        </form>
    </div>
</body>

</html>






<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background: #f9f9f9;
    /* height: 100vh; */
}

.container {
    min-width: 70%;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);


}

.titre h1 {
    text-align: center;
    margin-bottom: 20px;
}

.form-section {
    margin-top: 30px;
    border-top: 1px solid #ccc;
    padding-top: 20px;
    /* max-height: 80%; */
}

.section-title {
    font-size: 18px;
    margin-bottom: 15px;
    color: #333;
    font-weight: bold;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="file"],
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.row>div {
    flex: 1 1 45%;
}

.text-right {
    text-align: right;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
}

.btn-save {
    background-color: #4CAF50;
    color: white;
}

.btn-secondary {
    background-color: #ccc;
    color: #333;
}

.alert {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

.error {
    color: red;
    font-size: 14px;
}

.upload-box {
    padding: 10px;
    background-color: #eee;
    border: 1px dashed #aaa;
    text-align: center;
    border-radius: 5px;
}

.upload-box input[type="file"] {
    display: block;
    margin-top: 10px;
}

.form-text {
    font-size: 13px;
    color: #666;
}

a.link-primary {
    color: #007bff;
    text-decoration: underline;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    width: 1100px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.titre {
    text-align: center;
    margin-bottom: 20px;
}

.titre h1 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.titre span {
    font-size: 1rem;
    color: #666;
}

.form-section {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
}

.section-title {
    font-weight: bold;
    font-size: 1.2rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-left: 10px;
    cursor: pointer;
}

.row {
    display: flex;
    margin-bottom: 15px;
}

.col-md-6 {
    flex: 1;
    margin-right: 10px;
}

.col-md-6:last-child {
    margin-right: 0;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="date"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.upload-box {
    border: 2px dashed #007bff;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    color: #007bff;
    cursor: pointer;
}

.upload-box i {
    font-size: 2rem;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
    font-size: 1rem;
}

.btn-secondary {
    background-color: #6c757d;
    margin-right: 10px;
}

.btn-save {
    background-color: #009688;
}

.btn-save:hover,
.btn-secondary:hover {
    opacity: 0.8;
}

.alert {
    padding: 15px;
    border: 1px solid transparent;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
</style>