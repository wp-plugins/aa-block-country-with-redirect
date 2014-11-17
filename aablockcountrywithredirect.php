<?php 
/**
 * Plugin Name: AA Block country with redirect
 * Plugin URI: https://wordpress.org/plugins/aa-block-country-with-redirect/
 * Description: It's a nice plugin with which you can redirect your visitor according to their country. You just include country code by settings.
 * Version: 1.0
 * Developer + Idea: A. Roy / A. Mahmud
 * Author URI: http://webdesigncr3ator.com
 * Support Email : contact2us.aa@gmail.com
 * License: GPL2
 **/
	
		
		
function aa_redire_block_getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

$ip = aa_redire_block_getUserIP();


$json = file_get_contents('http://ip-api.com/json/'.aa_redire_block_getUserIP());
			$obj = json_decode($json);
			

//get user country	
$usr_country = $obj->countryCode;

$block_country = get_option('block_country');
$block_country = explode(",", $block_country);


if(isset($block_country[$usr_country])){
	wp_die('    <META http-equiv="refresh" content="2;URL='.get_option('redirect_block_url').'"> You are about to redirect');
}


// create custom plugin settings menu
add_action('admin_menu', 'aa_redire_add_menu_block_con');

function aa_redire_add_menu_block_con() {

	//create new top-level menu
	add_menu_page('Block Country With redirect', 'Block  redirect Settings', 'administrator', __FILE__, 'aa_redire_ba_baw_settings_page');

	//call register settings function
	add_action( 'admin_init', 'aa_redire_register_mysettings' );
}


function aa_redire_register_mysettings() {

	register_setting( 'baw-settings-group', 'block_country' );
	register_setting( 'baw-settings-group', 'redirect_block_url' );

}

function aa_redire_ba_baw_settings_page() {


?>
<div class="wrap">
<h2>Comming soon</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'baw-settings-group' ); ?>
    <?php do_settings_sections( 'baw-settings-group' ); ?>
 
	Enter Block country name (Country code) with comma to sepparate
	<input type="text" name="block_country" value="<?php echo esc_attr( get_option('block_country') ); ?>" /><br>
	Redirect url
	<br>
	<input type="text" name="redirect_block_url" value="<?php echo esc_attr( get_option('redirect_block_url') ); ?>" />
           
  
    <?php submit_button(); ?>

</form>
</div>
<?php }