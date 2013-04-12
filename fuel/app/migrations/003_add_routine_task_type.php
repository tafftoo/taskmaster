<?php
namespace Fuel\Migrations;

class Add_routine_task_type
{
	public function up()
	{
		\DBUtil::add_fields('tasks', array(
			'time_allocated' => array('type' => 'int'),
			'time_spent'	 => array('type' => 'int')
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tasks', array(
			'time_allocated',
			'time_spent'
		));
	}
}