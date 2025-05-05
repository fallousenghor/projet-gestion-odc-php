<?php

enum Messages: string
{
    case DIRECTORY_NOT_WRITABLE = "Le répertoire de données n'est pas accessible en écriture";
    case FILE_CREATION_FAILED = "Impossible de créer le fichier de données";
    case FILE_NOT_READABLE = "Le fichier de données n'est pas accessible en lecture";
    case FILE_READ_FAILED = "Impossible de lire le fichier de données";
    case JSON_ENCODE_ERROR = "Erreur lors de l'encodage JSON: ";
    case JSON_DECODE_ERROR = "Erreur lors du décodage JSON: ";
}


enum MessageReferentiel: string
{
    case REPERTOIRE_NON_ACCESSIBLE = "Le répertoire de données n'est pas accessible en écriture";
    case IMPOSSIBLE_CREER_FICHIER = "Impossible de créer le fichier de données";
    case FICHIER_NON_LECTURE = "Le fichier de données n'est pas accessible en lecture";
    case FICHIER_NON_LISIBLE = "Impossible de lire le fichier de données";
}

enum ApprenantTexts: string
{
    case MISSING_ID = "ID de l'apprenant manquant";
    case LEARNER_NOT_FOUND = "Apprenant non trouvé";
    case NO_ACTIVE_PROMOTION = "Aucune promotion active sélectionnée";
    case NO_REFERENTIELS = "Aucun référentiel associé à la promotion active";
    case SUCCESS_ADD = "Apprenant ajouté avec succès";
    case ERROR_SAVE = "Erreur lors de l'enregistrement de l'apprenant";
    case MISSING_REFERENTIEL_ID = "ID de référentiel manquant";
    case INVALID_FILE = "Fichier invalide";
    case IMPORT_SUCCESS = "Importation réussie";
    case IMPORT_FAILED = "Échec de l'importation";
    case NO_FILE_SENT = "Aucun fichier envoyé";

    // Validation messages
    case FIRSTNAME_REQUIRED = "Le prénom est obligatoire";
    case LASTNAME_REQUIRED = "Le nom est obligatoire";
    case BIRTHDATE_REQUIRED = "La date de naissance est obligatoire";
    case BIRTHPLACE_REQUIRED = "Le lieu de naissance est obligatoire";
    case ADDRESS_REQUIRED = "L'adresse est obligatoire";
    case EMAIL_REQUIRED = "L'email est obligatoire";
    case PHONE_REQUIRED = "Le téléphone est obligatoire";
    case TUTOR_NAME_REQUIRED = "Le nom du tuteur est obligatoire";
    case RELATIONSHIP_REQUIRED = "Le lien de parenté est obligatoire";
    case TUTOR_ADDRESS_REQUIRED = "L'adresse du tuteur est obligatoire";
    case TUTOR_PHONE_REQUIRED = "Le téléphone du tuteur est obligatoire";
    case REFERENTIEL_REQUIRED = "Veuillez sélectionner un référentiel";
    case INVALID_EMAIL = "Email invalide";
    case SHORT_PHONE = "Numéro de téléphone trop court (min 8 chiffres)";
    case INVALID_PHONE_FORMAT = "Format de téléphone invalide";
    case INVALID_DATE_FORMAT = "Format de date invalide (YYYY-MM-DD requis)";
}

enum RefTexts: string
{
    case NO_ACTIVE_PROMO = "Aucune promotion active sélectionnée";
    case IMAGE_ERROR = "Erreur lors du traitement de l'image";
    case SAVE_ERROR = "Erreur lors de la sauvegarde";
    case UNAUTHORIZED_METHOD = "Méthode non autorisée";
    case MODIF_DISABLED = "Les modifications sont désactivées pour les promotions terminées";
    case REFS_UPDATED = "Référentiels mis à jour avec succès";
    case REF_ADDED = "Référentiel ajouté avec succès";

    // Messages avec variables
    case REMOVED_REFS = " (%d référentiel(s) supprimé(s))";
    case ADDED_REFS = " (%d nouveau(x) référentiel(s) ajouté(s))";
    case UPDATE_ERROR = "Erreur lors de la mise à jour des référentiels";
}


enum ReferentielMessages: string
{
    case AUCUNE_PROMO_ACTIVE = "Aucune promotion active sélectionnée";
    case ERREUR_IMAGE = "Erreur lors du traitement de l'image";
    case REFERENTIEL_AJOUTE = "Référentiel ajouté avec succès";
    case ERREUR_SAUVEGARDE = "Erreur lors de la sauvegarde";
    case METHODE_NON_AUTORISEE = "Méthode non autorisée";
    case PROMO_TERMINEE = "Les modifications sont désactivées pour les promotions terminées";
    case REFERENTIELS_MAJ_SUCCES = "Référentiels mis à jour avec succès";
    case REFERENTIELS_MAJ_ERREUR = "Erreur lors de la mise à jour des référentiels";
    case ERREUR_HANDLE = "Erreur dans handle_request_ref";
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
    case ERREUR_TELECHARGEMENT_IMAGE = 'Erreur lors du téléchargement de l\'image';
    case ERREUR_CREATION_PROMOTION = 'Erreur lors de la création de la promotion';
    case SUCCES_CREATION_PROMOTION = 'Promotion créée avec succès';
    case ERREUR_ACTION_INEXISTANTE = 'Erreur : l\'action demandée n\'existe pas dans le module des promotions.';
    case ERREUR_SURVENUE = 'Une erreur est survenue: ';
}