<?php

namespace Fuel\Migrations;

class Create_tasks
{
	public function up()
	{
		\DBUtil::create_table('tasks', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'description' => array('type' => 'text'),
			'originator_id' => array('constraint' => 11, 'type' => 'int'),	// user id of task creator
			'owner_id' => array('constraint' => 11, 'type' => 'int'),			// user if of task owner
			'status' => array('type' => 'int'),
			'type' => array('type' => 'int'),				// deadline or duty
			'started_at' => array('type' => 'int', 'null' => true),
			'completed_at' => array('type' => 'int', 'null' => true),
			'due_at' => array('type' => 'int', 'null' => true),
			'sort_order' => array('type' => 'int'),
			'can_reorder' => array('type' => 'int', 'constraint' => 1),
			'priority' => array('type' => 'int', 'constraint' => 3),
			'latitude' => array('type' => 'decimal', 'constraint' => '10,10'),
			'longitude' => array('type' => 'decimal', 'constraint' => '10,10'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('tasks');
	}
}