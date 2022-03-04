<?php
/**
* Plugin Name:  Tripletex to woocomerce andpercent
* Plugin Uri:
* Author: Faisal Abbas Khan
* Author Uri:
* Version: 1.0.0
* Description: This Plugin is  integration of   Tripletex And Woocomerce Api
*
* Tags:
* License: GPL V2
*
*
*/
// wp-admin/admin.php?page=trptx_to_woocomerce
///////////////////////////  Constant Variables use ////////////////////
defined('ABSPATH') || die("You Can't Access this File Directly");
define('TRPTX_TO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TRPTX_TO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TRPTX_TO_PLUGIN_FILE', __FILE__);
define('TRPTX_TO_PLUGIN_SITE_URL',get_site_url());
define('TRPTX_TO_PLUGIN_PO_API_URL',get_option('TRPTX_TO_PLUGIN_PO_API_URL'));
define('TRPTX_TO_PLUGIN_WOO_AUTH_KEY',get_option('trptx_woo_api_key'));
define('TRPTX_TO_PLUGIN_AUTH_KEY',get_option('trptx_key'));
define('TRPTX_CHFS_VALIDATE_API_URL','http://abc4741.sg-host.com');
define('TRPTX_CHFS_VALIDATE_API_PLUGIN_ID',4);
////////////////////// Files and Code Render On Admin dashboard ///////////////////////

if (is_admin()) {
require 'plugin-update-checker-master/plugin-update-checker.php';
include TRPTX_TO_PLUGIN_PATH."inc/update_plugin_from_git.php";
include TRPTX_TO_PLUGIN_PATH."inc/admin_dashboard.php";

}

////////////////////// Files For Front end  ///////////////////////////////////////////
include TRPTX_TO_PLUGIN_PATH."inc/conection_to_trptx.php";
include TRPTX_TO_PLUGIN_PATH."inc/woocomerce_apiconection.php";

////////////////////////////////////////////////////////////////////////////////////////////

/**
* Check if WooCommerce is active
**/
// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
// // Put your plugin code here
// add_action('woocommerce_loaded' , function (){
// /////////////////  Token PowerOffice Access/////////////////////////////////////////
// if ($tokenAccess!=false ) {
	// 	$postsDataplug = wp_remote_retrieve_body(wp_remote_get(TRPTX_CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.TRPTX_TRPTX_CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));
	
	// 	$postsgetPlug = json_decode($postsDataplug);
	// 	if (get_option('TRPTX_TO_PLUGIN_AUTH_KEY')==$postsgetPlug->key) {
	// 	//include TRPTX_TO_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_woocomerce_hooks_triger.php";
	// 	}
// }else{
// }
// });
// }
///////////////////////////////////////////////////  Aactive Plugin /////////////////////////
register_activation_hook(__FILE__, function(){
	
add_option('trptx_woo_api_key', '');
add_option('trptx_Modedev', 'on');
add_option('trptx_key', '');
add_option('TRPTX_TO_PLUGIN_PO_API_URL', 'https://api-demo.poweroffice.net');
add_option('TRPTX_TO_PLUGIN_AUTH_KEY', '');
$postPluginData = wp_remote_retrieve_body(wp_remote_post(TRPTX_CHFS_VALIDATE_API_URL.'/api/sendSiteData', [
'body' =>['id' => TRPTX_CHFS_VALIDATE_API_PLUGIN_ID,
'domain' =>$_SERVER['HTTP_HOST'],
'is_del' => 'no',
'is_active' => 'yes'],
'method' => 'POST',
'content-type' => 'application/json',
]));
$resultPlugin=json_decode($postPluginData);
});
///////////////////////////////////////////////////  Deactive Plugin /////////////////////////
register_deactivation_hook(__FILE__, function(){
	$postPluginData = wp_remote_retrieve_body(wp_remote_post(TRPTX_CHFS_VALIDATE_API_URL.'/api/sendSiteData', [
		'body' =>['id' => TRPTX_CHFS_VALIDATE_API_PLUGIN_ID,
		'domain' =>$_SERVER['HTTP_HOST'],
		'is_del' => 'no',
		'is_active' => 'no'],
		'method' => 'POST',
		'content-type' => 'application/json',
		]));
		$resultPlugin=json_decode($postPluginData);
	
});
//////////////////////////////////////  Activation key Of Plugin //////////////////////
add_action("wp_ajax_frontend_action_trptx_activation_plugin" , "frontend_action_trptx_activation_plugin");
add_action("wp_ajax_nopriv_frontend_action_trptx_activation_plugin" , "frontend_action_trptx_activation_plugin");
function frontend_action_trptx_activation_plugin(){
if(isset($_POST['activation_key'])){
update_option('TRPTX_TO_PLUGIN_AUTH_KEY', sanitize_text_field($_POST['activation_key']));
echo  json_encode(['status'=>400]);
wp_die();
}
}