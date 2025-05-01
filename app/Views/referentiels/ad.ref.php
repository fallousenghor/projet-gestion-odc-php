<!DOCTYPE html>
<html lang="fr">
<?php require '../app/Views/layout/base.layout.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Gestion des référentiels</title>


</head>

<body>
    <div class="modal mod">
        <h2><i class="fas fa-book-open"></i> Gestion des référentiels
            <?php 
            $activePromo = $activePromo ?? [];
            $referentiels = $referentiels ?? [];
            $promoState = $activePromo['etat'] ?? 'indetermine';
            $isTerminee = ($promoState === 'termine'); // Seulement vrai si terminée
            
            $stateLabels = [
                'pas_commence' => 'À venir',
                'en_cours' => 'En cours', 
                'termine' => 'Terminée'
            ];
            ?>
            <span class="state-badge state-<?= htmlspecialchars($promoState) ?>">
                <?= htmlspecialchars($stateLabels[$promoState] ?? 'Indéterminé') ?>
            </span>
        </h2>

        <form action="?page=referentiel&action=assign-ref" method="POST">
            <input type="hidden" name="promo_id" value="<?= htmlspecialchars($activePromo['id'] ?? '') ?>">

            <div class="form-group">
                <label><i class="fas fa-graduation-cap"></i>
                    <?= htmlspecialchars($activePromo['titre'] ?? 'Promotion inconnue') ?></label>

                <?php if (!empty($activePromo['referentiels'] ?? [])): ?>
                <div class="form-group">
                    <div class="referentiels-list">
                        <?php
    $assignedRefs = get_referentiels_by_promo_id($activePromo['referentiels'] ?? []);
    
    foreach ($assignedRefs as $ref): 
        $hasApprenants = count_apprenants_in_referentiel($ref['id'], $activePromo['id']) > 0;
    ?>
                        <div class="referentiel-item">
                            <input type="checkbox" id="assigned_ref_<?= htmlspecialchars($ref['id'] ?? '') ?>"
                                name="assigned_refs[]" value="<?= htmlspecialchars($ref['id'] ?? '') ?>" checked
                                <?= ($isTerminee || $hasApprenants) ? 'disabled' : '' ?>>
                            <label for="assigned_ref_<?= htmlspecialchars($ref['id'] ?? '') ?>"
                                <?= ($isTerminee || $hasApprenants) ? 'class="disabled-label"' : '' ?>>
                                <i class="fas fa-file-alt"></i>
                                <?= htmlspecialchars($ref['titre'] ?? 'Référentiel inconnu') ?>
                                <i class="fas fa-check-circle assigned-icon"></i>
                                <?php if ($hasApprenants): ?>
                                <span class="apprenant-warning"
                                    title="Ce référentiel contient des apprenants et ne peut être retiré">
                                    <i class="fas fa-users"></i>
                                </span>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label><i class="fas fa-list-ul"></i> Référentiels disponibles</label>
                <div class="referentiels-list">
                    <?php
                    $assignedRefIds = $activePromo['referentiels'] ?? [];
                    $availableRefs = array_filter($referentiels, function ($ref) use ($assignedRefIds) {
                        return !in_array($ref['id'] ?? '', $assignedRefIds);
                    });

                    if (!empty($availableRefs)): 
                        foreach ($availableRefs as $ref): ?>
                    <div class="referentiel-item">
                        <input type="checkbox" id="ref_<?= htmlspecialchars($ref['id'] ?? '') ?>" name="referentiels[]"
                            value="<?= htmlspecialchars($ref['id'] ?? '') ?>"
                            <?= (isset($_POST['referentiels'])) && in_array($ref['id'], $_POST['referentiels'] ?? []) ? 'checked' : '' ?>
                            <?= $isTerminee ? 'class="disabled-checkbox" disabled' : '' ?>>
                        <label for="ref_<?= htmlspecialchars($ref['id'] ?? '') ?>"
                            <?= $isTerminee ? 'class="disabled-label"' : '' ?>>
                            <i class="fas fa-file-alt"></i>
                            <?= htmlspecialchars($ref['titre'] ?? 'Référentiel inconnu') ?>
                        </label>
                    </div>
                    <?php endforeach;
                    else: ?>
                    <p class="no-ref-message"><i class="fas fa-info-circle"></i> Tous les référentiels sont déjà
                        assignés.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="buttons-container">
                <?php if (!$isTerminee): ?>
                <a href="#">
                    <button type="submit" name="submit_type" value="validate" class="btn btn-validate">
                        <i class="fas fa-save"></i> Valider
                    </button>
                </a>
                <button type="submit" name="submit_type" value="finish" class="btn btn-finish">
                    <i class="fas fa-check-circle"></i> Terminer
                </button>
                <?php else: ?>
                <div class="alert-info">
                    <i class="fas fa-info-circle"></i>
                    Les modifications sont désactivées pour les promotions terminées.
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

