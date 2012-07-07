<div id="debug-routes" class="top" style="display: none;">
	<h1>Routes</h1>
	<table cellspacing="0" cellpadding="0">
		<tr align="left">
			<th>Name</th>
			<th>URI</th>
			<th>Route Regex</th>
		</tr>
		<?php foreach (Debugger::get_routes() as $route): ?>
		<tr class="<?php echo Text::alternate('odd', 'even') . ($route['current'] ? ' current' : '')?>">
			<td><?php echo $route['name'] ?></td>
			<td>
				<pre><?php echo HTML::entities($route['uri']) ?></pre>
			</td>
			<td>
				<pre><?php echo HTML::entities($route['route_regex']) ?></pre>
			</td>
		</tr>
		<?php endforeach ?>
	</table>
</div>