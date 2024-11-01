<?php
/*
Plugin Name: WP Internal Mail
Plugin URI: http://grx3.com/wp_internal_mail
Description: Wordpress Internal Mail. Now have mail like gmail, facebook, yahoo on your site.  Users can send, organize, and recieve mail all on your site. Including more features inside.
Author: GRX3
Version: 1.1
Author URI: http://grx3.com
*/

/* Setup Style for Mail Page */
if(strstr($_SERVER['REQUEST_URI'],'grx3mail')){
	function mail_header_hook() {
    	$url_plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    	echo '<link rel="stylesheet" type="text/css" href="' . $url_plugin_directory . 'style.css" />';
		wp_enqueue_scripts('jquery');
		?>
		<script type="text/javascript">
			// Menu Functions
			function keepvisible(did){
				document.getElementById(did).style.display = 'block';
			}
			function hidenow(did){
				document.getElementById(did).style.display = 'none';
			}
			// Ajax Hooks for Menus
			function menu_actions(action_type){
				inputs = document.getElementsByTagName('input');
				for(var i =0; i< inputs.length; i++){
					if(inputs[i].id.indexOf('mail_id_') != -1 && inputs[i].checked == true){
						var data = {
							action: 'mail_menu_actions',
							mail_id: inputs[i].id,
							type: action_type
						};
						jQuery.post(ajaxurl, data, function(response) {
							// all actions for menus 
							//alert(action_type+':'+response);					
							if(response.indexOf(1) != -1){
								/* unread */
								if(data['type'] == 'unread'){
									mid = data['mail_id'].substr(8);
									mrname = 'message_row_'+mid;
									srname = 'sender_row_'+mid;
									hypen = document.getElementById(mrname).innerHTML.indexOf('&nbsp;-&nbsp;');
									document.getElementById(mrname).innerHTML = '<b>' + document.getElementById(mrname).innerHTML.substr(0,hypen)+'</b>-' + document.getElementById(mrname).innerHTML.substr(hypen) ;
									document.getElementById(srname).innerHTML = '<b>' + document.getElementById(srname).innerHTML + '</b>'; 						
								}
								
								/* read */
								if(data['type'] == 'read'){
									mid = data['mail_id'].substr(8);
									mrname = 'message_row_'+mid;
									srname = 'sender_row_'+mid;
									document.getElementById(mrname).innerHTML = document.getElementById(mrname).innerHTML.replace('<b>','');
									document.getElementById(mrname).innerHTML = document.getElementById(mrname).innerHTML.replace('</b>','');
									document.getElementById(srname).innerHTML = document.getElementById(srname).innerHTML.replace('<b>','');
									document.getElementById(srname).innerHTML = document.getElementById(srname).innerHTML.replace('</b>','');
								}
								
								/* delete */
								if(data['type'] == 'delete'){
									mid = data['mail_id'].substr(8);
									alert(data['mail_id']);
									
									mr =document.getElementById('mail_rows');
									mr_id = document.getElementById('mail_row_'+mid);
									mr.removeChild(mr_id);
								}
								
								/* spam */
								if(data['type'] == 'spam'){
									mid = data['mail_id'].substr(8);
									mr =document.getElementById('mail_rows');
									mr_id = document.getElementById('mail_row_'+mid);
									mr.removeChild(mr_id);
								}
								
								/* Archive */
								if(data['type'] == 'archive'){
									mid = data['mail_id'].substr(8);
									mr =document.getElementById('mail_rows');
									mr_id = document.getElementById('mail_row_'+mid);
									mr.removeChild(mr_id);
								}
								
								/* Inbox */
								if(data['type'] == 'inbox'){
									mid = data['mail_id'].substr(8);
									mr =document.getElementById('mail_rows');
									mr_id = document.getElementById('mail_row_'+mid);
									mr.removeChild(mr_id);
								}
								/* Sent */
								if(data['type'] == 'sent'){
									mid = data['mail_id'].substr(8);
									mr =document.getElementById('mail_rows');
									mr_id = document.getElementById('mail_row_'+mid);
									mr.removeChild(mr_id);
								}
							}
						
						}); // end ajax response function				
					}
				}
			}
			
		</script>
		<?php
	}

add_action('admin_head', 'mail_header_hook');
}

