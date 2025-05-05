<?php
require_once __DIR__ . '/../Models/model.php';

require_once __DIR__ . '/../Models/ref.model.php';
require_once __DIR__ . '/../Controllers/controller.php';
require_once __DIR__ . '/../Services/execel.service.php';

require_once __DIR__ . '/../Services/validator.service.php';
require_once __DIR__ . '/../Services/PromoStateService.php';
require_once __DIR__ . '/../translate/fr/message.fr.php';

require_once __DIR__ . '/../Services/session.service.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Controllers/ref.controller.php';
require_once __DIR__ . '/../Controllers/controller.php';

require_once __DIR__ . '/../Models/apprenant.model.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../Models/apprenant.model.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Models/ref.model.php';
require_once __DIR__ . '/../Services/session.service.php';
require_once __DIR__ . '/../Services/validator.service.php';
require_once __DIR__ . '/../Services/mail.service.php';
require_once __DIR__ . '/../../vendor/autoload.php';


function getPromoDataFilePath(): string
{
    return __DIR__ . '/../../public/data/data.json';
}



function getReferentielsFilePath(): string
{
    return __DIR__ . '/../../public/data/data.json';
}



function getDataFilePath()
{
    return __DIR__ . '/../../public/data/data.json';
}