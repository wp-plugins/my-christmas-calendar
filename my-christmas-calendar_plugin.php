<?php
ob_start();
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
		add_settings_section('mcac_section', __('Online Advent Calendar', 'mcac'), array($this,'mcac_main_section_cb'), __FILE__);
		add_settings_field('mcac_subdomain',__('Web Address: <span>?</span> ','mcac'), array($this, 'mcac_subdomain_setting'), __FILE__, 'mcac_section');
	}

	public function mcac_remove() {
		delete_option("mcac_plugin_options");
	}

	public function mcac_register_scripts() {
		wp_register_style('mcac_css', plugins_url('/css/mcac.css',__FILE__), '', '1.1', 'all');
		wp_register_style('lato', 'http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic', '', '1.1', 'all');
		wp_register_script('mcac_js', plugins_url('/js/mcac.js',__FILE__), '', '1.1', 'all');
	}
	
	public function mcac_loadTextDomain() {
		//$path = dirname( plugin_basename( __FILE__ ) . ('/languages/',__FILE__);
		load_plugin_textdomain('mcac', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public static function add_menu_page() { //legg til menypunkt i settings menyen
		$page = add_options_page(__('My Christmas Calendar settings', 'mcac'),__('My Christmas Calendar settings', 'mcac'),'administrator',__FILE__, array('Mcac','mcac_display_options_page'));
		add_action( 'admin_print_styles-' . $page, array('Mcac', 'mcac_load_scripts'));
	}

	/* Load CSS AND Javascript */
	public static function mcac_load_scripts() { //load inn custom css og javascript
		wp_enqueue_style('mcac_css' );
		wp_enqueue_style('lato' );
		wp_enqueue_script('mcac_js' );
	}

	public function mcac_main_section_cb() {
		//callback
	}
	
	//Brukernavn setting /felt
	public function mcac_subdomain_setting() {
		echo "<input type='text' class='mcac_subdomain' name='mcac_plugin_options[mcac_subdomain]' value='{$this->options['mcac_subdomain']}' />". " " . "<span class='mcac_domain'>.julekalender.com</span>";
	}

	public static function mcac_display_options_page() { //vis frem optionssiden
		$op = get_option('mcac_plugin_options');
?>
		<div class="mcac_wrap">
			<div class="mcac-help">
            	<?php _e('<h2>Please insert your calendar Web Address</h2><br />
				This option can be found in your Advent Calendar admin, under Settings > General settings > Web address.<br /><br />
				If this doesn\'t make sense to you, you\'ll probably need to register an account first.','mcac'); ?>
            </div>
<?php if(!empty($op['mcac_subdomain'])) { ?>
            <p class="mcac_account"><?php _e('<span>Haven\'t got an account?</span><br /><a target="_blank" class="button button-primary" href="http://advent-calendar.net/">Register here</a>', 'mcac') ?></p>
<?php }else{ ?>
			<p class="mcac_account mcac_animation_helper"><?php _e('<span>Haven\'t got an account?</span><br /><a target="_blank" class="button button-primary" href="http://advent-calendar.net/">Register here</a>', 'mcac') ?></p>
<?php }; ?>
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
				if(!empty($_GET['settings-updated']))
					if( $_GET['settings-updated'] === 'true') { ?>
            		<div class="mcac-alert">
                    	<p>
            <?php
					
					if(!empty($op['mcac_subdomain'])) {
			 			_e('Paste the shortcode <span>[my_calendar]</span> into your page or post.<br /><br />
						 Available options are:<ul><li>width (in px or %)</li><li>height (in px)</li><li>border (in px)</li>
						 <li>bordercolor (in hex where ff0000 would be bright red)</li></ul>
						 <br /><p>Use the options as attributes of the shortcode tag like:</p><br />
						 <span>[my_calendar width="810px" height="800px" border="3" bordercolor="000000"]</span>
						 ','mcac'); 
					?>
            		
            <?php 
					}else{
			       		_e('Please insert your Web Address.<br />
						This option can be found in your Advent Calendar admin, under Settings > General settings > Web address','mcac'); 
            		}
			?>
            			</p>
					</div>
			<?php
				}
				
			?>
            <div class="mcac_disclaimer">
            	<p><?php _e('Brought to you by <a target="_blank" href="http://advent-calendar.net">Advent-calendar.net','mcac'); ?></a></p>
            </div>
           <div class="makelogo"></div>  
        </div>
      
    
<?php

	}

}