<h2>Viewing #<?php echo $task->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $task->title; ?></p>

<?php echo Html::anchor('admin/tasks/edit/'.$task->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tasks', 'Back'); ?>