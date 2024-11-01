<?php
$sql .= ' AND mail_id = '.$_GET['mail_id'];

	$mail = $wpdb->get_row($sql);
	
	?>
	<div id="mail_panel" style="background-color:white;">
	<div id="mail_panel_header">
		<?php echo $mail->user_nicename;?> to me <?php echo gtime($mail->when);?> <a href="?page=grx3mail&action=compose&sender=<?php echo $mail->sendid;?>">Reply</a>
	</div>
	<div id="mail_content">
	<?php echo $mail->message; ?>
	</div>
	</div>
	<?php
	$sql = 'UPDATE '.$wpdb->prefix.'internal_mail SET unread = 0 WHERE mail_id = '.$_GET['mail_id'];
	$wpdb->query($sql);
	?>