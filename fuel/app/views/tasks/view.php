<div class="row">
	<div class="span12">
		<h1>Task: <?php echo $task->title; ?></h1>
		<p>
			Assigned to 
			<?php if ($current_user && $task->owner_id == $current_user->id): ?>
			<em>you</em>
			<?php else: ?>
			<?php echo $task->owner->username; ?>
			<?php endif; ?>
			by
			<?php if ($current_user && $task->originator_id == $current_user->id): ?>
			<em>you</em>
			<?php else: ?>
			<?php echo $task->originator->username; ?>
			<?php endif; ?>
		</p>
		<?php if ($task->type == Model_Task::$TYPE_DEADLINE): ?>
		<p>Date Due: <?php echo date('j/M/Y H:i', $task->due_at); ?></p>
		<?php else: ?>
		<p>Priority: <?php echo $show_priority($task->priority); ?></p>
		<?php endif; ?>
		<p><?php echo $task->description; ?></p>
		<?php if ($task->started_at > 0): ?>
		<p>Task started at: <?php echo date('j/M/Y H:i', $task->started_at); ?></p>
		<?php endif; ?>
		<?php if ($task->completed_at > 0): ?>
		<p>Task completed at: <?php echo date('j/M/Y H:i', $task->completed_at); ?></p>
		<?php endif; ?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<?php if ($task->owner_id == $current_user->id): ?>
			<?php if ($task->status == Model_Task::$STATUS_NEW): ?>
				<a href="/task/<?php echo $task->id; ?>/start" class="btn">Start Task</a>
			<?php elseif ($task->status == Model_Task::$STATUS_IN_PROGRESS): ?>
				<a href="/task/<?php echo $task->id; ?>/pause" class="btn">Pause Task</a>
				<a href="/task/<?php echo $task->id; ?>/complete" class="btn btn-primary">Mark Completed</a>
			<?php elseif ($task->status == Model_Task::$STATUS_PAUSED): ?>
				<a href="/task/<?php echo $task->id; ?>/resume" class="btn">Resume Task</a>
			<?php elseif ($task->status == Model_Task::$STATUS_COMPLETE): ?>
				<p>This task was completed at <em><?php echo date('j/M/Y H:i', $task->completed_at); ?></em></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>