<?php
/* Copyright (C) 2024 Alexandre Marhic <alexandre.marhic@techsc.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Library javascript to enable Browser notifications
 */

if (!defined('NOREQUIREUSER')) {
	define('NOREQUIREUSER', '1');
}
if (!defined('NOREQUIREDB')) {
	define('NOREQUIREDB', '1');
}
if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
if (!defined('NOREQUIRETRAN')) {
	define('NOREQUIRETRAN', '1');
}
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', 1);
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1);
}
if (!defined('NOREQUIREMENU')) {
	define('NOREQUIREMENU', 1);
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', 1);
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}


/**
 * \file    addcompletebuttononevent/js/addcompletebuttononevent.js.php
 * \ingroup addcompletebuttononevent
 * \brief   JavaScript file for module AddCompleteButtonOnEvent.
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/../main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/../main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

// Define js type
header('Content-Type: application/javascript');
// Important: Following code is to cache this file to avoid page request by browser at each Dolibarr page access.
// You can use CTRL+F5 to refresh your browser cache.
if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=3600, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}
?>

/* Javascript library of module AddCompleteButtonOnEvent */
$(document).ready(function() {
    if(window.location.pathname == '/comm/action/index.php'){
        $('.cal_event').each(function() {
            var $this = $(this);
            var isTodo = $this.find('.badge-status1[title="À faire (0%)"]').length > 0;
            
            if($this[0].tagName == "TABLE" && isTodo){
                var linkHref = $this.find('a[href*="/comm/action/card.php?id="]').attr('href');
                var eventId = linkHref ? linkHref.split('=')[1] : null;

                // Créer un conteneur pour le bouton qui permettra de le centrer
                var $btnContainer = $('<div></div>').css({
                    'text-align': 'center', // Centre le bouton horizontalement
                    'margin': '10px 0' // Ajoute de l'espace autour du conteneur du bouton
                });

                var $btn = $('<button></button>', {
                    text: 'Marquer comme complété',
                    'class': 'completeButton badge-status2', // Utilisez 'class' comme propriété ici pour éviter toute confusion avec le mot-clé class
                    'data-event-id': eventId,
                    click: function(event) {
                        event.preventDefault(); // Empêcher la recharge de la page
                        event.stopPropagation(); // Arrêter la propagation de l'événement
                        
                        $.ajax({
                    	    url: '/custom/addcompletebuttononevent/ajax/set_event_complete.php', // Mettez ici l'URL correcte
                    	    type: 'POST',
                    	    data: { 'event_id': eventId },
                    	    dataType: 'json',
                    	    success: function(response) {
                    	        console.log(response);
								var messageDiv = $('<div></div>').addClass('ajax-notification').html(response.messageText);
                    	        // Ajoutez une classe en fonction du type de message
                        		if (response.messageType == 'success') {
                        		    messageDiv.addClass('ajax-success');
                        		} else if (response.messageType == 'error') {
                        		    messageDiv.addClass('ajax-error');
                        		} else if (response.messageType == 'warning') {
                        		    messageDiv.addClass('ajax-warning');
                        		}
							
                        		// Ajoutez le message à la page
                        		$('body').append(messageDiv);
							
                        		// Faites disparaître le message après quelques secondes
                        		setTimeout(function() {
                        		    messageDiv.fadeOut(500, function() {
                        		        $(this).remove();
                        		    });
                        		}, 5000);
								console.log()
								$this.find('.badge-status1[title="À faire (0%)"]').removeClass('badge-status1').addClass('badge-status6');
								$btnContainer.hide()
                    	    },
                    	    error: function(jqXHR, textStatus, errorThrown) {
                    	        console.error(textStatus, errorThrown);
                    	    }
                    	});
                    }
                }).appendTo($btnContainer); // Ajouter le bouton au conteneur

                $btnContainer.insertAfter($this.find('tbody:last-child')); // Insérer le conteneur après la table spécifiée
            }
        });
    }
});

