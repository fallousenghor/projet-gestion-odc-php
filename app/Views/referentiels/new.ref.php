<!DOCTYPE html>
<html lang="fr">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Créer un référentiel</title>

</head>

<body>
    <div class="modal">
        <a href="?page=referentiel&action=liste-ref">
            <div class="close-btn">&times;</div>
        </a>
        <h2>Créer un nouveau référentiel</h2>

        <form method="POST" action="?page=referentiel&action=new-ref" enctype="multipart/form-data">
            <div class="form-group fg-img <?php echo isset($errors['photo']) ? 'has-error' : ''; ?>">
                <div class="upload-box">
                    <span>Cliquer pour ajouter une photo</span>
                    <input type="file" name="photo" accept=".jpg,.png">
                </div>
                <?php if (isset($errors['photo'])): ?>
                    <div class="error-message"><?php echo $errors['photo']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['nom']) ? 'has-error' : ''; ?>">
                <label for="nom">Nom*</label>
                <input type="text" id="nom" name="nom" placeholder="Entrer le nom"
                    value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" />
                <?php if (isset($errors['nom'])): ?>
                    <div class="error-message"><?php echo $errors['nom']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['desc']) ? 'has-error' : ''; ?>">
                <label for="desc">Description</label>
                <textarea id="desc" name="desc"
                    placeholder="Ajouter une description..."><?php echo htmlspecialchars($_POST['desc'] ?? ''); ?></textarea>
                <?php if (isset($errors['desc'])): ?>
                    <div class="error-message"><?php echo $errors['desc']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group <?php echo isset($errors['capacite']) ? 'has-error' : ''; ?>">
                    <label for="capacite">Capacité*</label>
                    <input type="text" id="capacite" name="capacite"
                        value="<?php echo htmlspecialchars($_POST['capacite'] ?? '30'); ?>" />
                    <?php if (isset($errors['capacite'])): ?>
                        <div class="error-message"><?php echo $errors['capacite']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo isset($errors['sessions']) ? 'has-error' : ''; ?>">
                    <label for="sessions">Nombre de sessions*</label>
                    <select id="sessions" name="sessions">
                        <option value="1" <?php echo ($_POST['sessions'] ?? '1') === '1' ? 'selected' : ''; ?>>1 session
                        </option>
                        <option value="2" <?php echo ($_POST['sessions'] ?? '1') === '2' ? 'selected' : ''; ?>>2
                            sessions</option>
                        <option value="3" <?php echo ($_POST['sessions'] ?? '1') === '3' ? 'selected' : ''; ?>>3
                            sessions</option>
                    </select>
                    <?php if (isset($errors['sessions'])): ?>
                        <div class="error-message"><?php echo $errors['sessions']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="buttons">
                <a href="?page=referentiel&action=liste-ref">
                    <button class="btn cancel" type="button">Annuler</button>
                </a>
                <button class="btn create" type="submit">Créer</button>
            </div>
        </form>
    </div>


</body>

</html>


<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        padding: 20px;
        margin: 0;


    }

    .modal {
        z-index: 1;
        width: 800px;
        /* min-height: 500px; */
        margin: 150px auto;
        background-color: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        padding: 24px;
        position: relative;
        display: flex;

        flex-direction: column;

    }



    .modal h2 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 20px;
        font-weight: bold;
        color: #666;
        cursor: pointer;
    }

    .upload-box {
        border: 2px dashed var(--text-grey);
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        font-size: 14px;
        color: var(#949698);
        cursor: pointer;
        width: 40%;
        height: 100px;
        /* Occupation complète */
        position: relative;
        margin: auto;
        background-color: #f1f5f6;

    }

    .upload-box input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        font-size: 15px;
    }

    label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        /* background-color: #f9f9f9; */
        margin-bottom: 16px;
        font-size: 14px;
        outline: none;
    }

    .has-error input,
    .has-error textarea,
    .has-error select {
        border-color: #ff3860;
    }

    .error-message {
        color: #ff3860;
        font-size: 0.8em;
        margin-top: 0.25rem;
    }

    select {
        background-color: white;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-row {
        display: flex;
        gap: 10px;
    }

    .form-row>div {
        flex: 1;
    }

    .buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
    }

    .btn.cancel {
        background-color: transparent;
        color: #666;
    }

    .btn.create {
        background-color: #c5e2d7;
        color: #fff;
    }

    .btn.create:hover {
        background-color: #a8d0c1;
    }

    @media (max-width: 500px) {
        .modal {
            margin: 10px;
            padding: 20px;
        }
    }
</style>