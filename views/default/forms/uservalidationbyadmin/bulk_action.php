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

global $EVAN;
$users = $EVAN->get('Evan\Storage\Db')->getUsers()->where('validated', false);

$count = $users->getCount();
if (!$count) {
	echo autop(elgg_echo('uservalidationbyadmin:admin:no_unvalidated_users'));
	return TRUE;
}

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'base_url' => 'admin/users/unvalidated',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$validate = elgg_view('input/submit', array(
	'name' => 'uservalidationbyadmin/validate',
	'formaction' => elgg_normalize_url('action/uservalidationbyadmin/validate'),
	'value' => elgg_echo('uservalidationbyadmin:admin:validate'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_validate_checked'),
	'class' => 'elgg-button-submit elgg-requires-confirmation',
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
$spam = elgg_view('input/submit', array(
	'name' => 'uservalidationbyadmin/spam',
	'formaction' => elgg_normalize_url('action/uservalidationbyadmin/spam'),
	'value' => elgg_echo('spam'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_spam_checked'),
	'class' => 'elgg-button-action elgg-requires-confirmation',
));

$delete = elgg_view('input/submit', array(
	'name' => 'uservalidationbyadmin/delete',
	'formaction' => elgg_normalize_url('action/uservalidationbyadmin/delete'),
	'value' => elgg_echo('uservalidationbyadmin:admin:delete'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_delete_checked'),
	'class' => 'elgg-button-action elgg-requires-confirmation',
));

$bulk_actions = <<<___END
	<ul class="elgg-toolbar elgg-menu-hz">
		<style scoped>
			.elgg-toolbar { padding: 10px 0; }
			.elgg-toolbar > li { margin-right: 10px; }
		</style>
		<li>$validate</li>
		<li>$spam</li>
		<li>$delete</li>
	</ul>
___END;

$tbody = '';

foreach ($users->getItems($limit, $offset) as $user) {
	$tbody .= elgg_view('user/default/unvalidated', array('entity' => $user));
}

$table = <<<TABLE
<table class="elgg-table">
	<thead>
		<tr>
			<th><!-- Checkbox gets inserted here --></th>
			<th><!-- Profile icon in this column --></th>
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
