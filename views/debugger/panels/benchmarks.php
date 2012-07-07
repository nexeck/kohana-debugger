<div id="debug-benchmarks" class="top" style="display: none;">
	<h1>Benchmarks</h1>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<th align="left">benchmark</th>
			<th align="right">count</th>
			<th align="right">avg time</th>
			<th align="right">total time</th>
			<th align="right">avg memory</th>
			<th align="right">total memory</th>
		</tr>
		<?php if (count(Debugger::get_benchmark_groups())): ?>
		<?php foreach (Debugger::get_benchmark_groups() as $group => $marks): ?>
			<tr>
				<th colspan="6"><?php echo $group?></th>
			</tr>
			<?php foreach ($marks as $benchmark): ?>
				<tr class="<?php echo Text::alternate('odd', 'even')?>">
					<td align="left"><?php echo $benchmark['name'] ?></td>
					<td align="right"><?php echo $benchmark['count'] ?></td>
					<td align="right"><?php echo sprintf('%.2f', $benchmark['stats']['average']['time'] * 1000) ?> ms</td>
					<td align="right"><?php echo sprintf('%.2f', $benchmark['stats']['total']['time'] * 1000) ?> ms</td>
					<td align="right"><?php echo Text::bytes($benchmark['stats']['average']['memory']) ?></td>
					<td align="right"><?php echo Text::bytes($benchmark['stats']['total']['memory']) ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>

		<?php else: ?>
		<tr class="<?php echo Text::alternate('odd', 'even') ?>">
			<td colspan="6">no benchmarks to display</td>
		</tr>
		<?php endif ?>
	</table>
</div>