<h2>Listing Tasks</h2>
<br>
<?php if ($tasks): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tasks as $task): ?>		<tr>

			<td><?php echo $task->title; ?></td>
			<td>
				<?php echo Html::anchor('admin/tasks/view/'.$task->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tasks/edit/'.$task->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tasks/delete/'.$task->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tasks.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tasks/create', 'Add new Task', array('class' => 'btn btn-success')); ?>

</p>
