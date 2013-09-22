<?php

require_once 'TodoApp.php';

$DB_URL = parse_url(getenv('CLEARDB_DATABASE_URL'));
$app = new TodoApp($DB_URL);

if (isset($_POST['action'])) {
	
	$action = $_POST['action'];
	
	if ('add' == $action && isset($_POST['title']))
		$app->add_item($_POST['title']);
	
	if (isset($_POST['item'])) {
		if ('done' == $action) $app->finish_item($_POST['item']);
		if ('delete' == $action) $app->delete_item($_POST['item']);	
	}
}

$app->render();

?>
