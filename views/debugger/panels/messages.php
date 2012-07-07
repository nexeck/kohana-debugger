<div id="debug-messages" class="top" style="display: none;">
	<h1>Messages</h1>
	<table>
		<tbody>
		<tr>
			<th>#</th>
			<th>file</th>
			<th>line</th>
			<th>class</th>
			<th>function</th>
			<th>message</th>
		</tr>
		<?php foreach (Debugger::get_messages() as $id => $message): ?>
		<tr class="<?php echo Text::alternate('odd', 'even')?>">
			<td><?php echo '#' . ($id + 1) ?></td>
			<td><?php echo $message['file'] ?></td>
			<td><?php echo $message['line'] ?></td>
			<td><?php echo $message['class'] ?></td>
			<td><?php echo $message['function'] ?></td>
			<td><?php echo $message['message'] ?></td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>