/* Main Page Hook Function -- Runs most of the actions */
function grx3mail_dashboard(){
	global $wpdb;
	global $user_ID;
	
	/*  Send Internal Email */
	if(isset($_POST['sendto'])){
		if(strlen($_POST['subject']) > 3 && strlen($_POST['message']) > 10){
			$args = array(
				'sendid'=>$_POST['sendto'],
				'userid'=>$user_ID,
				'subject'=>$_POST['subject'],
				'message'=>$_POST['message'],
				'unread'=>1);
			$wpdb->insert($wpdb->prefix.'internal_mail',$args);
			echo '<h2>Message Sent</h2>';
		}else{
			echo '<h2 style="color:red">Subject and Message need to be longer.</h2>';
		}
	}	
	
	/* Page Head */
	$url_plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	include('im-header.php');
	
	/* Main Mail Query */
	if(isset($_POST['search'])){
		/* Does search as well, greedy */
		$sql='SELECT m.unread,m.mail_id,u.user_nicename,m.subject,m.message,m.when,m.sendid FROM '.$wpdb->prefix.'users u,'.$wpdb->prefix.'internal_mail m WHERE u.ID = m.userid AND m.sendid = '.$user_ID . ' AND (m.subject LIKE "%'.$_POST['search'].'%"  OR m.message LIKE "%'.$_POST['search'].'%")';
	}elseif(isset($_GET['folder']) && $_GET['folder'] != 'inbox'){
		/* Folder query */
		$sql='SELECT m.unread,m.mail_id,u.user_nicename,m.subject,m.message,m.when,m.sendid FROM '.$wpdb->prefix.'users u,'.$wpdb->prefix.'internal_mail m, '.$wpdb->prefix.'internal_mail_folders f WHERE m.mail_id = f.mail_id AND u.ID = m.userid AND m.sendid = '.$user_ID . ' AND f.folder_name = "'.$_GET['folder'].'"';
	}else{
		/* Inbox query */
		$sql='SELECT m.unread,m.mail_id,u.user_nicename,m.subject,m.message,m.when,m.sendid FROM '.$wpdb->prefix.'users u,'.$wpdb->prefix.'internal_mail m WHERE u.ID = m.userid AND m.sendid = '.$user_ID . ' AND m.mail_id NOT IN (SELECT mail_id FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = m.mail_id)';
	}
	
	/* Default mail list view */
	if(!isset($_GET['mail_id']) && !isset($_GET['action'])){
		$sql .= ' LIMIT 50';
		include('im-listview.php');
	}elseif(!isset($_GET['action'])){
	/* Single Mail View */
		include('im-single.php');
	}
	/* Compose Form */
	if($_GET['action'] == 'compose'){
		?>
		<style type="text/css">
			#toolbar_row,#toolbar_row_footer,#select_row,#select_row_footer{display:none;}
		</style>
		<?php
		include('im-compose.php');
	}
	
	/* individual stats grab */
	$sql = 'SELECT count(mail_id) as rc FROM ' . $wpdb->prefix .'internal_mail WHERE sendid = '.$user_ID;
	$rows = $wpdb->get_var($sql);
	$bytes = $rows * 115; // Row Size from Mysql (mail table)
	$sql = 'SELECT count(f.mail_id) as rc FROM ' . $wpdb->prefix .'internal_mail_folders f,' . $wpdb->prefix .'internal_mail m WHERE f.mail_id = m.mail_id AND m.sendid = '.$user_ID;
	$rows = $wpdb->get_var($sql);
	$bytes += $rows * 1044 ; // Row Size from Mysql (folders table)
	$kb = round($bytes * .0009765625).' KB'; //kb
	$mail_quota = get_option('mail_quota');
	$quota_percent = round($kb / ($mail_quota * 1000));
	
	/* Page footer */
	include('im-footer.php');	
	
}

/* Google time format */
function gtime($t){
	/* Convert to Gmail-like time */
	$ts = strtotime($t);
	$today = getdate();
	if(date('j',$ts) == $today['mday']){
		return date('H:i a',$ts);
	}else
		return date('M j',$ts);
}

