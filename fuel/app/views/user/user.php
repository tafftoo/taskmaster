<div class="row">
	<div class="span2">
		<?php if (isset($user->avatar_url)): ?>
		<img src="<?php echo $user->avatar_url; ?>" class="img-polaroid" width="100%">
		<?php else: ?>
		<img src="/assets/img/missing_avatar.jpg" class="img-polaroid" width="100%">
		<?php endif; ?>
	</div>
	<div class="span10">
		<h1><?php echo $user->username; ?></h1>
	</div>
</div>
<hr>
<div class="row">
	<?php if ($show_sidepanel): ?>
	<div class="span8">
	<?php else: ?>
	<div class="span12">
	<?php endif; ?>
		<div class="tabbable"> <!-- Only required for left/right tabs -->
			<ul class="nav nav-tabs">
				<li class="active"><a href="#assigned" data-toggle="tab">Assigned Tasks</a></li>
				<li><a href="#delegated" data-toggle="tab">Delegated Tasks</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="assigned">
					<h2>Assigned Tasks</h2>
					<?php if (count($user->assigned_tasks) > 0): ?>
					<ul>
					<?php foreach($user->assigned_tasks as $task): ?>
						<li><a href="/task/<?php echo $task->id; ?>"><?php echo $task->title; ?></a> assigned by <a href="/users/<?php echo $task->originator->username; ?>"><?php echo $task->originator->username; ?></a></li> 
					<?php endforeach; ?>
					</ul>
					<?php else: ?>
					<p><?php echo $user->username; ?> has no assigned tasks - <em>Lucky!</em></p>
					<?php endif; ?>
					<?php if ($current_user && $current_user->isMasterOf($user)): ?>
					<p><a href="/task/delegateto/<?php echo $user->username; ?>" class="btn">Delegate a task to this slave</a></p>
					<?php endif; ?>
				</div>
				<div class="tab-pane" id="delegated">
					<h2>Delegated Tasks</h2>
					<a href="" class="btn delegate-new-task">Delegate a task</a>
					<?php if(count($user->delegated_tasks) > 0): ?>
					<ul>
					<?php foreach($user->delegated_tasks as $task): ?>
						<li><a href="/task/<?php echo $task->id; ?>"><?php echo $task->title; ?><a/> delegated to <a href="/users/<?php echo $task->owner->username; ?>"><?php echo $task->owner->username; ?></a>	</li>
					<?php endforeach; ?>
					</ul>
					<?php else: ?>
						<p><?php echo $user->username; ?> has no tasks delegated</p>
					<?php endif; ?>
					<a href="/task/delegate" class="btn delegate-new-task">Delegate a task</a>
				</div>
			</div>
		</div>
	</div>
	<?php if($show_sidepanel): ?>
	<div class="span4">
		<div class="sidebar_block">
			<?php if (count($user->masters) > 0): ?>
			<h3><?php echo $user->username; ?>'s Masters</h3>
			<ul>
			<?php foreach($user->masters as $master): ?>
				<li><a href="/user/<?php echo $master->username; ?>"><?php echo $master->username; ?></a></li>
			<?php endforeach; ?>
			</ul>
			<?php else: ?>
				<h3><?php echo $user->username; ?> has no Master - <?php echo $user->username; ?> <em>is</em> the Task Master</h3>
			<?php endif; ?>			
		</div>

		<div class="sidebar_block">
			<?php if (count($user->slaves) > 0) : ?>
			<h3><?php echo $user->username; ?>'s Slaves</h3>
			<ul>
			<?php foreach($user->slaves as $slave): ?>
				<li><a href="<?php echo $slave->username; ?>"><?php echo $slave->username; ?></a></li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>