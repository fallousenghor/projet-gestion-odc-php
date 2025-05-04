<?php require '../app/Views/layout/base.layout.php'; ?>

<body>
    <div class="container-app">
        <div class="titre">
            <h1>Apprenants</h1>
            <span><?= $totalApprenants ?> apprenants</span>
        </div>
        <div class="header">
            <form action="?page=apprenant&action=liste-apprenant" method="GET" class="header-left">
                <input type="hidden" name="page" value="apprenant">
                <input type="hidden" name="action" value="liste-apprenant">
                <input type="text" name="search" placeholder="Rechercher"
                    value="<?= htmlspecialchars($search ?? '') ?>">
                <select name="referentiel" id="referentiel">
                    <option value="">Filtrer par référentiel</option>
                    <?php foreach (get_all_ref() as $ref): ?>
                        <option value="<?= $ref['id'] ?>" <?= $referentiel == $ref['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ref['libelle'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="status" id="status">
                    <option value="">Tous les statuts</option>
                    <option value="actif" <?= $status == 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="remplacer" <?= $status == 'remplacer' ? 'selected' : '' ?>>Remplacer</option>
                </select>
                <button type="submit" class="btn-r">Rechercher</button>
            </form>


            <div class="hedader-right">
                <a href="?page=apprenant&action=inscription-groupee" class="telechage-liste excel">Importer un
                    fichier</a>

                <a href="" class="telechage-liste">Télécharger la liste</a>
                <a href="?page=apprenant&action=ad-apprenant" class="liste-apprenant">Ajouter un apprenant</a>
            </div>
        </div>
        <div class="header-nav">
            <a href="" class="liste-or">Liste des retenues</a>
            <a href="">Liste d'attente</a>
        </div>
        <div class="apprenant-table">
            <?php if (!empty($apprenants)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Matricule</th>
                            <th>Nom Complet</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Référentiel</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apprenants as $apprenant): ?>
                            <tr>
                                <td class="photo-cell">
                                    <img src="./assets/images/dev.jpeg" alt="Photo" class="student-photo">
                                </td>
                                <td><?= htmlspecialchars($apprenant['matricule'] ?? '') ?></td>
                                <td>
                                    <?= htmlspecialchars($apprenant['prenom'] ?? '') ?>
                                    <?= htmlspecialchars($apprenant['nom'] ?? '') ?>

                                </td>
                                <td><?= htmlspecialchars($apprenant['adresse'] ?? '') ?></td>
                                <td><?= htmlspecialchars($apprenant['telephone'] ?? '') ?></td>
                                <td>

                                    <span
                                        class="badge ref-badge ref-<?= strtolower(get_referentiel_code($apprenant['referentiel_id'])) ?>">
                                        <?= htmlspecialchars(get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?= $apprenant['status'] == 'actif' ? 'active' : 'replaced' ?>">
                                        <?= ucfirst(htmlspecialchars($apprenant['status'] ?? '')) ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <a href="?page=apprenant&action=dashboard&id=<?= htmlspecialchars($apprenant['id']) ?>">
                                        <button class="action-menu">•••</button>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucune donnée disponible.</p>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <div class="pagination-left">
                <span>Apprenants/page</span>
                <select name="per_page" onchange="this.form.submit()">
                    <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                </select>
            </div>
            <div class="pagination-info">
                <?= $startItem ?> à <?= $endItem ?> apprenants pour <?= $totalApprenants ?>
            </div>
            <div class="pagination-controls">
                <a href="?page=apprenant&action=liste-apprenant&p=<?= max(1, $currentPage - 1) ?>&per_page=<?= $perPage ?>"
                    class="page-arrow <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    &lt;
                </a>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=apprenant&action=liste-apprenant&p=<?= $i ?>&per_page=<?= $perPage ?>&search=<?= $search ?>&referentiel=<?= $referentiel ?>&status=<?= $status ?>"
                        class="page-number <?= $i == $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <a href="?page=apprenant&action=liste-apprenant&p=<?= min($totalPages, $currentPage + 1) ?>&per_page=<?= $perPage ?>"
                    class="page-arrow <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    &gt;
                </a>
            </div>
        </div>
    </div>
</body>


<style>
    :root {
        --vert: #0e938a;
        --orange: #fa7214;
        --blanc: #ffffff;
        --semi-rouge: #fde2e4;
        --vert-text: #37a39c;
        --semi-vert: #b8e2c9;
        --rouge-text: #b55357;
        --vert-claire: #f7fbfc;
        --bg-grey: #f1f5f6;
        --black: #0000;
        --semi-orange: #fff6ef;
        --btn-log: #fef2f2;
        --text-grey: #949698;
    }

    .excel {
        background-color: #047857 !important;
    }

    .container-app {
        width: 84%;
        margin-left: auto;
        margin-top: 4%;
        margin-right: 10px;
    }

    h6 {
        color: white;
        background-color: #37a39c;
        text-align: center;

        border-radius: 25px;
        /* font-size: 17px; */
    }

    td {
        font-size: 14px !important;
    }

    .liste-or {
        border-bottom: 4px solid var(--orange);
    }


    .titre {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .titre h1 {
        color: #0e938a;
    }

    .titre span {
        padding: 10px;
        background-color: #fff6ef;
        color: #fa7214;
        border-radius: 10px;
    }

    .header-left {
        margin-top: 20px;
        width: 75%;
    }

    .btn-r {
        background-color: var(--vert);
        color: var(--blanc);
        font-size: 14px;
    }

    input,
    select,
    button {
        padding: 13px;
        /* background-color: #ffffff; */
        color: black;
        border: none;
        outline: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        font-size: 14px;
    }

    .op-text {
        font-size: 14px;
        color: black;
    }

    input {
        width: 25%;
        color: #949698;
    }

    .hedader-right a {
        padding: 10px;
        text-decoration: none;
    }

    .hedader-right {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
        width: 40%;
    }

    .header {
        display: flex;
        justify-content: space-between;
    }

    .telechage-liste {
        background-color: black;
        color: white;
    }

    .liste-apprenant {
        color: white;
        background-color: #0e938a;
    }

    .header-nav {
        margin-top: 20px;
        width: 100%;
        display: flex;
        justify-content: space-around;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        padding: 4px;
    }

    .header-nav a {
        padding: 15px;
        text-decoration: none;
        color: black;
        font-size: 17px;

    }

    /* .tab.active {
        color: var(--orange);
        border-bottom-color: var(--orange);
    } */

    .apprenant-table {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: var(--orange);
    }

    th {
        padding: 1rem;
        text-align: left;
        color: var(--white);
        font-weight: 500;
        font-size: 0.875rem;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-gray);
        font-size: 0.875rem;
        color: #333;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .student-photo {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        object-fit: cover;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .ref-badge {
        background-color: var(--light-orange);
        color: var(--orange);
    }

    .ref-dev {
        background-color: #ecfdf5;
        color: #10b981;
    }

    .ref-data {
        background-color: #f5f3ff;
        color: #8b5cf6;
    }

    .ref-dig {
        background-color: #eff6ff;
        color: #3b82f6;
    }

    .ref-aws {
        background-color: #fffbeb;
        color: #f59e0b;
    }

    .ref-hack {
        background-color: #fdf2f8;
        color: #ec4899;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .active {
        background-color: #d1fae5;
        color: #047857;
    }

    .replaced {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .actions-cell {
        text-align: center;
    }

    .action-menu {
        background: none;
        border: none;
        color: var(--text-gray);
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        font-size: 0.875rem;
    }

    .pagination-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-left select {
        padding: 0.5rem;
        width: auto;
    }

    .pagination-info {
        color: var(--text-gray);
    }

    .pagination-controls {
        display: flex;
        gap: 0.25rem;
    }

    .page-arrow,
    .page-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.25rem;
        text-decoration: none;
        color: var(--text-gray);
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .page-number.active {
        background-color: var(--orange);
        color: var(--white);
    }

    .page-arrow.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    .no-data {
        padding: 2rem;
        text-align: center;
        color: var(--text-gray);
    }

    <?php function get_referentiel_code($id)
    {
        $referentiel = get_referentiel_by_id($id);
        if (!$referentiel)
            return 'dev';

        $title = strtolower($referentiel['titre'] ?? '');
        if (strpos($title, 'dev') !== false)
            return 'dev';
        if (strpos($title, 'data') !== false)
            return 'data';
        if (strpos($title, 'dig') !== false)
            return 'dig';
        if (strpos($title, 'aws') !== false)
            return 'aws';
        if (strpos($title, 'hack') !== false)
            return 'hack';
        return 'dev';
    }

    ?>
</style>