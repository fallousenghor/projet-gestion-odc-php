<?php

function router()
{
    require_once __DIR__ . '/../Services/session.service.php';

    $controller = $_GET['page'] ?? 'login';
    $action = $_GET['action'] ?? '';


    $protectedPages = ['promotions', 'referentiel'];
    if (in_array($controller, $protectedPages) && !isAuthenticated()) {
        header('Location: /?page=login');
        exit();
    }

    switch ($controller) {
        case 'login':
            require_once __DIR__ . '/../Controllers/login.controller.php';

            handle_login_actions();
            break;

        // case 'password_change':
        //     require_once 'Controllers/login.controller.php';
        //     handle_login_actions();
        //     break;

        case 'promotions':
            require_once __DIR__ . '/../Controllers/promo.controller.php';
            handle_request_promo();
            break;

        case 'referentiel':
            require_once __DIR__ . '/../Controllers/ref.controller.php';
            handle_request_ref();
            break;

        case 'apprenant':
            require_once __DIR__ . '/../Controllers/apprenant.controller.php';
            handle_request_apprenant();
            break;

        case 'logout':
            logout();
            break;





        default:
            echo "<h2>Contrôleur inconnu : $controller</h2>";
            break;
    }
}