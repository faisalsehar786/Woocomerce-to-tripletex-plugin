<?php
/**
* Plugin Name:  Tripletex to woocomerce
* Plugin Uri:
* Author: CreativeHeads
* Author Uri:
* Version: 1.0.0
* Description: This Plugin is integration of tripletex api And Woocomerce Api Get The Orders,Products and  Customer From  Woocomerce Send to  tripletex .
*
* Tags:
* License: GPL V2
*
*
*/
// wp-admin/admin.php?page=trptx_to_woocomerce
///////////////////////////  Constant Variables use ////////////////////
defined("ABSPATH") || die("You Can't Access this File Directly");
define("TRPTX_TO_PLUGIN_PATH", plugin_dir_path(__FILE__));
define("TRPTX_TO_PLUGIN_URL", plugin_dir_url(__FILE__));
define("TRPTX_TO_PLUGIN_FILE", __FILE__);
define("TRPTX_TO_PLUGIN_SITE_URL", get_site_url());
define("TRPTX_TO_PLUGIN_PO_API_URL", get_option("TRPTX_TO_PLUGIN_PO_API_URL"));
define("TRPTX_TO_PLUGIN_WOO_AUTH_KEY", get_option("trptx_woo_api_key"));
define("TRPTX_TO_PLUGIN_AUTH_KEY", get_option("trptx_key"));
define("TRPTX_CHFS_VALIDATE_API_URL", "http://abc4741.sg-host.com");
define("TRPTX_CHFS_VALIDATE_API_PLUGIN_ID", 4);
define("TRPTX_SUCCESS_CODE", 201);
////////////////////// Files and Code Render On Admin dashboard ///////////////////////
if (is_admin()) {
require TRPTX_TO_PLUGIN_PATH ."plugin-update-checker-master/plugin-update-checker.php";
include TRPTX_TO_PLUGIN_PATH . "inc/admin_dashboard.php";
// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//  'https://github.com/faisalsehar786/Creativeheads-Power-Office-Woocomerce-Plugin/',
//  __FILE__,
//  'pwfo_power_office_woocomerce'
// );
// //Set the branch that contains the stable release.
// $myUpdateChecker->setBranch('main');
// //Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('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');


if ($_GET["page"] == "trptx_to_woocomerce" || $_GET["page"] =="CHFS_ACTIVATION_trptx") {
$GetDataplug = wp_remote_retrieve_body(
wp_remote_get(
TRPTX_CHFS_VALIDATE_API_URL .
"/api/getSiteData?id=" .
TRPTX_CHFS_VALIDATE_API_PLUGIN_ID .
"&domain=" .
$_SERVER["HTTP_HOST"]
)
);

$postsgetPlugcheck = json_decode($GetDataplug, true);
if (!empty($postsgetPlugcheck) && get_option("TRPTX_TO_PLUGIN_AUTH_KEY")==$postsgetPlugcheck["key"]) {

update_option("trptx_licens_status",'vaild');

}else{
    update_option("trptx_licens_status",'unvaild');
}
}
}
////////////////////////////////////////////////////////////////////////////////////////////
if (
in_array(
"woocommerce/woocommerce.php",
apply_filters("active_plugins", get_option("active_plugins"))
)
) {
// Put your plugin code here
add_action("woocommerce_loaded", function () {
////////////////////// Files For Front end  //////////////////////////////////////////

if (get_option("trptx_licens_status") =='vaild') {
include TRPTX_TO_PLUGIN_PATH . "inc/trptx_global_fuctions.php";
include TRPTX_TO_PLUGIN_PATH .
"trptx_to_woocomerce/trptx_to_woocomerce_hooks_triger.php";
}else{

     if (is_admin() && $_GET["page"] == "trptx_to_woocomerce") {
                echo "<script>alert('This Message From Tripletex to woocomerce  Plugin Please Enter Vaild license Key And Register Your Domain on Plugin Provider Record')</script>";
            }
}

});
}

