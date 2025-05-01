<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Informations de l'Apprenant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .container img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .container h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .container p {
            margin: 10px 0;
            font-size: 16px;
            color: #777;
        }

        .container .info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container .info i {
            font-size: 24px;
            color: #333;
            margin-right: 5px;
        }

        .container .info p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (isset($_GET['nom']) && isset($_GET['email']) && isset($_GET['matricule']) && isset($_GET['telephone'])): ?>
            <img src="./assets/images/fallou.jpg" alt="Photo de l'apprenant">
            <h2><?php echo htmlspecialchars($_GET['nom']); ?></h2>
            <div class="info">
                <i class='bx bx-envelope'></i>
                <p><?php echo htmlspecialchars($_GET['email']); ?></p>
            </div>
            <div class="info">
                <i class='bx bx-id-card'></i>
                <p><?php echo htmlspecialchars($_GET['matricule']); ?></p>
            </div>
            <div class="info">
                <i class='bx bx-phone'></i>
                <p><?php echo htmlspecialchars($_GET['telephone']); ?></p>
            </div>
        <?php else: ?>
            <p>Informations de l'apprenant non trouvées.</p>
        <?php endif; ?>
    </div>
</body>

</html>