<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <title>Détail Apprenant</title>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <img src="./assets/images/logo-odc.webp" alt="">
            <i class='bx bx-menu'></i>
        </div>
        <div class="contenu">
            <div class="tableau-bord">
                <h1>Tableau de bord</h1>
            </div>
            <div class="info">
                <div class="left">
                    <?php if (isset($apprenant)): ?>
                        <div class="info-perso">
                            <img src="./assets/images/fallou.jpg" alt="">
                            <i class='bx bxs-graduation bib'></i>
                            <div class="nom-ref">
                                <h2><?php echo htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']); ?></h2>
                                <span><?= htmlspecialchars(get_referentiel_by_id($apprenant['referentiel_id'])['titre'] ?? 'N/A') ?></span>
                                <div class="e">
                                    <i class='bx bx-envelope'></i>
                                    <p><?php echo htmlspecialchars($apprenant['email']); ?></p>
                                </div>
                            </div>
                            <div class="id-carde">
                                <i class='bx bx-id-card'></i>
                                <p><?php echo htmlspecialchars($apprenant['matricule']); ?></p>
                            </div>
                        </div>
                        <div class="card-info">
                            <div class="card-un">
                                <div class="presen">
                                    <i class='bx bx-calendar-alt'></i>
                                    <p>Presences</p>
                                </div>
                                <div class="stat">
                                    <p class="pr">40 </br> present</p>
                                    <p class="re">0 </br> retart</p>
                                    <p class="ab">0 </br> absent</p>
                                </div>
                            </div>
                            <div class="card-de">
                                <div class="repartition">
                                    <i class='bx bx-stopwatch'></i>
                                    <p>Repartition</p>
                                </div>
                                <div class="diagram"></div>
                                <div class="legende">
                                    <p class="prs"><span></span> Presents</p>
                                    <p class="ret"><span></span> Presents</p>
                                    <p class="abs"><span></span> Presents</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Apprenant non trouvé.</p>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <?php if (isset($apprenant)): ?>
                        <div class="code-qr">
                            <i class='bx bx-barcode-reader'></i>
                            <h3>Scanner pour la presence</h3>
                            <div class="qr" id="qrcode"></div>
                            <p>Code de presence personnel</p>
                            <h5><?php echo htmlspecialchars($apprenant['matricule']); ?></h5>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var apprenantInfo =
                                    `Nom: ${<?php echo json_encode($apprenant['prenom'] . ' ' . $apprenant['nom']); ?>}\n` +
                                    `Email: ${<?php echo json_encode($apprenant['email']); ?>}\n` +
                                    `Matricule: ${<?php echo json_encode($apprenant['matricule']); ?>}\n` +
                                    `Téléphone: ${<?php echo json_encode($apprenant['telephone']); ?>}`;

                                var qrcode = new QRCode(document.getElementById("qrcode"), {
                                    text: apprenantInfo,
                                    width: 300,
                                    height: 300,
                                    colorDark: "#000000",
                                    colorLight: "#ffffff",
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            });
                        </script>
                    <?php endif; ?>
                </div>


            </div>
        </div>
        <div class="tableau-presence">
            <div class="tab-titre">
                <i class='bx bx-stopwatch'></i>
                <h3>Historique des presences</h3>
            </div>
            <form action="">
                <input type="text" name="" id="" placeholder="Rechercher...">
            </form>
            <select name="p" id="">
                <option value="p">Tous les status</option>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12/11/2025</td>
                        <td>Present</td>
                    </tr>
                    <tr>
                        <td>12/11/2025</td>
                        <td>Present</td>
                    </tr>
                    <tr>
                        <td>12/11/2025</td>
                        <td>Present</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>





