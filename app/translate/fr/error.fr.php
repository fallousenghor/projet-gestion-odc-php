<?php
enum Messageslogin: string
{
    case LOGIN_FAILED = "Login ou mot de passe incorrect.";
    case EMAIL_NOT_FOUND = "Aucun compte trouvé avec cet email.";
    case PASSWORD_RESET_SUCCESS = "Mot de passe réinitialisé avec succès.";
    case PASSWORD_RESET_FAILED = "La réinitialisation a echouer.";
}



enum MessagesUser: string
{
    case USER_NOT_FOUND = "Aucun utilisateur trouvé avec cet e-mail.";
    case SECURITY_ANSWER_INCORRECT = "Réponse à la question de sécurité incorrecte.";
    case PASSWORD_TOO_SHORT = "Le mot de passe doit contenir au moins 8 caractères";
    case PASSWORD_RESET_SUCCESS = "Mot de passe réinitialisé avec succès.";
    case PASSWORD_RESET_FAILED = "Échec de la réinitialisation du mot de passe.";

    case USER_ALREADY_EXISTS = "Cet utilisateur existe déjà.";
    case USER_CREATED = "Utilisateur créé avec succès.";
}