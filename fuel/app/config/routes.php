<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	'user/index'			=> 'user/index',
	'user/:username'		=> 'user/viewuser',

	'task/delegateto'			=> 'tasks/delegateto',
	'task/:task_id/:action'		=> 'tasks/performaction',
	'task/:task_id'				=> 'tasks/view',

	'tasks/delegateto/:slave'	=> 'tasks/delegateto',

	'tasks/:task_id'			=> 'tasks/view'
);