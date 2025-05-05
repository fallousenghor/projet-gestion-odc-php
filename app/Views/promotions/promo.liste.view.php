<!DOCTYPE html>
<html lang="en">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="./assets/css/promo.css">
    <title><?= TextPromo::PROMOTION->value ?></title>
</head>

<body>
    <div class="containe">
        <div class="heade">
            <div class="text">
                <h1><?= TextPromo::PROMOTION->value ?></h1>
                <p><?= TextPromo::GERER_PROMOTIONS->value ?></p>
            </div>
            <div class="input-ajout">
                <button>
                    <a href="?page=promotions&action=add-promo">
                        <i class='bx bx-plus'></i>
                        <?= TextPromo::AJOUTER_PROMO->value ?>
                    </a>
                </button>
            </div>
        </div>
        <div class="cards">
            <div class="card">
                <div class="chiffre">
                    <h2><?= $totalApprenants ?></h2>
                    <span class="text-card"><?= TextPromo::APPRENANTS->value ?></span>
                </div>
                <div class="icon">
                    <i class='bx bx-group'></i>
                </div>
            </div>

            <div class="card">
                <div class="chiffre">
                    <h2><?= $stats['referentiels'] ?></h2>
                    <span class="text-card"><?= TextPromo::REFERENTIELS->value ?></span>
                </div>
                <div class="icon">
                    <i class='bx bx-book-alt'></i>
                </div>
            </div>

            <div class="card">
                <div class="chiffre">
                    <h2><?= $stats['promotions_actives'] ?></h2>
                    <span class="text-card"><?= TextPromo::PROMOTIONS_ACTIVES->value ?></span>
                </div>
                <div class="icon">
                    <i class='bx bx-check'></i>
                </div>
            </div>

            <div class="card">
                <div class="chiffre">
                    <h2><?= $stats['total_promotions'] ?></h2>
                    <span class="text-card"><?= TextPromo::TOTAL_PROMOTIONS->value ?></span>
                </div>
                <div class="icon">
                    <i class='bx bx-folder'></i>
                </div>
            </div>
        </div>
        <div class="bars">
            <form method="get" action="" class="search-form">
                <input type="hidden" name="page" value="promotions">
                <input type="hidden" name="action" value="liste-promo">

                <div class="form-container">
                    <div class="search-box">
                        <input type="text" class="search-inp" name="search"
                            placeholder="<?= TextPromo::RECHERCHER_PROMO->value ?>"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                    </div>

                    <select name="status" class="styled-select <?= $isSearch ? 'disabled-select' : '' ?>">
                        <option value="all" <?= ($_GET['status'] ?? 'all') === 'all' ? 'selected' : '' ?>>
                            <?= TextPromo::TOUS->value ?>
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
                </div>
            </form>

            <button class="grille"><?= TextPromo::GRILLE->value ?></button>
            <a href="?page=promotions&action=promo-liste">
                <button class="liste"><?= TextPromo::LISTE->value ?></button>
            </a>
        </div>

        <div class="promotion">
            <?php foreach ($promotions as $promo): ?>
                <div class="promotion-card">
                    <div class="active">
                        <div class="pc-active <?= $promo['statut'] === 'Actif' ? 'active' : '' ?>">
                            <h6 id="promoButton">
                                <?= $promo['statut'] === 'Actif' ? TextPromo::ACTIF->value : TextPromo::INACTIF->value ?>
                            </h6>
                            <?php if ($promo['statut'] !== 'Actif'): ?>
                                <a href="?page=promotions&action=toggle-promo&id=<?= $promo['id'] ?>">
                                    <i class='bx bx-power-off'></i>
                                </a>
                            <?php else: ?>
                                <span class="disabled-button">
                                    <i class='bx bx-power-off'></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="logo-promo">
                        <div class="logo">
                            <img src="<?= htmlspecialchars($promo['image']) ?>" alt="">
                        </div>
                        <div class="text">
                            <h3><?= htmlspecialchars($promo['titre']) ?></h3>
                            <p>
                                <i class='bx bx-calendar-alt'></i>
                                <?= htmlspecialchars($promo['date_debut']) ?> - <?= htmlspecialchars($promo['date_fin']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="aprenant">
                        <?= htmlspecialchars($promo['apprenants_count'] ?? 0) ?>
                        <span class="apprenant-text"><?= htmlspecialchars(TextPromo::APPRENANTS->value) ?></span>
                    </div>

                    <div class="line"></div>
                    <div class="voir-plus">
                        <a href="#"><?= TextPromo::VOIR_PLUS->value ?></a>
                        <i class='bx bx-chevron-right'></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="pagination-container">
        <div class="pagination-summary">Page <?= $currentPage ?> sur <?= $totalPages ?></div>
        <ul class="pagination">
            <li class="<?= $currentPage == 1 ? 'disabled' : '' ?>">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=promotions&action=liste-promo&page_num=<?= $currentPage - 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
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
                        href="?page=promotions&action=liste-promo&page_num=<?= $i ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=promotions&action=liste-promo&page_num=<?= $currentPage + 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
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
    /* body {
        overflow: hidden;
    }

    .promotion {
        overflow-y: auto;
    } */

    .search-form {
        width: 100%;
    }

    .form-container {
        display: flex;
        gap: 15px;
        width: 50%;
        align-items: center;
        margin-left: 30px;
    }

    .grille {
        margin-right: 50px;
    }

    .search-box {
        display: flex;
        flex-grow: 1;

        position: relative;
    }

    .search-box button {
        width: 60px;

    }

    .search-inp {
        width: 30%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .search-button {
        position: absolute;

        /* height: 80%; */
        background: none;
        border: none;
        padding: 0 10px;
        cursor: pointer;
        color: white;
        font-size: 17px;
        width: 120px;
        border: 1px solid #ddd;
        height: 50px;
        margin-left: 51%;
        background-color: #0e938a;
        border-radius: 4px;
    }

    .styled-select {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
        min-width: 150px;
        margin-left: 39%;
        height: 50px;
    }


    .disabled-select {
        opacity: 0.7;
        cursor: not-allowed;
    }


    .bx-search {
        font-size: 18px;
    }


    .search-box {
        height: 23px;
        display: flex;

        align-items: center;
    }

    .disabled-select {
        background-color: #ccc;
        cursor: not-allowed;
        pointer-events: none;
        color: #666;
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

    .disabled-button {
        cursor: not-allowed;
        opacity: 0.5;
        pointer-events: none;
    }

    .pc-active.active .bx-power-off {
        background-color: rgb(205, 133, 138);

    }



    .pc-active.active #promoButton {
        background-color: rgb(5, 83, 16);

    }

    .search-box button {

        background-color: transparent;
        border: none;
    }

    .search-box input {
        width: 100%;
        padding: 14px;
        background-color: transparent !;
        font-size: 17px;
        border: none;
        outline: none;
        color: #949698;
        offset: none;
        /* <img src="?= htmlspecialchars($promo['image']) ?>" alt=""> */
    }
</style>