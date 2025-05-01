<!DOCTYPE html>
<html lang="en">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Détails de l'apprenant</title>
</head>

<body>
    <div class="retour">
        <a href="?page=apprenant">
            <i class='bx bx-left-arrow-alt'></i>
            Retour sur la liste
        </a>
    </div>
    <div class="container-da">
        <?php if (isset($apprenant)): ?>
            <div class="left">
                <div class="profil">
                    <img src="./assets/images/<?= htmlspecialchars($apprenant['photo'] ?? 'fallou.jpg') ?>"
                        alt="Photo de l'apprenant">
                </div>
                <div class="profil-d">
                    <h1><?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?></h1>
                    <h3><?= htmlspecialchars($referentiel['titre'] ?? 'Aucun référentiel') ?></h3>
                    <h5><?= htmlspecialchars($promotion['titre'] ?? 'Aucune promotion') ?></h5>
                    <h5><?= htmlspecialchars($apprenant['status']) ?></h5>
                </div>
                <div class="email">
                    <p><i class='bx bx-envelope'></i> Email : <?= htmlspecialchars($apprenant['email']) ?></p>
                    <p><i class='bx bx-phone-call'></i> Téléphone : <?= htmlspecialchars($apprenant['telephone']) ?></p>
                    <p><i class='bx bx-location-plus'></i> Adresse : <?= htmlspecialchars($apprenant['adresse']) ?></p>
                </div>
            </div>
            <div class="right">
                <div class="presence">
                    <div class="card-pre">
                        <i class='bx bx-check-double'></i>
                        <div class="text-p">
                            <h4><?= htmlspecialchars($apprenant['presences'] ?? 0) ?></h4>
                            <p>Présences</p>
                        </div>
                    </div>
                    <div class="card-pre">
                        <i class='bx bx-stopwatch retard'></i>
                        <div class="text-p rd">
                            <h4><?= htmlspecialchars($apprenant['retards'] ?? 0) ?></h4>
                            <p>Retards</p>
                        </div>
                    </div>
                    <div class="card-pre">
                        <i class='bx bx-error abs'></i>
                        <div class="text-p abse">
                            <h4><?= htmlspecialchars($apprenant['absences'] ?? 0) ?></h4>
                            <p>Absences</p>
                        </div>
                    </div>
                </div>
                <div class="pro-mod">
                    <a href="?page=apprenant&action=programmes&id=<?= htmlspecialchars($apprenant['id']) ?>"
                        class="program">Programmes et modules</a>
                    <a href="?page=apprenant&action=absences&id=<?= htmlspecialchars($apprenant['id']) ?>"
                        class="to-ab">Total absence par étudiant</a>
                </div>
                <div class="cour">
                    <?php foreach ($apprenant['cours'] ?? [] as $cours): ?>
                        <div class="card-cour">
                            <div class="heure">
                                <span>
                                    <i class='bx bx-stopwatch'></i> <?= htmlspecialchars($cours['duree']) ?> jours
                                </span>
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </div>
                            <div class="matiere">
                                <h2><?= htmlspecialchars($cours['titre']) ?></h2>
                                <p><?= htmlspecialchars($cours['description']) ?></p>
                                <span><?= htmlspecialchars($cours['status']) ?></span>
                            </div>
                            <div class="date">
                                <p>
                                    <i class='bx bx-calendar'></i> <?= htmlspecialchars($cours['date']) ?>
                                </p>
                                <p>
                                    <i class='bx bx-stopwatch'></i> <?= htmlspecialchars($cours['heure']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Aucun apprenant trouvé.</p>
        <?php endif; ?>
    </div>
</body>

</html>



<style>
    .heure {
        display: flex;
        justify-content: space-around;
        margin-top: 10px;
        align-items: center;

    }

    .date {
        padding: 8px;
        background-color: var(--bg-grey);
        margin-top: 20%;
        border-radius: 10px;
        display: flex;
        justify-content: space-around;
        align-items: center;

    }

    .heure span {
        padding: 1px;
        background-color: black;
        color: white;
        border-radius: 5px;
        display: flex;
        align-items: center;
    }

    .matiere {
        display: flex;
        flex-direction: column;
        align-items: center;
        line-height: 1px;
        /* margin-top: 10px; */
        margin-top: 25px;

    }

    .matiere span {
        background-color: rgb(122, 201, 154) !important;
        padding: 10px;
        border-radius: 10px;
        color: white !important;
        margin-right: auto;
        margin-left: 35px;
    }

    .cour {
        display: flex;
        margin-top: 15px;
        justify-content: space-around;

    }

    .card-cour {
        width: 32%;
        min-height: 210px;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .pro-mod {
        height: 8%;
        background-color: white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-top: 15px;
        display: flex;
        justify-content: space-around;
        align-items: center;

    }

    .to-ab {
        width: 50%;
        color: black;
        height: 100%;
        margin-right: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .program {
        width: 50%;
        background-color: #ff9501;
        height: 100%;
        margin-right: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        border-radius: 15px;
        border-bottom: 4px solid black;
    }

    .pro-mod a {
        text-decoration: none;
        font-size: 22px;
        font-weight: bold;
    }

    .container-da {
        width: 84%;
        margin-left: auto;
        display: flex;
        height: 84vh;
        margin-top: 20px;
        gap: 10px;

    }

    .presence {
        display: flex;
        justify-content: space-around;
    }

    .abs {
        background-color: #fde2e4 !important;
        color: #b55357 !important;
    }

    .abse {
        color: #b55357 !important;
    }

    .retard {
        background-color: rgb(238, 193, 44) !important;
        color: #fa7214 !important;

    }

    .rd {
        color: #fa7214 !important;

    }

    .card-pre {
        width: 32%;
        height: 170px;
        background-color: white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;

    }

    .card-pre .text-p {
        margin-left: 15px;
        color: #0e938a;
        line-height: 2px;
    }

    .card-pre .text-p h4 {
        font-size: 25px;
    }

    .card-pre i {
        padding: 15px;
        background-color: #b8e2c9;
        color: #0e938a;
        border-radius: 100%;
        margin-left: 20px;
    }

    /* a {
        margin-top: 10%;
        color: black;

    } */

    .retour {

        height: 2%;
        margin-top: 4.5%;
        display: flex;
        justify-content: start;
        width: 85%;
        margin-left: auto;
        align-items: center;

    }

    .retour a {
        color: black;
        text-decoration: none;

    }

    .left {
        width: 20%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        height: 100%;
        background-color: white;
        display: flex;
        /* align-items: center; */
        flex-direction: column;
    }


    .right {
        width: 78%;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);

    }

    .profil {
        height: 150px;
        width: 150px;
        border-radius: 100%;
        border: 4px solid #0e938a;
        margin: 10px auto;
    }

    .profil img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
    }

    .profil-d {
        display: flex;
        flex-direction: column;
        align-items: center;

    }

    .email {
        margin-left: 15px;
    }

    h1 {
        color: black;

    }

    h3 {
        margin-top: -7px;
        background-color: #37a39c;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    h5 {
        margin-top: -10px;
        background-color: #b8e2c9;
        padding: 5px;
        border-radius: 5px;
        color: white;
        font-size: 14px;
    }

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
</style>