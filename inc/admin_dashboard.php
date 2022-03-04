<?php
add_action('admin_enqueue_scripts', 'admin_enqueue_scripts_trptx');
add_action('admin_menu', 'plugin_menu_trptx');
add_action('admin_menu', 'process_form_settings_trptx');
/////////////////////////// Call These Hooks //////////////////////////////
function admin_enqueue_scripts_trptx(){
		wp_enqueue_script('trptx_dev_script', TRPTX_TO_PLUGIN_URL."assets/js/custom.js", array(), '1.0.0', false);
	}

//////////////////////////////////// ///////////////////////////////////////////////////////
function plugin_menu_trptx(){
	add_menu_page( 'Tripletex woocomerce', 'Tripletex woocomerce', 'manage_options', 'trptx_to_woocomerce','options_func_trptx', $icon_url = '', $position = null);
	add_submenu_page( 'trptx_to_woocomerce', 'License and Activation', 'License and Activation', 'manage_options','CHFS_ACTIVATION_trptx','CHFS_ACTIVATION_trptx_FUNCATION_trptx');
}

///////////////////////////////////////////////////////////////////////////////////////////
function CHFS_ACTIVATION_trptx_FUNCATION_trptx(){
	$postsDataplug = wp_remote_retrieve_body(wp_remote_get(TRPTX_CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.TRPTX_CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));
	$postsgetPlug = json_decode($postsDataplug);
	
	
?>
<div class="wrap">
	<h1>License and Activation </h1>
	<label for="">
		<?php
		if (get_option('TRPTX_TO_PLUGIN_AUTH_KEY')==$postsgetPlug->key) {
		echo '<label style="background: green; border-radius: 6px; padding: 10px;color: white;">Activated</label> ';
		}else{
		echo '<label style="background: red; border-radius: 6px; padding: 10px;color: white;">Unregister Key </label> ';
		}
		?>
		
		<input type="text" name="TRPTX_TO_PLUGIN_AUTH_KEY" id="TRPTX_TO_PLUGIN_AUTH_KEY" value="<?php echo esc_html(get_option('TRPTX_TO_PLUGIN_AUTH_KEY'));  ?>" style="width:100%;" required / placeholder="Activation Key">
	</label>
	<div style="margin-top: 30px;">
		<button class="button button-primary trptx_CHFS_Plugin_activate">Activate</button>
	</div>
</div>
<?php
}
/////////////////////////////////////////////////////////////////////////////////////


function process_form_settings_trptx(){
	register_setting('trptx_option_group', 'trptx_option_name' );
	if(isset($_POST['action']) && current_user_can('manage_options') && isset($_POST['plugin_set_trptx'])){
		
		update_option('trptx_woo_api_key', sanitize_text_field($_POST['trptx_woo_api_key']));
		update_option('trptx_key', sanitize_text_field($_POST['trptx_key']));
update_option('TRPTX_TO_PLUGIN_PO_API_URL', sanitize_text_field($_POST['TRPTX_TO_PLUGIN_PO_API_URL']));
update_option('trptx_Modedev', sanitize_text_field($_POST['trptx_Modedev']));
	
	
		
	}
}


////////////////////////////////////////////////////////////////////////////


function options_func_trptx(){ ?>
<div class="wrap">
	<h1>Tripletex woocomerce Credentials  </h1>
	<?php settings_errors(); ?>
	<form id="ajax_form" action="options.php" method="post">
		<?php settings_fields('trptx_option_group'); ?>
		<input type="hidden" name="plugin_set_trptx" value="plugin_set_trptx">
		
		<label for="">
			<a href="https://www.blitter.se/utils/basic-authentication-header-generator/" target="_blank" style="text-transform:capitalize ;">
				Woocomerce consumer and Secret key base64_encode genrator     (put below input filed )
			</a>
			<input type="text" name="trptx_woo_api_key" value="<?php echo esc_html(get_option('trptx_woo_api_key'));  ?>" style="width:100%;" required />
		</label>
		
		<br>
		<br>
		<label for="">
			<a href="https://www.blitter.se/utils/basic-authentication-header-generator/" target="_blank" style="text-transform:capitalize ;">
			Tripletex  base64_encode key genrator     (put below input filed )</a>
			<input type="text" name="trptx_key" value="<?php echo esc_html(get_option('trptx_key')); ?>"  style="width:100%;"required/>
		</label>
		<br>
		<br>
		<label for="">
			
			Tripletex  Api Url
			<input type="text" name="TRPTX_TO_PLUGIN_PO_API_URL" value="<?php echo esc_html(get_option('TRPTX_TO_PLUGIN_PO_API_URL')); ?>"  style="width:100%;"required/>
		</label>
		<br>
		<br>
		
		
		<label for="">
			
			Setting On Sidebar:
			
			<select name="trptx_Modedev">
				
				<option value="on"<?php if (get_option('trptx_Modedev')=='on') { echo "Selected"; } ?>>Enable</option>
				<option value="off" <?php if (get_option('trptx_Modedev')=='off') { echo "Selected"; } ?>>Disable</option>
			</select>
		</label>
		
		<?php submit_button('Save Credentials');
		
		?>
		
	</form>
	<style type="text/css">
	#adminmenu .toplevel_page_trptx_to_woocomerce{
	display: <?php if (get_option('trptx_Modedev')=='off') { echo "none"; } ?>;
	}
	#submit{
	float: right;
	background: #11a111;
	}
	.progress-bar{display: flex;
	-ms-flex-direction: column;
	flex-direction: column;
	-ms-flex-pack: center;
	justify-content: center;
	overflow: hidden;
	color: #fff;
	text-align: center;
	white-space: nowrap;
	background-color: #007bff;
	transition: width .6s ease;}
	</style>
	
	<div class="form-group" id="process" style="display:none; margin-bottom: 15px;">
		<div class="progress" style="    display: flex;
			height: 1rem;
			overflow: hidden;
			line-height: 0;
			font-size: .75rem;
			background-color: #e9ecef;
			border-radius: 0.25rem;">
			<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
			</div>
		</div>
	</div>
	
	<!-- <h3>Step 1:</h3>
	<button class="button button-primary sendAllproducts" id="sendAllproducts" >All Products</button>
	<h3>Step 2:</h3>
	<button class="button button-primary sendAllcustomers"id="sendAllcustomers" >All Customer</button>
	<h3>Step 3:</h3>
	<button class="button button-primary sendAllOrders"id="sendAllOrders" >All Orders</button>
	<br><br>
	<h3>Logs</h3>
	<div class="collectresponse" style="background: white;
		padding: 2%;
		border: 2px solid;">
		
	</div> -->
</div>
<?php
}