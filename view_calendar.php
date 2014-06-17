<?php
/*
Plugin Name: View Calendar
Description: Тестовый плагин смены шаблона
License: Free
*/

class ViewCalendar
{
	private $plugin_name = 'View Calendar';
	private $plugin_slug = 'calendar_template';
	private $options;

	public function __construct()
	{
		$this->options = get_option( $this->plugin_slug . '_options' );
		add_action( 'admin_menu', array( $this, 'adminMenu' ) );
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
		add_shortcode( 'link_to_calendar', array( $this, 'shortcode' ) );
		add_action( 'template_redirect', array( $this, 'getTemplate' ), 5 );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
	}
	
	public function adminMenu()
	{
		add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', $this->plugin_slug, array( $this, 'optionsPage' ) );
		add_filter( 'plugin_action_links', array( $this, 'optionsLinks' ), 10, 2 );
	}

	public function optionsLinks( $actions, $plugin_file )
	{
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$settings_link = '<a href="options-general.php?page=' . esc_attr( $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';
			array_unshift( $actions, $settings_link );
		}
		return $actions;
	}

	public function registerSettings()
	{
		register_setting( $this->plugin_slug, $this->plugin_slug . '_options' );
	}

	public function getTemplate()
	{
		if ( $this->checkMyTemplate() ) {
			include( plugin_dir_path( __FILE__ ) . 'calendar.php' );
			exit();
		}
	}

	private function checkMyTemplate()
	{
		return isset( $_GET['view'] ) && $_GET['view'] == calendar;
	}

	public function optionsPage()
	{
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $this->plugin_name ); ?></h2>
		</div>
	<?php
	}
	
}

new ViewCalendar;

function Calendar_shortcode_link()
{
	$new_calendar = new ViewCalendar;
	echo $new_calendar->shortcode();
}