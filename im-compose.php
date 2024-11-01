	<?php
	$sql = 'SELECT ID,user_nicename FROM '.$wpdb->prefix.'users';
		$nicenames = $wpdb->get_results($sql);
		foreach($nicenames as $nicename){
			$im_user_options .= '<option value="'.$nicename->ID.'">'.$nicename->user_nicename.'</option>';
		}
	?>
	<form action="" id="internal_mail_send" method="post">
		<div style="min-width:100px;width:100px;max-width:100px;float:left;">User</div>	<select name="sendto"><?php echo $im_user_options;?></select><br>
		<div style="min-width:100px;width:100px;max-width:100px;float:left;">Subject</div> <input style="width:400px;" type="text" name="subject"><br>
		<div style="min-width:100px;width:100px;max-width:100px;float:left;">Message</div> <textarea name="message" style="width:650px;height:400px"></textarea><br>
		<input type="submit" name="submit" value="send">
	</form>