<?php
/*
Plugin Name: WP-Christmas german
Plugin URI: http://www.weihnachtsgeschenk.com/
Description: WP-Christmas german l&auml;sst Schneeflocken &uuml;ber Deinen Blog rieseln. Basierend auf dem DHTML Snowstorm Skript von <cite><a href="http://www.schillmania.com/projects/snowstorm/" title="DHTML Snowstorm">Scott Schiller</a>.</cite>
Version: 1.0
Author: Kai
Author URI: http://www.weihnachtsgeschenk.com/
*/
function snow_options() {
	add_menu_page('WP-Christmas', 'WP-Christmas', 8, basename(__FILE__), 'snow_options_page');
	add_submenu_page(basename(__FILE__), 'Einstellungen', 'Einstellungen', 8, basename(__FILE__), 'snow_options_page');
}
?>
<?php function snow_options_page() { ?>

<div class="wrap">
    
    <div class="icon32" id="icon-options-general"><br/></div><h2>Einstellungen f&uuml;r WP-Christmas</h2>

    <form method="post" action="options.php">

	    <?php
	        // New way of setting the fields, for WP 2.7 and newer
	        if(function_exists('settings_fields')){
	            settings_fields('snow-options');
	        } else {
	            wp_nonce_field('update-options');
	            ?>
	            <input type="hidden" name="action" value="update" />
	            <input type="hidden" name="page_options" value="sflakesMax,sflakesMaxActive,svMaxX,svMaxY,ssnowStick,sfollowMouse" />
	            <?php
	        }
	    ?>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="sflakesMax">Max. Anzahl Schneeflocken</label></th>
				<td>
					<input type="text" name="sflakesMax" value="<?php echo get_option('sflakesMax'); ?>" size="10" />
					<span class="description">Max. Anzahl Schneeflocken die sich auf dem Bildschirm befinden</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sflakesMaxActive">Max. Anzahl aktiver Schneeflocken</label></th>
				<td>
					<input type="text" name="sflakesMaxActive" value="<?php echo get_option('sflakesMaxActive'); ?>" size="10" />
					<span class="description">Max. Anzahl aktiver Schneeflocken die sich bewegen</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="svMaxX">Schneefall max. Geschwindigkeit (horizontal)</label></th>
				<td>
					<input type="text" name="svMaxX" value="<?php echo get_option('svMaxX'); ?>" size="5" />
					<span class="description">Sollte zwischen 1 - 10 sein</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="svMaxY">Schneefall max. Geschwindigkeit (vertikal)</label></th>
				<td>
					<input type="text" name="svMaxY" value="<?php echo get_option('svMaxY'); ?>" size="5" />
					<span class="description">Sollte zwischen 1 - 10 sein</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="ssnowStick">Schnee liegenbleibt?</label></th>
				<td>
					<select name="ssnowStick">
                		<option <?php if (get_option('ssnowStick') == '1') echo 'selected="selected"'; ?> value="1">Ja</option>
                		<option <?php if (get_option('ssnowStick') == '0') echo 'selected="selected"'; ?> value="0">Nein</option> 
                	</select>
                	<span class="description">Soll der Schnee auf dem unteren Browserrand liegenbleiben?</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sfollowMouse">Der Maus folgen?</label></th>
				<td>
					<select name="sfollowMouse">
                		<option <?php if (get_option('sfollowMouse') == '1') echo 'selected="selected"'; ?> value="1">Ja</option>
                		<option <?php if (get_option('sfollowMouse') == '0') echo 'selected="selected"'; ?> value="0">Nein</option> 
                	</select>
                	<span class="description">Sollen die Schneeflocken der Maus folgen?</span>
				</td>
			</tr>
		</table>
		<p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" class="button-primary" />
        </p>

    </form>
    
</div>
<?php } ?>
<?php
// On access of the admin page, register these variables (required for WP 2.7 & newer)
function snow_init(){
    if(function_exists('register_setting')){
        register_setting('snow-options', 'sflakesMax');
        register_setting('snow-options', 'sflakesMaxActive'); 
        register_setting('snow-options', 'svMaxX'); 
        register_setting('snow-options', 'svMaxY');
        register_setting('snow-options', 'ssnowStick');
        register_setting('snow-options', 'sfollowMouse');
    }
}

// Only all the admin options if the user is an admin
if(is_admin()){
    add_action('admin_menu', 'snow_options');
    add_action('admin_init', 'snow_init');
}

//Set the default options when the plugin is activated
function snow_activate(){
    add_option('sflakesMax', 64);
    add_option('sflakesMaxActive', 64);
    add_option('svMaxX', 2);  
    add_option('svMaxY', 3);
    add_option('ssnowStick', 1);  
    add_option('sfollowMouse', 0);
}

register_activation_hook( __FILE__, 'snow_activate' );

function let_it_snow() {
	// Path for snow images
	$snowPath = get_option('siteurl').'/wp-content/plugins/wp-christmas-german/';

	$snowJS =	'<script type="text/javascript">
				sitePath = "'.$snowPath.'";
				sflakesMax = '.get_option('sflakesMax').';
				sflakesMaxActive = '.get_option('sflakesMaxActive').';
				svMaxX = '.get_option('svMaxX').';
				svMaxY = '.get_option('svMaxY').';
				ssnowStick = '.get_option('ssnowStick').';
				sfollowMouse = '.get_option('sfollowMouse').';
				</script>';

	$snowJS .= '<script type="text/javascript" src="'.$snowPath.'script/snowstorm.js"></script>'."\n";
	
	print($snowJS);
}
add_action('wp_head', 'let_it_snow');
?>