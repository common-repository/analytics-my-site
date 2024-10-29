<?php
/*
Plugin Name: Analytics My Site
Plugin URI: http://vlcmedia.blogspot.com/2011/06/google-analytics-plugin.html
Description: Allows you to enable Google Analytics stats on all of your pages!
Version: 1.0.1
Author: VLC Media
Author URI: http://vlcmedia.blogspot.com
*/

/*  Copyright 2010 vlcmedia.blogspot.com - support@vlcmedia.blogspot.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Hook for adding admin menus
add_action('admin_menu', 'google_analytics_add_pages');

// action function for above hook
function google_analytics_add_pages() {
    add_options_page('Analytics My Site', 'Analytics My Site', 'administrator', 'analytics', 'google_analytics_options_page');
}

// google_analytics_options_page() displays the page content for the Test Options submenu
function google_analytics_options_page() {

    // variables for the field and option names
    $opt_name_1 = 'mt_google_analytics_ID';	
    $opt_name_2 = 'mt_google_analytics_headfooter';
    $opt_name_5 = 'mt_google_analytics_plugin_support';
    $hidden_field_name = 'mt_google_analytics_submit_hidden';
	$data_field_name_1 = 'mt_google_analytics_ID';
	$data_field_name_2 = 'mt_google_analytics_headfooter';
    $data_field_name_5 = 'mt_google_analytics_plugin_support';

    // Read in existing option value from database
	$opt_val_1 = get_option($opt_name_1);
	$opt_val_2 = get_option($opt_name_2);
    $opt_val_5 = get_option($opt_name_5);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
		$opt_val_1 = $_POST[$data_field_name_1];
		$opt_val_2 = $_POST[$data_field_name_2];
        $opt_val_5 = $_POST[$data_field_name_5];

        // Save the posted value in the database
		update_option( $opt_name_1, $opt_val_1 );
		update_option( $opt_name_2, $opt_val_2 );
        update_option( $opt_name_5, $opt_val_5 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Analytics My Site Options', 'mt_trans_domain' ) . "</h2>";


    // options form
    
    $change3 = get_option("mt_google_analytics_plugin_support");
    $change4 = get_option("mt_google_analytics_headfooter");

if ($change3=="Yes" || $change3=="") {
$change3="checked";
$change31="";
} else {
$change3="";
$change31="checked";
}

if ($change4=="Yes") {
$change4="checked";
$change41="";
} else {
$change4="";
$change41="checked";
}

    ?>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

Your Analytics Site ID can be retrieved from Google Analytics, and is normally in the form: UA-xxxxxxxx-x.<br /><br />

<p><?php _e("Analytics Site ID:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_1; ?>" value="<?php echo $opt_val_1; ?>" />
</p><hr />

<p><?php _e("Analytics code in...", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_2; ?>" value="Yes" <?php echo $change4; ?>>Header
<input type="radio" name="<?php echo $data_field_name_2; ?>" value="No" <?php echo $change41; ?>>Footer (Recommended)
</p>

<p><?php _e("Support us with a link?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="Yes" <?php echo $change3; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="No" <?php echo $change31; ?>>No
</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<?php 
}

function show_Analytics() {
$id=get_option("mt_google_analytics_ID");

if ($id!="" && (!current_user_can('manage_options'))) {
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $id; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php
}

$supportplugin=get_option("mt_google_analytics_plugin_support");

if ($supportplugin=="Yes" || $supportplugin=="") {
if (get_option("mt_google_analytics_headfooter")=="Yes") {
add_action('wp_footer', 'google_analytics_footer_plugin_support');
} else {
google_analytics_footer_plugin_support();
}

}
}

function google_analytics_footer_plugin_support() {
$pshow = "<p style='font-size:x-small'>Analytics Plugin made by <a href='http://www.vlc-player-download.org'>VLC Media Player</a></p>";
  echo $pshow;
}

if (get_option("mt_google_analytics_headfooter")=="Yes") {
add_action('wp_head', 'show_Analytics');
} else {
add_action('wp_footer', 'show_Analytics');
}

?>
