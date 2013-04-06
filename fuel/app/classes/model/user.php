<?php
class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group',
		'email',
		'last_login',
		'login_hash',
		'profile_fields',
		'created_at',
		'updated_at',
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

	protected static $_has_many = array(
		/**
		 *	Tasks that have been assigned _TO_ this user
		 */
		'assigned_tasks' => array(
			'model_to'			=> 'Model_Task',
			'key_from'			=> 'id',
			'key_to'			=> 'owner_id',
			'cascade_save'		=> false,
			'cascade_delete' 	=> false
		),

		/**
		 *	Tasks that have been assigned _BY_ this user
		 */
		'delegated_tasks' => array(
			'model_to' 			=> 'Model_Task',
			'key_from'			=> 'id',
			'key_to'			=> 'originator_id',
			'cascade_save'		=> false,
			'cascade_delete'	=> false
		)
	);

	protected static $_many_many = array(
		/**
		 *	Users that can delegate tasks _TO_ this user
		 */
		'masters' => array(
			'key_from' 				=> 'id',
			'key_through_from' 		=> 'slave_id',
			'table_through'			=> 'master_slave',
			'key_through_to'		=> 'master_id',
			'model_to'				=> 'Model_User',
			'key_to'				=> 'id',
			'cascade_save'			=> false,
			'cascade_delete'		=> false
		),
		/**
		 *	Users that can be delegated tasks _BY_ this user
		 */
		'slaves' => array(
			'key_from'				=> 'id',
			'key_through_from'		=> 'master_id',
			'table_through'			=> 'master_slave',
			'key_through_to'		=> 'slave_id',
			'model_to'				=> 'Model_User',
			'key_to'				=> 'id',
			'cascade_save'			=> false,
			'cascade_delete'		=> false		
		)
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('username', 'Username', 'required|max_length[50]');
		$val->add_field('password', 'Password', 'required|max_length[255]');
		$val->add_field('group', 'Group', 'required|valid_string[numeric]');
		$val->add_field('email', 'Email', 'required|valid_email|max_length[255]');
		$val->add_field('last_login', 'Last Login', 'required|valid_string[numeric]');
		$val->add_field('login_hash', 'Login Hash', 'required|max_length[255]');
		$val->add_field('profile_fields', 'Profile Fields', 'required');

		return $val;
	}

	/**
	 *	Adds a new master for this user
	 *
	 * 	@param integer|string|Model_User $new_master The new master to add. 
	 * 			An integer will be assumed to be an id, while a string will be assumed to be a username
	 *  @return boolean indicating success
	 */
	public function addMaster($new_master)
	{
		if (is_numeric($new_master)) {
			$new_master = Model_User::find($new_master);
		}

		if (is_string($new_master)) {
			$new_master = Model_User::query()->where('username', $new_master)->get_one();
		}

		if ($new_master instanceof Model_User) {
			$this->masters[$new_master->id] = $new_master;
			return true;
		} else {
			return false;
		}
	}

	/**
	 *	Adds a new slave for this user
	 *
	 * 	@param integer|string|Model_User $new_slave The new slave to add. 
	 * 			An integer will be assumed to be an id, while a string will be assumed to be a username
	 *  @return boolean indicating success
	 */
	public function addSlave($new_slave)
	{
		if (is_numeric($new_slave)) {
			$new_slave = Model_User::find($new_slave);
		}

		if (is_string($new_slave)) {
			$new_slave = Model_User::query()->where('username', $new_slave)->get_one();
		}

		if ($new_slave instanceof Model_User) {
			$this->slaves[$new_slave->id] = $new_slave;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * isMasterOf
	 *
	 * Returns a boolean indicating whether this user is a master of the specified user
	 *
	 * @param integer|string|Model_User $user the user to check
	 * @return boolen Indicates whether this user is master of the specified user
	 */
	public function isMasterOf($user)
	{
		if (is_numeric($user)) {
			$user = Model_User::find($user);
		}

		if (is_string($user)) {
			$user = Model_User::query()->where('username', $user)->get_one();
		}

		if ($user instanceof Model_User) {
			return in_array($user->id, array_keys($this->slaves));
		}
	}

	/**
	 * isSlaveTo
	 *
	 * Returns boolean indicating whether this user is a slave of the specified user
	 *
	 * @param integer|string|Model_User $user the user to check
	 * @return boolean Indicates whether this user is a slave to the specified user
	 */
	public function isSlaveTo($user)
	{
		if (is_numeric($user)) {
			$user = Model_User::find($user);
		}

		if (is_string($user)) {
			$user = Model_User::query()->where('username', $user)->get_one();
		}

		if ($user instanceof Model_User) {
			return in_array($user->id, array_keys($this->masters));
		}
	}

	/**
	 * Override magic method __get to check additional data.
	 *
	 * We override this method on Model_User to check the profile_fields for properties as well as everything else.
	 * This provides us with a shorthand for user_profile fields such as $user->display_name, or $user->web_address
	 * 
	 * @param string $name The name of the property to get
	 * @return mixed The value of the property
	 * @throws OutOfBoundsException If the property can not be found
	 */
	public function & __get($name) {
		if ($name != 'profile_fields') {		// test stops weird __get recursion issue
			// check the profile fields
			$fields = unserialize($this->profile_fields);
			foreach($fields as $fname => $value) {
				// property match, return the value
				if ($fname == $name) return $value;
			}			
		}
		return parent::__get($name);
	}

	/**
	 * Set a user profile field
	 *
	 * @param string $name The name of the profile field to set
	 * @param mixed $value The new value of the profile field
	 */
	public function setProfileField($name, $value)
	{
		$fields = unserialize($this->profile_fields);
		$fields[$name] = $value;
		$this->profile_fields = serialize($fields);
	}

	/**
	 * Returns a boolean indicating whether this user can view the given object
	 *
	 * @param object $object The object to test
	 * @return boolean Indicating whether the user can view the object
	 */
	public function canView($object)
	{
		if ($object instanceof Model_Task)
		{
			return (
				$this->id == $object->owner_id ||				// this user was assigned the task
				$this->id == $object->originator_id ||			// this user assigned the task
				$this->isMasterOf($object->originator_id) ||	// this users slave assigned the task
				$this->isMasterOf($object->owner_id)			// this user slave was assigned the task
				);
		} 
		else if ($object instanceof Model_User)
		{
			throw new Exception('Not implmented yet!');
		}
		else
		{
			throw new Exception('Unknown object');
		}
	}
}
