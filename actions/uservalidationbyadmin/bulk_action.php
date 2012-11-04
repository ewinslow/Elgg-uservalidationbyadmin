<?php
/**
 * Dispatches a bulk action to real action.
 *
 * @package Elgg.Core.Plugin
 * @subpackage uservalidationbyadmin
 */

if (get_input('uservalidationbyadmin/delete', false)) {
	action('uservalidationbyadmin/delete');
} elseif (get_input('uservalidationbyadmin/ban', false)) {
	action('uservalidationbyadmin/ban');
} else {
	// Always assume validate as default since some browsers don't send submit button info when keyboard is used.
	// See http://stackoverflow.com/questions/2680160/how-can-i-tell-which-button-was-clicked-in-a-php-form-submit
	action('uservalidationbyadmin/validate');
}