/*  Add Wordpress Mail Menu and Unread Count */
function add_menus(){
	global $wpdb;
	global $user_ID;
	
	/* Mail Count */
	$sql='SELECT count(m.mail_id) as numunread FROM '.$wpdb->prefix.'internal_mail m WHERE m.unread = 1 AND m.sendid = '.$user_ID;
	$numunread = $wpdb->get_var($sql);
	
	$menu_cap = 'read';
	
	add_menu_page( __('GRX3 Mail','grx3mail'),__('GRX3 Mail','grx3mail'), $menu_cap, 'grx3mail', 'grx3mail_dashboard',WP_PLUGIN_URL.'/wp-internal-mail/images/menu_icon.gif'); 
		
	/* Menus */
	if($numunread > 0){
		add_submenu_page( 'grx3mail', __('Mail','grx3mail'),__('Mail ('.$numunread.')'),$menu_cap, 'grx3mail', 'grx3mail_dashboard');
	}else{
		add_submenu_page( 'grx3mail', __('Mail','grx3mail'),__('Mail'),$menu_cap, 'grx3mail', 'grx3mail_dashboard');
	}
	add_submenu_page( 'grx3mail', __('Settings','grx3mail'),__('Settings'),$menu_cap, 'grx3mail_settings', 'grx3mail_settings');
}
/* Mail Setings */
function grx3mail_settings(){
	/* Page Head */
	$url_plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	include('im-header.php');
	
	?>
	<style type="text/css">
		#toolbar_row,#toolbar_row_footer,#select_row,#select_row_footer{display:none;}
		#mail_panel{background-color:#FF9933;}
	</style>
	<div id="mail_panel">
		<form action="?page=grx3mail_settings" method="post">
		</form>
	</div>
	<?php
	
	include('im-footer.php');
}
/* Runs on action and installs, upgrades tables */
function activation_init(){
	global $wpdb;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	/*install table if one doesn't exist, else upgrade */
   	$table_name = $wpdb->prefix . "internal_mail";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE  $table_name (
		`mail_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`userid` INT NOT NULL ,
		`sendid` INT NOT NULL ,
		`subject` VARCHAR( 50 ) NOT NULL ,
		`message` MEDIUMTEXT NOT NULL ,
		`unread` INT NOT NULL DEFAULT 0,
		`when` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		 );";	
		dbDelta($sql);
	}
	$table_name_meta = $wpdb->prefix . "internal_mail_folders";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name_meta'") != $table_name_meta) {
		$sql = "CREATE TABLE  $table_name_meta (
	`mail_id` INT NOT NULL ,
	`folder_name` VARCHAR( 40 ) NOT NULL
	)";
		dbDelta($sql);
	}
	/* Install Default data */
	$welcome_subject = "GRX3";
    $welcome_text = "Congratulations, you just completed the installation of internal_mail! ";

  $rows_affected = $wpdb->insert( $table_name, array( 'unread'=>1,'when' => current_time('mysql'), 'subject' => $welcome_subject, 'message' => $welcome_text, 'userid'=>1,'sendid'=>1 ) );
  
  	/* Default Settings */
	update_option('mail_quota','10'); // 10 Megs
 
}
/* Ajax Functions  for Menus */
function mail_menu_actions(){
	global $wpdb;
	global $user_ID;
	
	$mail_id = substr($_POST['mail_id'],strpos($_POST['mail_id'],'_id_')+4);
	
	switch($_POST['type']){
		case 'unread':
			$sql = 'UPDATE '.$wpdb->prefix.'internal_mail SET unread = 1 WHERE mail_id = '.$mail_id;
			break;
		case 'read':
			$sql = 'UPDATE '.$wpdb->prefix.'internal_mail SET unread = 0 WHERE mail_id = '.$mail_id;
			break;
		case 'delete':
			$sql = 'DELETE FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = '.$mail_id;
			$wpdb->query($sql);
			$sql = 'INSERT INTO '.$wpdb->prefix.'internal_mail_folders(mail_id,folder_name) VALUES ('.$mail_id.',"trash")';
			break;
		case 'spam':
			$sql = 'DELETE FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = '.$mail_id;
			$wpdb->query($sql);
			$sql = 'INSERT INTO '.$wpdb->prefix.'internal_mail_folders(mail_id,folder_name) VALUES ('.$mail_id.',"spam")';
			break;
		case 'archive':
			$sql = 'DELETE FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = '.$mail_id;
			$wpdb->query($sql);
			$sql = 'INSERT INTO '.$wpdb->prefix.'internal_mail_folders(mail_id,folder_name) VALUES ('.$mail_id.',"archive")';
			break;
		case 'inbox':
			$sql = 'DELETE FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = '.$mail_id;
			break;
		case 'sent':
			$sql = 'DELETE FROM '.$wpdb->prefix.'internal_mail_folders WHERE mail_id = '.$mail_id;
			$wpdb->query($sql);
			$sql = 'INSERT INTO '.$wpdb->prefix.'internal_mail_folders(mail_id,folder_name) VALUES ('.$mail_id.',"sent")';
			break;
	}
	
	$res = $wpdb->query($sql);
	if($res != false)
		echo 1; // returns ajax
	else{
		echo $sql; 
		$wpdb->print_error();
	}
	exit; //stop extra return values	
}

/* Wordpress Hooks */
add_action('wp_ajax_mail_menu_actions','mail_menu_actions');
register_activation_hook(__FILE__,'activation_init');
add_action('admin_menu','add_menus');
?>