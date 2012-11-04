<?php
/**
 * Bans a user as spam.
 *
 * User entities are banned by setting the 'banned' column
 * to 'yes' in the users_entity table.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

$user_guids = get_input('user_guids');
$error = FALSE;

if (!$user_guids) {
	register_error(elgg_echo('uservalidationbyadmin:errors:unknown_users'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($user_guids as $guid) {
	if ($guid == elgg_get_logged_in_user_guid()) {
		$error = TRUE;
		continue;
	}
	
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = true;
		continue;
	}
	
	if (!$user->canEdit()) {
		$error = true;
		continue;
	}
	
	if (!$user->ban('spam')) {
		$error = true;
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyadmin:messages:spammed_user');
	$error_txt = elgg_echo('uservalidationbyadmin:errors:could_not_spam_user');
} else {
	$message_txt = elgg_echo('uservalidationbyadmin:messages:spammed_users');
	$error_txt = elgg_echo('uservalidationbyadmin:errors:could_not_spam_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);