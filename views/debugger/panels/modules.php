<div id="debug-modules" class="top" style="display: none;">
	<h1>Modules</h1>
	<table cellspacing="0" cellpadding="0">
		<tr align="left">
			<th>name</th>
			<th>path</th>
		</tr>
		<?php foreach (Debugger::get_modules() as $name => $path): ?>
		<tr class="<?php echo Text::alternate('odd', 'even')?>">
			<td><?php echo $name ?></td>
			<td><?php echo $path ?></td>
		</tr>
		<?php endforeach ?>
	</table>
</div>