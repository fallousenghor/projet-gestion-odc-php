<!DOCTYPE html>
<html lang="fr">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/form.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Ajouter promotion</title>

</head>

<body>

    <div class="modal">
        <a href="?page=promotions">
            <div class="close-btn">&times;</div>
        </a>
        <h2>Créer une nouvelle promotion</h2>
        <p>Remplissez les informations ci-dessous pour créer une nouvelle promotion.</p>

        <?php
        $formData = getFormData();
        $errors = getSessionMessage('form_errors') ?? [];
        ?>


        <?php if ($errorMessage = getSessionMessage('error_message')): ?>
            <div class="error-message mb-3">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="?page=promotions&action=add-promo" enctype="multipart/form-data">
            <input type="hidden" name="generer_id" value="1">


            <div class="form-group <?php echo isset($errors['nom']) ? 'has-error' : ''; ?>">
                <label for="nom">Nom de la promotion</label>
                <input type="text" name="nom" id="nom" placeholder="Ex: Promotion 2025"
                    value="<?php echo htmlspecialchars($formData['nom'] ?? ''); ?>">
                <?php if (isset($errors['nom'])): ?>
                    <div class="error-message"><?php echo $errors['nom']; ?></div>
                <?php endif; ?>
            </div>


            <div class="date-container">

                <div class="form-group <?php echo isset($errors['debut']) ? 'has-error' : ''; ?>">
                    <label for="debut">Date de début (format: jj/mm/annee)</label>
                    <input type="text" name="debut" id="debut" placeholder="jj/mm/annee"
                        value="<?php echo htmlspecialchars($formData['debut'] ?? ''); ?>">
                    <?php if (isset($errors['debut'])): ?>
                        <div class="error-message"><?php echo $errors['debut']; ?></div>
                    <?php endif; ?>
                </div>


                <div class="form-group <?php echo isset($errors['fin']) ? 'has-error' : ''; ?>">
                    <label for="fin">Date de fin (format: jj/mm/annee)</label>
                    <input type="text" name="fin" id="fin" placeholder="jj/mm/annee"
                        value="<?php echo htmlspecialchars($formData['fin'] ?? ''); ?>">
                    <?php if (isset($errors['fin'])): ?>
                        <div class="error-message"><?php echo $errors['fin']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <label>Photo de la promotion</label>
            <div class="form-group fg-img <?php echo isset($errors['photo']) ? 'has-error' : ''; ?>">
                <div class="upload-box">
                    <span>Ajouter</span>
                    ou glisser<br>
                    <input type="file" name="photo" accept=".jpg,.png">
                </div>
                <small>Format JPG, PNG. Taille max 2MB</small>
                <?php if (isset($errors['photo'])): ?>
                    <div class="error-message"><?php echo $errors['photo']; ?></div>
                <?php endif; ?>
            </div>

            <div class="referentiel-container <?php echo isset($errors['referentiels']) ? 'has-error' : ''; ?>">
                <label class="referentiel-label">Référentiels disponibles</label>
                <div class="referentiel-grid">
                    <?php $referentiels = get_all_ref(); ?>
                    <?php foreach ($referentiels as $ref): ?>
                        <div class="referentiel-item">
                            <input type="checkbox" name="referentiels[]" id="ref_<?= $ref['id'] ?>"
                                value="<?= htmlspecialchars($ref['id']) ?>" class="referentiel-checkbox"
                                <?= (isset($formData['referentiels'])) && in_array($ref['id'], (array) $formData['referentiels']) ? 'checked' : '' ?>>
                            <label for="ref_<?= $ref['id'] ?>"
                                class="referentiel-text"><?= htmlspecialchars($ref['titre']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($errors['referentiels'])): ?>
                    <div class="referentiel-error"><?php echo $errors['referentiels']; ?></div>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="?page=promotions">
                    <button type="button" class="btn cancel">Annuler</button>
                </a>

                <button type="submit" class="btn primary">Créer la promotion</button>
            </div>
        </form>

    </div>

</body>

</html>



<style>
    .date-container {
        display: flex;
        position: relative;
    }

    .referentiel-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .referentiel-label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1.1em;
    }

    .referentiel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .referentiel-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
    }

    .referentiel-checkbox {
        width: 16px;
        height: 16px;
        margin-right: 8px;
    }

    .referentiel-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 5px;
    }

    .referentiel-error {
        color: #d32f2f;
        margin-top: 10px;
        font-size: 0.9em;
    }



    .compact-error {
        color: red;
        text-align: center;
        margin-top: 5px;
    }

    .error-message {
        color: red;
        text-align: center;
        margin-top: 5px;
    }



    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
        border: 1px solid black;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }

    .tag-option {
        display: flex;
        align-items: center;


    }

    .tag-option input[type="checkbox"] {
        display: none;

    }

    .tag-option label {
        display: inline-block;
        padding: 6px 12px;
        background: #f0f7ff;
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid #ddd;
    }

    .tag-option input[type="checkbox"]:checked+label {
        background: #fa7214;
        color: white;

        margin-top: -5px;
    }

    a {
        text-decoration: none;
    }

    .close-btn {
        display: flex;
        justify-content: end;
        font-size: 17px;
    }

    form {
        margin-top: 30px !important;
    }

    form label {
        margin-top: 20px;
        font-size: 12px;
    }

    .upload-box {
        border: 2px dashed var(--text-grey);
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        font-size: 14px;
        color: var(--text-grey);
        cursor: pointer;
        width: 30%;
        /* Occupation complète */
        position: relative;
    }

    .upload-box input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .search-container {
        position: relative;
    }

    .search-container i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: var(--text-grey);
        font-size: 18px;
    }

    .search-container input {
        width: 100%;
        padding: 10px 10px 10px 36px;
        border-radius: 6px;
        border: 1px solid var(--text-grey);
        font-size: 14px;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .actions .btn.cancel {
        background-color: transparent;
        border: 1px solid var(--text-grey);
        color: var(--text-grey);
    }

    .actions .btn.primary {
        background-color: var(--orange);
        color: white;
    }

    .fg-img {
        display: flex;
        align-items: center;
        justify-content: start;

    }

    .fg-img small {
        margin-left: 15px;

    }

    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

    .has-error input {
        border-color: #dc3545;
    }
</style>