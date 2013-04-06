<?php
class View_Tasks_Delegateto extends ViewModel
{
	public function view()
	{
		$this->form = $this->buildTaskForm(Input::post());
	}

	public function buildTaskForm($postdata = null) {
		$fieldset = Fieldset::forge('delegated_task');

		$fieldset->add_model('Model_Task')->repopulate($postdata);

		$fieldset->field('description')->set_type('textarea');
		$fieldset->field('originator_id')->set_value($this->current_user->id)->set_type('hidden');
		$fieldset->field('owner_id')->set_value($this->slave->id)->set_type('hidden');
		$fieldset->field('status')->set_value(Model_Task::$STATUS_NEW)->set_type('hidden');
		$fieldset->field('type')->set_type('select')->set_options(array(
			0 => 'Select Task Type',
			Model_Task::$TYPE_DEADLINE => 'Deadline Task',
			Model_Task::$TYPE_DUTY => 'Duty Task'
		));	
		$fieldset->field('started_at')->set_type('hidden')->set_value(0);
		$fieldset->field('completed_at')->set_type('hidden')->set_value(0);
		$fieldset->field('sort_order')->set_type('hidden')->set_value(0);
		$fieldset->field('can_reorder')->set_type('hidden')->set_value(0);
		$fieldset->field('priority')->set_type('select')->set_options(array(
			0 => 'Select Priority',
			1 => 'Highest',
			2 => 'High',
			3 => 'Normal',
			4 => 'Low',
			5 => 'Lowest'
		));
		$fieldset->field('latitude')->set_type('hidden')->set_value(0);
		$fieldset->field('longitude')->set_type('hidden')->set_value(0);

		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Create Task', 'class' => 'btn btn-primary'));

		return $fieldset;
	}
}