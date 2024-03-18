<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Hrm_Dashboard' ) ) {
	class Hrm_Dashboard {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_shortcode('dashboard_page', [$this, 'dashboard_page_shortcode']);
		}

		public function dashboard_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Dashboard Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/dashboard/index', null, $attributes);

			return ob_get_clean();
		}

	} // class end

	Hrm_Dashboard::instance()->initialize();
}