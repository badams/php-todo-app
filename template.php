<!DOCTYPE html>
<html>
	<head>
		<title>Todo List</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">	
		<link rel="stylesheet" href="http://getbootstrap.com/dist/css/bootstrap-theme.min.css">	
		<style>
			#todo_list {border-radius: 0px;}
			.list-group-item p { font-size: 1.2em;}
			.list-group-item.done p {text-decoration: line-through; color: #888;}
			footer {text-align:center;}
		</style>		
	</head>	
	<body>
		<div id="todo_list" class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title"><div class="glyphicon glyphicon-time"></div> Todo List</h2>
			</div>
			<div class="panel-body">
				<?php foreach ($this->errors as $error): ?>
					<div class="alert alert-danger"><?php echo $error; ?></div>
				<?php endforeach; ?>
				<form method="POST" class="form-inline" role="form">
					<div class="form-group">
						<input type="hidden" name="action" value="add">
						<input class="form-control" type="text" name="title" placeholder="Something I need to do...">
					</div>
					
					<button class="btn btn-primary " type="submit">
						<span class="glyphicon glyphicon-plus-sign"></span> Add Todo
					</button>
				</form>
			</div>			
			<ul class="list-group">
			<?php foreach ($this->get_items() as $item): ?>
				<li class="clearfix list-group-item <?php if ($item['done']) echo 'done'?>">
					<form method="POST" class="form-inline pull-right">
						<input type="hidden" name="item" value="<?php echo $item['id']; ?>">
						<? if (!$item['done']): ?>
							<button class="btn btn-success" type="submit" name="action" value="done">
								<span class="glyphicon glyphicon-check"></span> Finish
							</button>
							
						<? endif; ?>
						<button class="btn btn-danger" name="action" value="delete" type="submit">
							<span class="glyphicon glyphicon-remove-circle"></span>
						</button>
					</form>
					<p><?php echo htmlspecialchars($item['title']);?></p>
				</li>
			<?php endforeach; ?>	
			</ul>
		</div>
		<footer>
			<p><a href="http://github.com/badams/php-todo-app" class="btn btn-default"> View the code on Github.</a></p>
		</footer>
	</body>
</html>
