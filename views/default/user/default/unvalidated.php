<?php
/**
 * Formats and list an unvalidated user.
 *
 * @package Elgg.Core.Plugin
 * @subpackage uservalidationbyadmin.Administration
 */

$user = elgg_extract('entity', $vars);

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'user_guids[]',
	'value' => $user->guid,
	'default' => false,
));

$created = elgg_echo('uservalidationbyadmin:admin:user_created', array(elgg_view_friendly_time($user->time_created)));

$validate = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('uservalidationbyadmin:confirm_validate_user', array($user->username)),
	'href' => "/action/uservalidationbyadmin/validate?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyadmin:admin:validate')
));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('uservalidationbyadmin:confirm_delete', array($user->username)),
	'href' => "action/uservalidationbyadmin/delete/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyadmin:admin:delete')
));

$row = <<<___END
<tr id="unvalidated-user-{$user->guid}" class="elgg-item">
	<td>$checkbox</td>
	<td>$user->name</td>
	<td>$user->username</td>
	<td>$user->email</td>
	<td>$created</td>
</tr>
___END;

echo $row;