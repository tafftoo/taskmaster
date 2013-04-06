<?php
class Model_Task extends \Orm\Model
{
	public static $TYPE_DEADLINE = 1;
	public static $TYPE_DUTY = 2;

	public static $STATUS_NEW = 1;
	public static $STATUS_IN_PROGRESS = 2;
	public static $STATUS_PAUSED = 3;
	public static $STATUS_COMPLETE = 4;

	protected static $_properties = array(
		'id',
		'title',
		'description',
		'originator_id',	// user id of task creator
		'owner_id',			// user if of task owner
		'status',			// New, In progress, Paused, Completed
		'type',				// deadline or duty
		'started_at',
		'completed_at',
		'due_at',
		'sort_order',
		'can_reorder',
		'priority',
		'latitude',
		'longitude',
		'created_at' => array(
			'skip' => true
		),
		'updated_at' => array(
			'skip' => true
		),
	);

	protected static $_has_one = array(
		'owner' => array(
			'model_to'			=> 'Model_User',
			'key_from'			=> 'owner_id',
			'key_to'			=> 'id',
			'cascade_save'		=> false,
			'cascade_delete' 	=> false			
		),
		'originator' => array(
			'model_to'			=> 'Model_User',
			'key_from'			=> 'originator_id',
			'key_to'			=> 'id',
			'cascade_save'		=> false,
			'cascade_delete' 	=> false
		)
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('title', 'Title', 'required|max_length[255]');

		return $val;
	}

}
