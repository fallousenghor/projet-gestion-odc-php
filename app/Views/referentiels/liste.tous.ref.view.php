<!DOCTYPE html>
<html lang="fr">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Référentiels</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header>
        <a href="?page=referentiel" class="retour">
            <i class='bx bx-left-arrow-alt'></i>
            Retour aux referentiels actifs
        </a>
        <h1>Tous les Référentiels</h1>
        <p>Liste complete des referentiels de la formation</p>
        <div class="cl">
            <div class="search-bar">
                <form method="get" action="">
                    <input type="hidden" name="page" value="referentiel">
                    <input type="hidden" name="action" value="<?= htmlspecialchars($_GET['action'] ?? 'liste-ref') ?>">
                    <button type="submit" class="search-button">
                        <i class='bx bx-search'></i>
                    </button>
                    <input type="text" class="search-inp" name="search" placeholder="Rechercher un référentiel..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <?php if (!empty($_GET['search'])): ?>
                        <a href="?page=referentiel&action=<?= htmlspecialchars($_GET['action'] ?? 'liste-ref') ?>"
                            class="clear-search" title="Effacer la recherche">
                            <!-- <i class='bx bx-x'></i> -->
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="buttons">

                <a href="?page=referentiel&action=new-ref">
                    <button class="add">
                        <i class='bx bx-plus'></i>
                        Creer un referentiel
                    </button>
                </a>
            </div>
        </div>
    </header>
    <main>

        <?php if (!empty($referentiels)): ?>
            <?php foreach ($referentiels as $ref): ?>

                <div class="card">
                    <?php if (!empty($ref['image'])): ?>
                        <img src="<?= htmlspecialchars($ref['image']) ?>" alt="<?= htmlspecialchars($ref['titre'] ?? '') ?>">
                    <?php endif; ?>

                    <h2><?= htmlspecialchars($ref['titre'] ?? 'Titre non disponible') ?></h2>
                    <p class="mod"><?= htmlspecialchars($ref['modules'] ?? 0) ?> modules</p>
                    <p><?= htmlspecialchars($ref['description'] ?? 'Description non disponible') ?></p>
                    <div class="horizontal"></div>
                    <div class="learners">

                        <span><?= htmlspecialchars($ref['apprenants_count'] ?? 0) ?> apprenant(s)</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-data">Aucun référentiel disponible pour le moment.</p>
        <?php endif; ?>

    </main>
    <div class="pagination-container">
        <div class="pagination-summary">Page <?= $currentPage ?> sur <?= $totalPages ?></div>
        <ul class="pagination">

            <li class="<?= $currentPage == 1 ? 'disabled' : '' ?>">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=referentiel&action=<?= $action ?>&page_num=<?= $currentPage - 1 ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>"
                        class="arrow">&laquo;</a>
                <?php else: ?>
                    <span class="arrow">&laquo;</span>
                <?php endif; ?>
            </li>

            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);

            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="<?= $i == $currentPage ? 'active' : '' ?>">
                    <a
                        href="?page=referentiel&action=<?= $action ?>&page_num=<?= $i ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>


            <li class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=referentiel&action=<?= $action ?>&page_num=<?= $currentPage + 1 ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>"
                        class="arrow">&raquo;</a>
                <?php else: ?>
                    <span class="arrow">&raquo;</span>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</body>

</html>


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

    body {
        font-family: Arial, sans-serif;
        background-color: var(--vert-claire);
        margin: 0;
        padding: 0;
    }

    a {
        text-decoration: none;
    }

    .search-bar {
        display: flex;
        align-items: center;

    }

    .search-bar .search-inp {
        font-size: 17px;
    }

    .learners span {
        font-size: 15px;
    }

    .search-button {
        background-color: white;
        border: none;

    }

    .retour {
        color: var(----text-grey);
        font-size: 14px;
        display: flex;
        justify-content: start;
        align-items: center;
        margin-top: -20px;
    }

    .horizontal {
        border-top: 2px solid var(--vert) !important;
        width: 20%;
        margin: 20px 0;
    }

    header {
        margin-left: auto;
        padding: 20px;
        width: 85%;
        margin-top: 100px;
    }

    header h1 {
        margin: 0;
        margin-top: 20px;
        font-size: 24px;
        color: var(--vert-text);
    }

    header p {
        margin: 10px 0;
        font-size: 16px;
        color: #666;
    }

    .cl {
        margin-top: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
    }

    .search-bar {
        flex: 1;
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        background-color: #fff;
        outline: none;
    }

    .search-bar i {
        margin-right: 10px;
        color: #666;
    }

    .search-bar input {
        flex: 1;
        border: none;
        padding: 10px;
        box-sizing: border-box;
        outline: none;
    }

    .buttons {
        display: flex;
        gap: 10px;
    }

    .buttons button {
        padding: 14px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .view-all {
        background-color: var(--orange);
        color: #fff;
        border-radius: 10px;
    }

    .add {
        background-color: var(--vert);
        color: #fff;
        border-radius: 10px;
    }

    main {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 20px;
        margin-left: auto;
        width: 85%;
        margin-top: 30px;
    }

    .card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 10px;
        padding: 10px;
        width: 20%;
        overflow-wrap: break-word;
        word-wrap: break-word;
        hyphens: auto;
        overflow: hidden;


    }

    .pagination-container {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        justify-content: center;

        margin-top: 50PX;

    }

    .pagination-summary {
        color: #555;
        width: 300px;

    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination a {
        text-decoration: none;
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
        color: var(---blanc);
        transition: all 0.3s;
    }

    .pagination a:hover {
        background-color: var(--vert);
        color: #fff;
    }

    .pagination .active a {
        margin-top: -7px;
        background-color: var(--orange);
        color: #fff;
        border-color: var(--orange);
    }

    .pagination .disabled a {
        color: #ccc;
        pointer-events: none;
        border-color: #ddd;
    }

    .arrow {
        font-weight: bold;
    }




    .mod {
        color: black !important;
        font-size: 18px !important;
        font-weight: bold !important;
    }

    p {
        font-size: 17px !important;
    }

    .card img {
        width: 100%;
        height: 250px;
        border-radius: 8px;
    }

    .card h2 {
        margin: 10px 0;
        font-size: 18px;
        color: var(--vert-text);
    }

    .card p {
        margin: 10px 0;
        font-size: 14px;
        color: #666;
    }

    .learners {
        margin-top: 10px;
        font-size: 12px;
        color: #999;
    }

    @media (max-width: 768px) {
        .card {
            width: 100%;
        }

        .cl {
            flex-direction: column;
            align-items: stretch;
        }

        .search-bar {
            width: 100%;
        }

        .buttons {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>