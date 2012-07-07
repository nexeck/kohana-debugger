<div id="debug-vars" class="top" style="display: none;">
	<h1>Vars</h1>
	<ul class="varmenu">
		<li onclick="debugToolbar.showvar(this, 'vars-param'); return false;">PARAM</li>
		<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
		<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
		<li onclick="debugToolbar.showvar(this, 'vars-files'); return false;">FILES</li>
		<li onclick="debugToolbar.showvar(this, 'vars-server'); return false;">SERVER</li>
		<li onclick="debugToolbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
		<li onclick="debugToolbar.showvar(this, 'vars-session'); return false;">SESSION</li>
	</ul>
	<div style="display: none;" id="vars-param">
		<?php echo Debug::vars(Debugger::get_param()) ?>
	</div>
	<div style="display: none;" id="vars-post">
		<?php echo Debug::vars(Debugger::get_post()) ?>
	</div>
	<div style="display: none;" id="vars-get">
		<?php echo Debug::vars(Debugger::get_query()) ?>
	</div>
	<div style="display: none;" id="vars-files">
		<?php echo isset($_FILES) ? Debug::vars($_FILES) : Debug::vars(array()) ?>
	</div>
	<div style="display: none;" id="vars-server">
		<?php echo isset($_SERVER) ? Debug::vars($_SERVER) : Debug::vars(array()) ?>
	</div>
	<div style="display: none;" id="vars-cookie">
		<?php echo Session::instance()->as_array() ?>
	</div>
	<div style="display: none;" id="vars-session">
		<?php echo Debug::vars(Debugger::get_session()) ?>
	</div>
</div>