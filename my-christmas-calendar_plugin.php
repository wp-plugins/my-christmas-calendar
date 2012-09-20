<?php
class Mcac {
	
	public $options;
	
	/* Init plugin, register settings, register script & css, call api function */
	public function __construct() {
		$this->mcac_register_settings();
		$this->mcac_register_scripts();
		$this->mcac_loadTextDomain();
		//delete_option('makenewsmail_plugin_options');
		$this->options  = get_option('mcac_plugin_options');
	}
	
	public function mcac_register_settings() {
		register_setting('mcac_plugin_options', 'mcac_plugin_options');	 //3 param er callback
		add_settings_section('mcac_section', __('My Christmas Calendar settings', 'mcac'), array($this,'mcac_main_section_cb'), __FILE__);
		add_settings_field('mcac_subdomain',__('Subdomain: ','mcac'), array($this, 'mcac_subdomain_setting'), __FILE__, 'mcac_section');
	}
	
	public function mcac_remove() {
		delete_option("mcac_plugin_options");
	
	}
	
	public function mcac_register_scripts() {
		wp_register_style('mcac_css', plugins_url('/css/mcac.css',__FILE__), '', '1.0', 'all');
	}
	
	public function mcac_loadTextDomain() {
		//$path = dirname( plugin_basename( __FILE__ ) . ('/languages/',__FILE__);
		load_plugin_textdomain('mcac', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	public function add_menu_page() { //legg til menypunkt i settings menyen
		$page = add_options_page(__('My Christmas Calendar settings', 'mcac'),__('My Christmas Calendar settings', 'mcac'),'administrator',__FILE__, array('Mcac','mcac_display_options_page'));
		add_action( 'admin_print_styles-' . $page, array('Mcac', 'mcac_load_scripts'));
	}
	
	/* Load CSS AND Javascript */
	public function mcac_load_scripts() { //load inn custom css og javascript
		wp_enqueue_style('mcac_css' );
	}
	
	public function mcac_main_section_cb() {
		//callback
	}
	
	//Brukernavn setting /felt
	public function mcac_subdomain_setting() {
		echo "<input type='text' class='mcac_subdomain' name='mcac_plugin_options[mcac_subdomain]' value='{$this->options['mcac_subdomain']}' />". " " . "<span class='mcac_domain'>.julekalender.com</span>";
	}
	
	public function mcac_display_options_page() { //vis frem optionssiden
		
?>
		<div class="wrap">
			<div class="mcac_header"></div>
            <p class="account"><?php _e('Not got an account? <a target="_blank" href="http://www.julekalender.com">Register here</a>', 'mcac') ?></p>
            <div class="mcac_settings">
                
                <form action="options.php" method="post">
                <?php screen_icon(); ?>
				<?php
					settings_fields('mcac_plugin_options');
					do_settings_sections(__FILE__);
				?>
                	<table class="form-table">
                    	<tr>
                        	<th></th>
                            <td><input id="submit" class="button-primary" type="submit" value="<?php _e('Save','mcac');?>" name="submit"></td>
                        </tr>
                    </table>
               
                </form>
            </div>
       
<?php
	}
}