///////////////////////////////////////////////////  Aactive Plugin /////////////////////////
register_activation_hook(__FILE__, function () {
add_option("trptx_woo_api_key", "");
add_option("trptx_Modedev", "on");
add_option("trptx_key", "");
add_option("trptx_Currency",1);
add_option("trptx_Country",161);
// add_option("trptx_Inventory", "");
add_option("trptx_VolumeUnit", "cm3");
add_option("trptx_Markup", 0);
add_option("trptx_Discount", 0);
add_option("trptx_VatType", 3);
add_option("trptx_InvoicesDueIn",30);
add_option("trptx_WeightUnit", "kg");
add_option(
"TRPTX_TO_PLUGIN_PO_API_URL",
"https://api.tripletex.io/v2"
);
add_option("TRPTX_TO_PLUGIN_AUTH_KEY", "");
add_option("trptx_licens_status", "unvaild");
$postPluginData = wp_remote_retrieve_body(
wp_remote_post(TRPTX_CHFS_VALIDATE_API_URL . "/api/sendSiteData", [
"body" => [
"id" => TRPTX_CHFS_VALIDATE_API_PLUGIN_ID,
"domain" => $_SERVER["HTTP_HOST"],
"is_del" => "no",
"is_active" => "yes",
],
"method" => "POST",
"content-type" => "application/json",
])
);
$resultPlugin = json_decode($postPluginData);



$GetDataplug = wp_remote_retrieve_body(
wp_remote_get(
TRPTX_CHFS_VALIDATE_API_URL .
"/api/getSiteData?id=" .
TRPTX_CHFS_VALIDATE_API_PLUGIN_ID .
"&domain=" .
$_SERVER["HTTP_HOST"]
)
);

$postsgetPlugcheck = json_decode($GetDataplug, true);
if (!empty($postsgetPlugcheck) && get_option("TRPTX_TO_PLUGIN_AUTH_KEY")==$postsgetPlugcheck["key"]) {

update_option("trptx_licens_status",'vaild');

}else{
    update_option("trptx_licens_status",'unvaild');
}
});


///////////////////////////////////////////////////  Deactive Plugin /////////////////////////
register_deactivation_hook(__FILE__, function () {
$postPluginData = wp_remote_retrieve_body(
wp_remote_post(TRPTX_CHFS_VALIDATE_API_URL . "/api/sendSiteData", [
"body" => [
"id" => TRPTX_CHFS_VALIDATE_API_PLUGIN_ID,
"domain" => $_SERVER["HTTP_HOST"],
"is_del" => "no",
"is_active" => "no",
],
"method" => "POST",
"content-type" => "application/json",
])
);
$resultPlugin = json_decode($postPluginData);


$GetDataplug = wp_remote_retrieve_body(
wp_remote_get(
TRPTX_CHFS_VALIDATE_API_URL .
"/api/getSiteData?id=" .
TRPTX_CHFS_VALIDATE_API_PLUGIN_ID .
"&domain=" .
$_SERVER["HTTP_HOST"]
)
);

$postsgetPlugcheck = json_decode($GetDataplug, true);
if (!empty($postsgetPlugcheck) && get_option("TRPTX_TO_PLUGIN_AUTH_KEY")==$postsgetPlugcheck["key"]) {

update_option("trptx_licens_status",'vaild');

}else{
    update_option("trptx_licens_status",'unvaild');
}
});
//////////////////////////////////////  Activation key Of Plugin //////////////////////
add_action(
"wp_ajax_frontend_action_trptx_activation_plugin",
"frontend_action_trptx_activation_plugin"
);
add_action(
"wp_ajax_nopriv_frontend_action_trptx_activation_plugin",
"frontend_action_trptx_activation_plugin"
);
function frontend_action_trptx_activation_plugin()
{
if (isset($_POST["activation_key"])) {

update_option(
"TRPTX_TO_PLUGIN_AUTH_KEY",
sanitize_text_field($_POST["activation_key"])
);
$postsDataplug = wp_remote_retrieve_body(
wp_remote_get(
TRPTX_CHFS_VALIDATE_API_URL .
"/api/getSiteData?id=" .
TRPTX_CHFS_VALIDATE_API_PLUGIN_ID .
"&domain=" .
$_SERVER["HTTP_HOST"]
)
);
$postsgetPlugcheck = json_decode($postsDataplug, true);
if (!empty($postsgetPlugcheck) && get_option("TRPTX_TO_PLUGIN_AUTH_KEY")==$postsgetPlugcheck["key"]) {

update_option("trptx_licens_status",'vaild');


echo json_encode(["status" =>200]);

}else{

    update_option("trptx_licens_status",'unvaild');

echo json_encode(["status" => 404]);
}

wp_die();
}
}


