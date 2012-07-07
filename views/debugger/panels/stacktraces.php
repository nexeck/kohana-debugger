<div id="debug-stacktraces" class="top" style="display: none;">
	<h1>Stacktraces</h1>
	<table>
		<tbody>
		<tr>
			<th>#</th>
			<th>stacktrace</th>
		</tr>
		<?php foreach (Debugger::get_stacktraces() as $id => $stacktrace): ?>
		<tr class="<?php echo Text::alternate('odd', 'even')?>">
			<td><?php echo '#' . ($id + 1) ?></td>
			<td>
				<pre><?php echo $stacktrace ?></pre>
			</td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>