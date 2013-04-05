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

	protected static $_many_many = array(
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
			$new_master = Model_User::find_by_username($new_master);
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
			$new_slave = Model_User::find_by_username($new_slave);
		}

		if ($new_slave instanceof Model_User) {
			$this->slaves[$new_slave->id] = $new_slave;
			return true;
		} else {
			return false;
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
}
