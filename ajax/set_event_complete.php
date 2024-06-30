<?php
/* Copyright (C) 2022 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *       \file       htdocs/recruitementplus/ajax/myobject.php
 *       \brief      File to return Ajax response on product list request
 */


if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1); // Disables token renewal
}
if (!defined('NOREQUIREMENU')) {
	define('NOREQUIREMENU', '1');
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', '1');
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}
if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', '1');
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', '1');
}

// Load Dolibarr environment
require '../../../main.inc.php';

// Security check
if($user->rights->societe->contact->lire){

}else{
    echo json_encode([
        'success' => false,
        'error' => "vous navez pas les droit"
    ]);
}

$eventId = GETPOST('event_id', 'int');
/*
 * View
 */

//dol_syslog("Call ajax recruitementplus/ajax/myobject.php");

top_httphead('application/json');


//$arrayresult = array();
//
require '../../../comm/action/class/actioncomm.class.php';
//
if ($eventId) {
    // Chargez l'événement/agenda en utilisant la classe ActionComm
    $actionComm = new ActionComm($db);
    $result = $actionComm->fetch($eventId);

    if ($result > 0) {
        // Vérifiez si l'événement est lié à une candidature
        $actionComm->updatePercent($eventId,100);
        echo json_encode([
            'success' => true,
            'messageType' => 'success', // ou 'error', 'warning', etc.
            'messageText' => 'L\'évènement '.$eventId.' à été mis au status terminé.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => "Aucun événement trouvé pour cet ID."
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => "ID d'événement non fourni."
    ]);
}


//
//$db->close();

