elgg.provide('elgg.uservalidationbyadmin');

elgg.uservalidationbyadmin.init = function() {
	$('#uservalidationbyadmin-form .elgg-table').each(function() {
		var $table = $(this);
		var $checkAll = $('<input type="checkbox" />');

		$table.find('thead th:first-child').append($checkAll);
		
		$checkAll.click(function() {
			var checked = $(this).attr('checked') == 'checked';
			$table.find('tbody input[type=checkbox]').attr('checked', checked);
		});
	});
	
};

elgg.register_hook_handler('init', 'system', elgg.uservalidationbyadmin.init);