<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    tr {
        margin-bottom: 0.5px solid black;
        height: 17px;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    td {
        padding: 10px;
    }

    table {
        margin-top: 15px;
        width: 100%;
        text-align: center;
        margin-bottom: 20px;
    }

    thead {
        margin-bottom: 0.3px solid black;
    }

    select {
        width: 45%;
        padding: 12px;
        outline: none;
        border-radius: 10px;
        margin-right: auto;
        margin-top: 10px;
        margin-left: 20px;
    }

    form {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    input {
        width: 80%;
        padding: 12px;
        outline: none;
        border-radius: 10px;
    }

    .tab-titre {
        display: flex;
        justify-content: space-around;
        align-items: center;
        font-size: 17px;
    }

    .tab-titre i {
        font-size: 25px;
        padding: 5px;
        background-color: #f97316;
        color: white;
        border-radius: 5px;
        margin-right: 10px;
    }

    .tableau-presence {
        min-height: 200px;
        width: 100%;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-top: 15px;
        border-radius: 15px;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .right {
        border-radius: 15px;
    }

    .legende {
        margin-top: 10px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        gap: 30px;
    }

    .legende p {
        margin-left: 5px;
        word-spacing: 5px;
    }

    .legende span {
        padding-top: 1px;
        padding-right: 15px;
        border-radius: 5px;

        margin-left: -5px;

    }

    .legende .abs {
        color: #c9450c;

    }

    .legende .abs span {
        background-color: #c9450c;

    }

    .legende .ret {
        color: #f97316;

    }

    .legende .ret span {
        background-color: #f97316;

    }

    .legende .prs {
        color: #10b981;

    }

    .legende .prs span {
        background-color: #10b981;

    }

    .card-de {
        display: flex;
        flex-direction: column;
        color: black !important;
        align-items: center;
    }

    .diagram {
        width: 150px;
        height: 150px;
        border-radius: 100%;
        border: 25px solid #10b981;
    }

    .card-de .repartition {
        margin-right: auto;
    }

    .repartition {
        display: flex;
        align-items: center;
        margin-left: 15px;
        gap: 10px;
        font-size: 22px;
    }

    .repartition i {
        font-size: 27px;
        padding: 5px;
        background-color: #f97316;
        color: white;
        border-radius: 10px;
    }

    .presen {
        display: flex;
        color: black;
        align-items: center;
        gap: 10px;
        font-size: 19px;
        margin-left: 12px;

    }

    .stat .pr {
        background-color: #ecfdf5;
        color: #059d9a;
    }

    .stat .re {
        background-color: #fff7ed;
        color: #f97316;
        font-size: 21px;
    }

    .stat .ab {
        background-color: #fef2f2;
        color: #f15244;

    }

    .presen i {
        padding: 6px;
        background-color: #f97316;
        border-radius: 10px;
        font-size: 25px;
        color: white;
    }

    .stat {
        display: flex;
        justify-content: space-around;
    }

    .stat p {
        padding: 10px;
        border-radius: 15px;
        height: 50px;
        background-color: #f97316;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .container {
        width: 1300;
        height: 100vh;
    }

    .qr {
        min-height: 310px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: white;
        border: 0.5px solid #f97316;
        width: 80%;
        margin-bottom: 20px;
    }

    .qr:hover {
        transform: scale(1.01);
    }

    .code-qr {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* min-height: 500px; */
        border-radius: 15px;

    }

    .nom-ref {
        line-height: 10px;
        text-align: center;

    }

    .e {

        display: flex;
        margin-top: 20px;
        align-items: center;
        padding: 2px;
        border-radius: 5px;
        background-color: #f9fafb;
        /* justify-content: center; */
        width: 300px;

    }

    .id-carde {
        border-radius: 15px;
        display: flex;
        margin-top: 10px;
        align-items: center;
        padding: 2px;
        border-radius: 5px;
        background-color: #f9fafb;
        /* justify-content: center; */
        width: 80%;
        margin-bottom: 10px;
        text-align: start;
    }

    .e i {

        margin-right: 10px;
        color: #f97316;
        font-size: 22px;
    }

    .id-carde i {

        margin-right: 10px;
        color: #f97316;
        font-size: 22px;
    }

    .info-perso .bib {
        position: absolute;
        font-size: 40px;
        /* background-color: #f97316; */
        margin-top: -110px;
        margin-left: 50px;
        color: #f97316;

        border-radius: 15px;

    }

    .nom-ref span {
        color: darkorange;
    }



    .code-qr i {
        font-size: 40px;
        margin-top: 15px;
        color: orange;
    }

    .code-qr p {
        font-size: 20px;
        color: orange;
    }

    .code-qr h5 {
        margin-top: -10px;
        font-size: 19px;
    }

    .navbar {
        height: 10%;
        width: 100%;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tableau-bord h1 {
        text-align: center;
    }


    .navbar img {
        height: 100%;

    }

    .navbar i {
        font-size: 30px;
        color: darkgray;
    }


    .info-perso img {
        height: 90px;
        width: 80px;
        padding: 25px;
        border-radius: 25px;
    }

    .info-perso {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        position: relative;
        border-radius: 15px;
    }

    .contenu {
        width: 100%;
        display: flex;
        flex-direction: column;
        margin-top: 15px;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
    }

    .tableau-bord {
        width: 90%;
        padding: 0.60%;
        background-color: #c9450c;
        ;
        border-radius: 15px;
        color: white;
    }

    .code-qr {
        width: 100;
        min-height: 500px;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-top: 10px;
    }

    .info {
        margin-top: 10px;
        width: 60%;
        display: flex;
        display: flex;
        flex-wrap: wrap;
    }

    .left {
        width: 70%;

    }

    .right {
        width: 30%;

    }

    .card-info {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 5px;
        margin-top: 15px;
        border-radius: 15px;
    }

    .card-un {
        width: 49%;
        color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        min-height: 200px;
    }

    .card-de {
        width: 49%;
        color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        min-height: 200px;
    }

    @media only screen and (max-width: 600px) {
        body {
            background-color: white;
        }



        .info {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        .left,
        .right {
            width: 100%;
        }

        .left {
            flex-direction: column-reverse;
            margin-top: 10px;
        }

        .right {
            order: -1;
        }

        .card-info {

            display: flex;
            flex-wrap: wrap;
        }

        .card-un {
            width: 100%;


        }

        .card-de {
            width: 100%;
        }

        .info-perso {
            display: flex;
            flex-direction: column;
        }
    }
</style>