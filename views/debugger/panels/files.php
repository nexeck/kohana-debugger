<div id="debug-files" class="top" style="display: none;">
	<h1>Files</h1>
	<table cellspacing="0" cellpadding="0">
		<tr align="left">
			<th>#</th>
			<th>file</th>
			<th>size</th>
			<th>lines</th>
		</tr>
		<?php $total_size = $total_lines = 0 ?>
		<?php foreach (Debugger::get_files() as $id => $file): ?>
		<tr class="<?php echo Text::alternate('odd', 'even')?>">
			<td><?php echo $id ?></td>
			<td><?php echo $file['path'] ?></td>
			<td><?php echo Text::bytes($file['size']) ?></td>
			<td><?php echo $file['lines'] ?></td>
		</tr>
		<?php $total_size += $file['size']; ?>
		<?php $total_lines += $file['lines']; ?>
		<?php endforeach; ?>
		<tr align="left">
			<th colspan="2">total</th>
			<th><?php echo Text::bytes($total_size) ?></th>
			<th><?php echo number_format($total_lines) ?></th>
		</tr>
	</table>
</div>