</html>
<style>
:root {
    --primary-color: #fa7214;
    --secondary-color: #f8f9fa;
    --accent-color: #6c757d;
    --success-color: #37a39c;
}

.state-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    margin-left: 10px;
}

.state-pas_commence {
    background-color: #e2e3e5;
    color: #383d41;
}

.state-en_cours {
    background-color: #d4edda;
    color: #155724;
}

.state-termine {
    background-color: #f8d7da;
    color: #721c24;
}

.disabled-checkbox {
    opacity: 0.6;
    pointer-events: none;
}

.disabled-label {
    color: #888;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    padding: 10px;
    border-radius: 4px;
    margin: 15px 0;
}


.modal.mod {
    max-width: 81%;
    /* margin: 2rem auto; */
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    margin-top: 120px;
    margin-left: auto;
    margin-right: 20px;

}

.modal h2 {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.modal h2 i {
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
    padding: 1.2rem;
    background: var(--secondary-color);
    border-radius: 8px;
    border-left: 4px solid var(--primary-color);
}

.form-group label {
    display: block;
    margin-bottom: 0.8rem;
    font-weight: 600;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.referentiels-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 0.8rem;
}

.referentiel-item {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    background: white;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.referentiel-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.referentiel-item input[type="checkbox"] {
    margin-right: 10px;
    accent-color: var(--primary-color);
    width: 18px;
    height: 18px;
}

.referentiel-item label {
    margin: 0;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    flex-grow: 1;
    display: flex;
    align-items: center;
    gap: 8px;
}

.referentiel-item a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    width: 100%;
}

.no-ref-message {
    color: var(--accent-color);
    font-style: italic;
    padding: 1rem;
    text-align: center;
    grid-column: 1 / -1;
}

.buttons-container {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn {
    flex: 1;
    padding: 0.8rem;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

}

.btn-validate {
    background: var(--success-color);
}

.assigned-icon {
    color: var(--success-color);
    margin-left: auto;
}


.btn-validate:hover {
    background: #218838;
    transform: translateY(-2px);
}

.btn-finish {
    background: var(--primary-color);
}



.assigned-icon {
    color: var(--success-color);
    margin-left: auto;
}


< !-- <a href="?page=promotion"class="btn btn-cancel"><i class="fas fa-times"></i>Annuler </a>-->.state-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    margin-left: 10px;
}

.state-pas_commence {
    background-color: #e2e3e5;
    color: #383d41;
}

.state-en_cours {
    background-color: #d4edda;
    color: #155724;
}

.state-termine {
    background-color: #f8d7da;
    color: #721c24;
}

.state-indetermine {
    background-color: #fff3cd;
    color: #856404;
}

.disabled-checkbox {
    opacity: 0.6;
    pointer-events: none;
}

.disabled-label {
    color: #888;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    padding: 10px;
    border-radius: 4px;
    margin: 15px 0;
}

.referentiel-item {
    margin: 8px 0;
    padding: 8px;
    border-radius: 4px;
    background-color: #f8f9fa;
}

.no-ref-message {
    color: #6c757d;
    font-style: italic;
}
</style>