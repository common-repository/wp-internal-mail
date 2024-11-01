<div id="select_row_footer">Select: <a>All,</a><a>None,</a><a>Read,</a><a>Unread</a></div>
<div id="toolbar_row_footer">
		<div id="archive_footer" class="button">
		<a onmousedown="menu_actions('archive')">Archive</a>
		</div>
		<div id="report_spam_footer" class="button">
		<a onmousedown="menu_actions('spam')">Report Spam</a>
		</div>
		<div id="delete_footer" class="button">
		<a onmousedown="menu_actions('delete')">Delete</a>
		</div>
		<div id="move_to_footer" class="button" onmousedown="keepvisible('move_folders_footer')">
		Move To <img src="<?php echo $url_plugin_directory?>images/mail.png" width=16 height=16>
		<div id="move_folders_footer" onmouseover="keepvisible('move_folders_footer')" onmouseout="hidenow('move_folders_footer')">
			<ul>
			<li><a onmousedown="menu_actions('inbox')">Inbox</a></li>
			<li><a onmousedown="menu_actions('sent')">Sent</a></li>
			<li><a onmousedown="menu_actions('spam')">Spam</a></li>
			<li><a onmousedown="menu_actions('delete')">Trash</a></li>
			<li><hr /></li>
			<li><a>Manage Folders</a></li>
		</ul>
		</div>
		</div>
		<div id="more_actions_footer" class="button" onmousedown="keepvisible('more_actions_menu_footer')">
		More Actions <img src="<?php echo $url_plugin_directory?>images/mail.png" width=16 height=16>
		<div id="more_actions_menu_footer" onmouseover="keepvisible('more_actions_menu_footer')" onmouseout="hidenow('more_actions_menu_footer')">
			<ul>
			<li><a onmousedown="menu_actions('read')">Mark as read</a></li>
			<li><a onmousedown="menu_actions('unread')">Mark as unread</a></li>
			<li><a>Mute</a></li>
			
		</ul>
		</div>
		</div>
		<div id="refresh_footer"><a href="?page=grx3mail&no_cache=<?php rand(1000,9999);?>">Refresh</a></div>
		<div id="pagination_footer">1 - 50 of 63 <a href="">Older &raquo;</a></div>
	</div></div>
<div style="clear:both"></div>
<div id="mail_footer">
Visit <a href="?page=grx3mail_settings">Settings</a> to better configure your mail client<br />
<br />
<h3>Your are currently using <?php echo $kb; ?> (<?php echo $quota_percent;?>%) of your <?php echo $mail_quota;?> MB</h3>
</div>