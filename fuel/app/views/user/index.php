<div class="row">
	<div class="span2">
		<?php if (isset($current_user->avatar_url)): ?>
		<img src="<?php echo $current_user->avatar_url; ?>" class="img-polaroid" width="100%">
		<?php else: ?>
		<img src="/assets/img/missing_avatar.jpg" class="img-polaroid" width="100%">
		<?php endif; ?>
	</div>
	<div class="span10">
		<h1><?php echo $current_user->username; ?></h1>
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
					<?php if (count($current_user->assigned_tasks) > 0): ?>
					<ul>
					<?php foreach($current_user->assigned_tasks as $task): ?>
						<li><?php echo $task->title; ?></li> 
					<?php endforeach; ?>
					</ul>
					<?php else: ?>
					<p>You have no assigned tasks - <em>Phew!</em></p>
					<?php endif; ?>
				</div>
				<div class="tab-pane" id="delegated">
					<h2>Delegated Tasks</h2>
					<a href="" class="btn delegate-new-task">Delegate a task</a>
					<?php if(count($current_user->delegated_tasks) > 0): ?>
					<ul>
					<?php foreach($current_user->delegated_tasks as $task): ?>
						<li><?php echo $task->title; ?></li>
					<?php endforeach; ?>
					</ul>
					<?php else: ?>
						<p>You have no tasks delegated</p>
					<?php endif; ?>
					<a href="" class="btn delegate-new-task">Delegate a task</a>
				</div>
			</div>
		</div>
	</div>
	<?php if($show_sidepanel): ?>
	<div class="span4">
		<div class="sidebar_block">
			<?php if (count($current_user->masters) > 0): ?>
			<h3>Your Masters</h3>
			<ul>
			<?php foreach($current_user->masters as $master): ?>
				<li><a href="/user/<?php echo $master->username; ?>"><?php echo $master->username; ?></a></li>
			<?php endforeach; ?>
			</ul>
			<?php else: ?>
				<h3>You have no Master - You <em>are</em> the Task Master</h3>
			<?php endif; ?>			
		</div>

		<div class="sidebar_block">
			<?php if (count($current_user->slaves) > 0) : ?>
			<h3>Your Slaves</h3>
			<ul>
			<?php foreach($current_user->slaves as $slave): ?>
				<li><a href="<?php echo $slave->username; ?>"><?php echo $slave->username; ?></a></li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>