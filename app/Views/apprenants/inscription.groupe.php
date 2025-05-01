<form action="?page=apprenant&action=upload-excel" method="post" enctype="multipart/form-data"
    onsubmit="return resetFormOnSuccess(event)">
    <div class="form-section">
        <div class="section-title">Importation via CSV</div>

        <label for="excelFile">Fichier CSV</label>
        <input type="file" id="excelFile" name="import_csv" accept=".csv" required>

        <div class="form-text">
            Téléchargez notre
            <a href="?page=apprenant&action=upload-excel&download_template=1" class="link-primary">modèle
                CSV</a>
            pour garantir un format correct.
        </div>

        <button type="submit" name="upload_excel" class="btn btn-save">Importer le fichier</button>

        <!-- Zone d'affichage des messages -->
        <?php if (!empty($_SESSION['upload_message'])): ?>
            <div class="alert alert-success mt-2">
                <?= $_SESSION['upload_message'];
                unset($_SESSION['upload_message']); ?>
            </div>
        <?php endif; ?>
    </div>
</form>