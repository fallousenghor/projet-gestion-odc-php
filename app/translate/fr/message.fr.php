<?php





enum Messages: string {
    case DIRECTORY_NOT_WRITABLE = "Le répertoire de données n'est pas accessible en écriture";
    case FILE_CREATION_FAILED = "Impossible de créer le fichier de données";
    case FILE_NOT_READABLE = "Le fichier de données n'est pas accessible en lecture";
    case FILE_READ_FAILED = "Impossible de lire le fichier de données";
    case JSON_ENCODE_ERROR = "Erreur lors de l'encodage JSON: ";
    case JSON_DECODE_ERROR = "Erreur lors du décodage JSON: ";
}








enum MessagesInterface: string
{
    case RECHERCHER_PLACEHOLDER = "Rechercher...";
    case EMAIL_NON_DEFINI = "Email non défini";
    case ROLE_PAR_DEFAUT = "Utilisateur";
    case UTILISATEUR_NON_CONNECTE = "Utilisateur non connecté";
    case PROMOTION = "Promotion-2025";
    case TABLEAU_DE_BORD = "Tableau de bord";
    case PROMOTIONS = "Promotions";
    case REFERENTIELS = "Referentiels";
    case APPRENANTS = "Apprenants";
    case GESTION_PRESENCES = "Gestion des presences";
    case KITS_LAPTOPS = "Kits & Laptops";
    case RAPPORTS_STATS = "Rapports & Stats";
    case DECONNEXION = "Deconnexion";
}



enum TextPromo: string
{
    case PROMOTION = 'Promotion';
    case GERER_PROMOTIONS = 'Gerer les promotions de l\'ecole';
    case AJOUTER_PROMO = 'Ajouter une promotion';
    case APPRENANTS = 'Apprenants';
    case REFERENTIELS = 'Référentiels';
    case PROMOTIONS_ACTIVES = 'Promotions actives';
    case TOTAL_PROMOTIONS = 'Total promotions';
    case RECHERCHER_PROMO = 'Rechercher une promotion...';
    case TOUS = 'Tous';
    case OPTION_2 = 'Option 2';
    case OPTION_3 = 'Option 3';
    case GRILLE = 'Grille';
    case LISTE = 'Liste';
    case VOIR_PLUS = 'Voir plus';
    case INACTIF = 'Inactif';
    case ACTIF = 'Actif';
}