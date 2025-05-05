<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste de présences</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="header">
            <span>Programme & Modules</span>
            <button class="btn-orange">Liste de présences de l’apprenant</button>
        </div>

        <table class="presence-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Matricule</th>
                    <th>Nom Complet</th>
                    <th>Date & Heure</th>
                    <th>Statut</th>
                    <th>Justification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemple de ligne (à dupliquer dynamiquement) -->
                <tr>
                    <td><img src="https://via.placeholder.com/40" alt="Photo" class="avatar"></td>
                    <td>1058215</td>
                    <td>Seydina Mouhammad Diop</td>
                    <td>10/02/2025 7:32</td>
                    <td><span class="badge red">Absent</span></td>
                    <td><span class="badge green">Justifiée</span></td>
                    <td><span class="dots">•••</span></td>
                </tr>

                <tr>
                    <td><img src="https://via.placeholder.com/40" alt="Photo" class="avatar"></td>
                    <td>1058218</td>
                    <td>Seydina Mouhammad Diop</td>
                    <td>10/02/2025 7:32</td>
                    <td><span class="badge red">Absent</span></td>
                    <td><span class="badge orange">Non justifiée</span></td>
                    <td><span class="dots">•••</span></td>
                </tr>

                <!-- Ajouter d'autres lignes ici -->
            </tbody>
        </table>

        <div class="pagination-container">
            <div>
                <label>Apprenants/page</label>
                <select>
                    <option>10</option>
                    <option>25</option>
                </select>
            </div>
            <div class="info">1 à 10 apprenants pour 142</div>
            <div class="pagination">
                <button disabled>&lt;</button>
                <button class="active">1</button>
                <button>2</button>
                <button>...</button>
                <button>10</button>
                <button>&gt;</button>
            </div>
        </div>
    </div>

</body>

</html>


<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #fff;
        padding: 20px;
        color: #333;
    }

    .container {
        max-width: 1100px;
        margin: auto;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .btn-orange {
        background-color: #FF8000;
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
    }

    .presence-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    .presence-table thead {
        background-color: #FF8000;
        color: white;
    }

    .presence-table th,
    .presence-table td {
        padding: 12px 15px;
        text-align: left;
    }

    .presence-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        color: white;
        display: inline-block;
    }

    .badge.red {
        background-color: #e74c3c;
    }

    .badge.green {
        background-color: #27ae60;
    }

    .badge.orange {
        background-color: #f39c12;
    }

    .dots {
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-container select {
        padding: 5px 8px;
        border-radius: 5px;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination button {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        background: #eee;
        cursor: pointer;
    }

    .pagination button.active {
        background-color: #FF8000;
        color: white;
    }

    .pagination button:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>