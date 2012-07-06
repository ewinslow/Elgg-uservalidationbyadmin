<?php
/**
 * Admin area to view, validate, resend validation email, or delete unvalidated users.
 *
 * @package Elgg.Core.Plugin
 * @subpackage uservalidationbyadmin.Administration
 */
elgg_load_js('elgg.uservalidationbyadmin');

$limit = get_input('limit', 100);
$offset = get_input('offset', 0);

// can't use elgg_list_entities() and friends because we don't use the default view for users.
$ia = elgg_set_ignore_access(TRUE);
$hidden_entities = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

$options = array(
	'type' => 'user',
	'wheres' => uservalidationbyadmin_get_unvalidated_users_sql_where(),
	'limit' => $limit,
	'offset' => $offset,
	'count' => TRUE,
);
$count = elgg_get_entities($options);

if (!$count) {
	access_show_hidden_entities($hidden_entities);
	elgg_set_ignore_access($ia);

	echo autop(elgg_echo('uservalidationbyadmin:admin:no_unvalidated_users'));
	return TRUE;
}

$options['count']  = FALSE;

$users = elgg_get_entities($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'base_url' => 'admin/users/unvalidated',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$validate = elgg_view('input/submit', array(
	'name' => 'action/uservalidationbyadmin/validate',
	'value' => elgg_echo('uservalidationbyadmin:admin:validate'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_validate_checked'),
	'class' => 'elgg-button elgg-button-submit elgg-requires-confirmation',
	'is_action' => true,
	'is_trusted' => true,
));
/*
$resend_email = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyadmin/resend_validation/',
	'text' => elgg_echo('uservalidationbyadmin:admin:resend_validation'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_resend_validation_checked'),
	'class' => 'uservalidationbyadmin-submit',
	'is_action' => true,
	'is_trusted' => true,
));
*/
$delete = elgg_view('input/submit', array(
	'name' => 'action/uservalidationbyadmin/delete',
	'value' => elgg_echo('uservalidationbyadmin:admin:delete'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_delete_checked'),
	'class' => 'elgg-button elgg-button-action elgg-requires-confirmation',
	'is_action' => true,
	'is_trusted' => true,
));

$bulk_actions = <<<___END
	<ul class="elgg-toolbar elgg-menu-hz">
		<style scoped>
			.elgg-toolbar { padding: 10px 0; }
			.elgg-toolbar > li { margin-right: 10px; }
		</style>
		<li>$validate</li><li>$delete</li>
	</ul>
___END;

$tbody = '';
if (is_array($users) && count($users) > 0) {
	foreach ($users as $user) {
		$tbody .= elgg_view('user/default/unvalidated', array('entity' => $user));
	}
}

$table = <<<TABLE
<table class="elgg-table">
	<thead>
		<tr>
			<th><!-- Checkbox gets inserted here --></th>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Created</th>
		</tr>
	</thead>
	<tbody>
		$tbody
	</tbody>
</table>
TABLE;

echo $bulk_actions; // toolbar
echo $table; // data
