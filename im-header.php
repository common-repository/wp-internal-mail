<div id="logo"></div><br>
	<div id="search_mail">
		<form action="" method="post">
			<input type="text" style="width:300px;" name="search" value=""><input type="submit" value="Search Mail">
		</form>
	</div>
	<br style="clear:both">
	
	<div id="mail_folders">
		<div id="compose"><a href="?page=grx3mail&action=compose">Compose</div>
		<ul>
			<li><a href="?page=grx3mail&folder=inbox">Inbox</a></li>
			<li><a href="?page=grx3mail&folder=sent">Sent</a></li>
			<li><a href="?page=grx3mail&folder=spam">Spam</a></li>
			<li><a href="?page=grx3mail&folder=Trash">Trash</a></li>
		</ul>
	</div>
	
	<div id="mail_panel" >
	<div id="toolbar_row">
		<div id="archive" class="button">
		<a onmousedown="menu_actions('archive')">Archive</a>
		</div>
		<div id="report_spam" class="button">
		<a onmousedown="menu_actions('spam')">Report Spam</a>
		</div>
		<div id="delete" class="button">
		<a onmousedown="menu_actions('delete')">Delete</a>
		</div>
		<div id="move_to" class="button" onmousedown="keepvisible('move_folders')">
		Move To <img src="<?php echo $url_plugin_directory?>images/mail.png" width=16 height=16>
		<div id="move_folders" onmouseover="keepvisible('move_folders')" onmouseout="hidenow('move_folders')">
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
		<div id="more_actions" class="button" onmousedown="keepvisible('more_actions_menu')">
		More Actions <img src="<?php echo $url_plugin_directory?>images/mail.png" width=16 height=16>
		<div id="more_actions_menu" onmouseover="keepvisible('more_actions_menu')" onmouseout="hidenow('more_actions_menu')">
			<ul>
			<li><a onmousedown="menu_actions('read')">Mark as read</a></li>
			<li><a onmousedown="menu_actions('unread')">Mark as unread</a></li>
			<li><a>Mute</a></li>
			
		</ul>
		</div>
		</div>
		<div id="refresh"><a href="?page=grx3mail&no_cache=<?php rand(1000,9999);?>">Refresh</a></div>
		<div id="pagination">1 - 50 of 63 <a href="">Older &raquo;</a></div>
	</div>
	<div id="select_row" style="clear:left">Select: <a>All,</a><a>None,</a><a>Read,</a><a>Unread</a></div>
	