		<?php
		$mails = $wpdb->get_results($sql);
		echo '<div id="mail_rows">';
		foreach($mails as $mail){
		?>
		<div class="mail_row" id="mail_row_<?php echo $mail->mail_id;?>" >
			<div id="checkbox_row_<?php echo $mail->mail_id;?>" class="checkbox">
				<input type="checkbox" name="mail_id_<?php echo $mail->mail_id;?>" id="mail_id_<?php echo $mail->mail_id;?>">
			</div>
			<div id="sender_row_<?php echo $mail->mail_id;?>" class="sender">
			<?php 
			if($mail->unread == 1)
				echo '<b><a href="?page=grx3mail&mail_id='.$mail->mail_id.'">'.$mail->user_nicename.'</a></b>';
			else
				echo '<a href="?page=grx3mail&mail_id='.$mail->mail_id.'">'.$mail->user_nicename.'</a>';
			?>
			</div>
			<div id="message_row_<?php echo $mail->mail_id;?>" class="message">
			<?php 
			if($mail->unread == 1)
				echo '<a href="?page=grx3mail&mail_id='.$mail->mail_id.'">'.substr('<b>'.$mail->subject.'</b>&nbsp;-&nbsp;'.$mail->message,0,60).'</a>';
			else
				echo '<a href="?page=grx3mail&mail_id='.$mail->mail_id.'">'.substr($mail->subject.'&nbsp;-&nbsp;'.$mail->message,0,60).'</a>';
			?>
			</div>
			<div id="when_row_<?php echo $mail->mail_id;?>" class="when">
			<?php echo '<a href="?page=grx3mail&mail_id='.$mail->mail_id.'">'.gtime($mail->when).'</a>';?>
			</div>
		</div>
		<?php
		}
		?></div>
