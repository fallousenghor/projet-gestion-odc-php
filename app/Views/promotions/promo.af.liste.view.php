<!DOCTYPE html>
<html lang="fr">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Promotions</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <div class="pliste">
            <h1>Promotion</h1>
            <span><?= $stats['apprenants'] ?? 0 ?> apprenants</span>
        </div>

        <div class="input-pliste">
            <div class="right-input">
                <form method="get" action="" class="search search-b">
                    <input type="hidden" name="page" value="promotions">
                    <input type="hidden" name="action" value="promo-liste">
                    <div class="search-box">

                        <input type="text" class="search-inp" name="search"
                            placeholder="<?= TextPromo::RECHERCHER_PROMO->value ?>"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <select name="titre" id="filtre-ref" class="styled-select select-ref">
                        <option value="" ?>
                            Filtrer par referentiel
                        </option>
                        <?php $referentiels = get_all_ref(); ?>
                        <?php foreach ($referentiels as $ref): ?>
                            <option value="titre" <?= ($_GET['titre'] ?? '') === 'titre' ? 'selected' : '' ?>>

                                <div class="x">

                                    <div class="r">
                                        <?= htmlspecialchars($ref['titre']) ?>
                                    </div>

                                </div>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name=" status" id="filtre-statut" class="styled-select">
                        <option value="all" <?= ($_GET['status'] ?? 'all') === 'all' ? 'selected' : '' ?>>
                            Filtrer par statut
                        </option>
                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                            <?= TextPromo::ACTIF->value ?>
                        </option>
                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                            <?= TextPromo::INACTIF->value ?>
                        </option>
                    </select>
                    <button type="submit" class="search-button">
                        <i class='bx bx-search'></i>
                    </button>
                </form>
            </div>
            <div class="left-input">
                <a href="?page=promotions&action=add-promo">
                    <button>
                        <i class='bx bx-user-plus'></i>
                        Ajouter une promotion
                    </button>
                </a>
            </div>
        </div>

        <div class="card-stat-pliste">
            <?php require 'promo.stats.view.php'; ?>
        </div>
        <div class="tab-plidte">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Promotion</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Référentiel</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($activePromo): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($activePromo['image'] ?? './assets/images/dev.jpeg') ?>"
                                    alt=" <?= htmlspecialchars($activePromo['titre'] ?? 'Promo') ?>">
                            </td>
                            <td>
                                <?= htmlspecialchars($activePromo['titre'] ?? 'Promotion 2025') ?>
                            </td>
                            <td><?= !empty($activePromo['date_debut']) ? date('d/m/Y', strtotime($activePromo['date_debut'])) : '01/02/2025' ?>
                            </td>
                            <td><?= !empty($activePromo['date_fin']) ? date('d/m/Y', strtotime($activePromo['date_fin'])) : '01/02/2025' ?>
                            </td>
                            <td class="referentiel">
                                <?php foreach ($activePromo['referentiels_titles'] ?? [] as $title):
                                    $refClass = strtolower(str_replace([' ', '/'], '', $title));
                                    ?>
                                    <span class="<?= htmlspecialchars($refClass) ?>">
                                        <?= htmlspecialchars($title) ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td class="status">
                                <span class="status-dot active-dot"></span>
                                <span class="actives">Actif</span>
                            </td>
                            <td class="actions">⋮</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($promotions as $promo):

                        if (isset($activePromo) && $promo['id'] === $activePromo['id'])
                            continue;
                        ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($promo['image'] ?? './assets/images/dev.jpeg') ?>"
                                    alt="<?= htmlspecialchars($promo['titre'] ?? 'Promo') ?>">
                            </td>
                            <td><?= htmlspecialchars($promo['titre'] ?? 'Promotion 2025') ?></td>
                            <td><?= !empty($promo['date_debut']) ? date('d/m/Y', strtotime($promo['date_debut'])) : '01/02/2025' ?>
                            </td>
                            <td><?= !empty($promo['date_fin']) ? date('d/m/Y', strtotime($promo['date_fin'])) : '01/02/2025' ?>
                            </td>
                            <td class="referentiel">
                                <?php foreach ($promo['referentiels_titles'] ?? [] as $title):
                                    $refClass = strtolower(str_replace([' ', '/'], '', $title));
                                    ?>
                                    <span class="<?= htmlspecialchars($refClass) ?>">
                                        <?= htmlspecialchars($title) ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td class="status">
                                <span
                                    class="status-dot <?= ($promo['statut'] ?? 'Inactive') === 'Actif' ? 'active-dot' : 'inactive-dot' ?>"></span>
                                <span class="<?= ($promo['statut'] ?? 'Inactive') === 'Actif' ? 'actives' : 'inactives' ?>">
                                    <?= htmlspecialchars($promo['statut'] ?? 'Inactive') ?>
                                </span>
                            </td>
                            <td class="actions">⋮</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            <div class="pagination-summary">Page <?= $currentPage ?> sur <?= $totalPages ?></div>
            <ul class="pagination">
                <li class="<?= $currentPage == 1 ? 'disabled' : '' ?>">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=promotions&action=promo-liste&page_num=<?= $currentPage - 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
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
                            href="?page=promotions&action=promo-liste&page_num=<?= $i ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=promotions&action=promo-liste&page_num=<?= $currentPage + 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                            class="arrow">&raquo;</a>
                    <?php else: ?>
                        <span class="arrow">&raquo;</span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
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

    .r {
        display: flex;
    }

    form {
        display: flex;
    }

    form select {
        margin-left: 15%;
    }

    * {
        box-sizing: border-box;
    }

    .select-ref {
        margin-left: 30%;
    }

    select {
        padding: 15px;
        height: 47px;
        /* margin-right: 50px; */
    }

    .search-b button {
        position: absolute;
        margin-left: 45%;
        /* margin-top: -35px; */
        height: 45px;
        width: 100px;
        background-color: #37a39c;
        border-radius: 5px;
        border: none !important;
        outline: none;

    }

    .pagination-container {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        justify-content: space-between;
        align-items: center;

    }

    .search-box input {
        padding: 15px;
        border-radius: 5px;
        outline: none;
    }

    .pagination-summary {
        color: #555;
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


    .container {

        /* z-index: -1; */
        margin-top: 50px;
        width: 85%;
        margin-left: auto;
        display: flex;
        flex-direction: column;
    }

    .card-stat-pliste {
        width: 100%;
    }

    .pliste {
        display: flex;
        align-items: center;
    }

    .pliste h1 {
        color: var(--vert-text);
    }

    .pliste span {
        height: 40px;
        padding: 8px;
        background-color: var(--semi-orange);
        border-radius: 50px;
        color: var(--orange);
        margin-left: 10px;
        font-weight: 900;
    }

    .input-pliste {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }

    .right-input {
        display: flex;
        gap: 10px;
        flex: 1;
    }

    .left-input {
        display: flex;
        align-items: center;
    }

    .search-input,
    select {
        padding: 5px;
        border-radius: 7px;
        outline: none;
        font-size: 15px;
    }

    .left-input button {
        padding: 5px 10px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--vert);
        color: white;
        border: none;
        cursor: pointer;
    }

    .tab-plidte {
        background: var(--blanc);
        padding: 30px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th,
    td {
        text-align: left;
        padding: 15px;
        vertical-align: middle;
    }

    thead {
        background-color: var(--orange);
        color: white;
    }

    th:first-child,
    td:first-child {
        border-top-left-radius: 10px;
    }

    th:last-child,
    td:last-child {
        border-top-right-radius: 10px;
    }

    td img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .referentiel span {
        margin-right: 8px;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
    }

    .devweb {
        background-color: var(--bg-grey);
        color: var(--vert-text);
    }

    .refdig,
    .devdata {
        background-color: var(--vert-claire);
        color: var(--vert-text);
    }

    .aws {
        background-color: var(--semi-orange);
        color: var(--orange);
    }

    .hackeuse {
        background-color: var(--semi-rouge);
        color: var(--rouge-text);
    }

    .status {
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;

    }

    .active-dot .actives {
        color: green !important;
    }

    .inactive {
        color: var(--rouge-text);
    }

    .status .active-dot {
        background: green !important;
    }

    .status .inactive-dot {
        background: var(--rouge-text);
    }

    .actions {
        font-size: 24px;
        cursor: pointer;
        text-align: center;
    }
</style>
</body>

</html>