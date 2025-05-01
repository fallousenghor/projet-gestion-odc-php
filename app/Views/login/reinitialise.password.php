<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
</head>

<body>
    <div class="container">
        <div class="logo-odc">
            <img src="./assets/images/logo-odc.webp" alt="logo sonatel">
        </div>

        <div class="welcome">
            <h5>Bienvenue sur</h5>
            <h5 class="nom-odc">Ecole du code Sonatel Academy</h5>
        </div>

        <div class="seConnecter">
            <h2>Réinitialiser le mot de passe</h2>
            <p>Pour <?= htmlspecialchars($_SESSION['reset_email'] ?? '') ?></p>
        </div>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="pw">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Nouveau mot de passe" required>
            </div>

            <div class="bc">
                <input class="Connecter" type="submit" name="reset" value="Réinitialiser">
            </div>
        </form>
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

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        position: relative;
        height: 100vh;
        width: 100vw;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: auto;
    }

    .container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 30px;
        height: 77%;
        width: 28%;
        margin: auto;
        background-color: var(--blanc);
        border-radius: 20px 20px 20px 20px;
        overflow: visible;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .container::after {
        content: "";
        position: absolute;
        top: 5px;
        left: 0;
        right: -10px;
        bottom: 0;
        background-color: var(--orange);
        background-position: center;
        border-radius: 20px 20px 20px 20px;
        z-index: -1;
    }

    .container::before {
        content: "";
        position: absolute;
        top: 10px;
        left: -1px;
        right: 8px;
        bottom: -10px;
        background-color: var(--vert);
        background-position: center;
        border-radius: 20px 20px 20px 20px;
        z-index: -2;
    }

    h5 {
        font-size: 22px;
        font-weight: 500;
        color: #000;
    }

    img {
        position: relative;
        width: 100%;
        height: 120%;
    }

    .logo-odc {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 25%;
        width: 30%;
    }

    .welcome {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 20%;
        width: 100%;
        font-weight: bold;
        margin-top: -20%;
    }

    .welcome .nom-odc {
        font-weight: bold;
        font-size: 16px;
    }

    .nom-odc {
        color: var(--orange);
    }

    .seConnecter {
        margin-top: -7%;
        font-size: 26px;
        font-weight: bold;
        font-family: var(--police);
    }

    form {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
        justify-content: center;
        height: 80%;
        width: 80%;
        padding-top: 0%;
        margin-top: -10%;
        font-size: 14px;
        font-family: var(--police);
        font-weight: 500;
    }

    .login,
    .pw {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 10px;
        height: 18%;
        width: 100%;
        font-size: 17px;
    }

    input {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75%;
        width: 100%;
        padding-left: 10px;
        border-radius: 5px 5px 5px 5px;
        border: solid 1px #00000080;
        border-radius: 15px;
    }

    input:focus {
        outline: none;
        border: solid 1px var(--orange);
    }

    .password {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100%;
        margin-top: -5%;
    }

    a {
        color: var(--orange);
        text-decoration: none;
        font-weight: 500;
        font-family: var(--police);
    }

    .bc {
        width: 100%;
        height: 13%;
        font-weight: 600;
    }

    .Connecter {
        background: var(--degrader-boutton);
        width: 100%;
        height: 80%;
        font-weight: 600;
        border: none;
        background: var(--orange);
        color: var(--blanc);
        border-radius: 15px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .container {
            width: 90%;
            height: 90%;
            padding: 0px 10px 0px 10px;
        }

        .entSonatel {
            width: 100%;
        }

        .mBienvenue {
            width: 100%;
        }

        form {
            width: 100%;
        }
    }
</style>