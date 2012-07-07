<div id="debug-database" class="top" style="display: none;">
    <h1>SQL Queries</h1>
    <table cellspacing="0" cellpadding="0">
        <tr align="left">
            <th>#</th>
            <th>query</th>
            <th>time</th>
            <th>memory</th>
        </tr>
        <?php foreach (Debugger::get_database_queries() as $db_profile => $stats): ?>
        <tr align="left">
            <th colspan="4">DATABASE "<?php echo strtoupper($db_profile) ?>"</th>
        </tr>
        <?php foreach ($stats as $num => $query): ?>
            <tr class="<?php echo Text::alternate('odd', 'even') ?>">
                <td><?php echo $num + 1 ?></td>
                <td><?php echo HTML::entities($query['name']) ?></td>
                <td><?php echo number_format($query['time'] * 1000, 3) ?> ms</td>
                <td><?php echo number_format($query['memory'] / 1024, 3) ?> kb</td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</div>