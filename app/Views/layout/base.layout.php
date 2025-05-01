<?php
require_once __DIR__ . '/../../translate/fr/message.fr.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="main">
            <div class="navbar">
                <div class="search">
                    <!-- <button><i class='bx bx-search'></i></button> -->
                    <input type="text" placeholder="<?= MessagesInterface::RECHERCHER_PLACEHOLDER->value ?>">
                </div>
                <div class="info-admin">
                    <h2><a href="#"><i class='bx bx-bell'></i></a><span class="badge"></span></h2>
                    <?php
                    if (function_exists('isAuthenticated') && isAuthenticated()) {
                        $user = getUserSession();
                        $email = $user['email'] ?? MessagesInterface::EMAIL_NON_DEFINI->value;
                        $role = $user['role'] ?? MessagesInterface::ROLE_PAR_DEFAUT->value;
                        $firstLetter = !empty($email) ? strtoupper(substr($email, 0, 1)) : '?';
                        ?>
                        <span class="user-circle"><?= htmlspecialchars($firstLetter); ?></span>
                        <h4><a href="#" class="e-admin"><?= htmlspecialchars($email); ?>
                                <small><?= htmlspecialchars($role); ?></small></a></h4>
                        <?php
                    } else {
                        echo "<p>" . MessagesInterface::UTILISATEUR_NON_CONNECTE->value . "</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="content">
                <!-- Contenu principal -->
            </div>
        </div>

        <div class="sidebar">
            <div class="side-nav">
                <div class="siderbar-entente">
                    <a href="#">
                        <img src="./assets/images/logo-odc.webp" alt="logo-odc">
                    </a>
                    <h5 class="promo">
                        <?php if (!empty($activePromo)): ?>
                            <div class="tag"><?= htmlspecialchars($activePromo['titre']) ?> </div>
                        <?php else: ?>
                            <p class="font-size:10px;">promo!</p>
                        <?php endif; ?>

                    </h5>
                    <div class="bordure"></div>
                </div>
                <div class="siderbar-corps">
                    <a href="#">
                        <i class='bx bx-grid-alt'></i>
                        <?= MessagesInterface::TABLEAU_DE_BORD->value ?>
                    </a>
                    <a href="?page=promotions">
                        <i class='bx bxs-folder-minus'></i>
                        <?= MessagesInterface::PROMOTIONS->value ?>
                    </a>
                    <a href="?page=referentiel">
                        <i class='bx bxs-folder-minus'></i>
                        <?= MessagesInterface::REFERENTIELS->value ?>
                    </a>
                    <a href="?page=apprenant">
                        <i class='bx bx-group'></i>
                        <?= MessagesInterface::APPRENANTS->value ?>
                    </a>
                    <a href="#">
                        <i class='bx bxs-folder-minus'></i>
                        <?= MessagesInterface::GESTION_PRESENCES->value ?>
                    </a>
                    <a href="#">
                        <i class='bx bxs-folder-minus'></i>
                        <?= MessagesInterface::KITS_LAPTOPS->value ?>
                    </a>
                    <a href="#">
                        <i class='bx bx-signal-4'></i>
                        <?= MessagesInterface::RAPPORTS_STATS->value ?>
                    </a>
                </div>
            </div>

            <div class="siderbar-footer">
                <a href="?page=logout">
                    <button>
                        <i class='bx bx-log-out bx-rotate-180'></i>
                        <h4><?= MessagesInterface::DECONNEXION->value ?></h4>
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>