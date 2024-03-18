<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Client' ) ) {
	class Hrm_Client {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {


			add_action('init', [$this, 'create_db_client_table']);
			add_shortcode('client_page', [$this, 'client_page_shortcode']);

			add_action('wp_ajax_create_client_ajax_action', [$this, 'create_client_ajax_action']);
			add_action('wp_ajax_delete_client_ajax_action', [$this, 'delete_client_ajax_action']);
		}

		public function create_db_client_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . "clients";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  name VARCHAR(255) NOT NULL,
                  email VARCHAR(255) NULL,
                  phone VARCHAR(20) NULL,
                  address VARCHAR(255) NULL,
                  city VARCHAR(50) NULL,
                  zip VARCHAR(20) NULL,
                  country VARCHAR(50) NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}


		public function client_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Client Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/client/index', null, $attributes);

			return ob_get_clean();
		}


		public function create_client_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//get company table
				$table_clients = $wpdb->prefix . 'clients';

				$name     = $_POST['name'];
				$email    = $_POST['email'];
				$phone    = $_POST['phone'];
				$address  = $_POST['address'];
				$city     = $_POST['city'];
				$zip      = $_POST['zip'];
				$country  = $_POST['country'];

				$data = [
					'name'        => $name,
					'email'       => $email,
					'phone'       => $phone,
					'address'     => $address,
					'city'        => $city,
					'zip'         => $zip,
					'country'     => $country,
					'created_at'  => current_time('mysql', false),
				];

				$rules = [
					'name'  => ['required', 'string'],
					'email' => ['nullable', 'email'],
				];

				$customMessages = [
					'name' => [
						'required' => __('Client Name is required')
					],
					'email' => [
						'email' => __('The provided email is not valid')
					],
				];

				$format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$insert_row = $wpdb->insert( $table_clients, $data, $format );
					// if row inserted in table
					if( $insert_row ) {
						wp_send_json_success( __('Client has been created successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}


		public function delete_client_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				//get company table
				$table_company   = $wpdb->prefix . 'clients';
				$client_id      = (int) $_POST['clientId'];

				$delete_row = $wpdb->delete( $table_company, ['id' => $client_id] );

				if( $delete_row ) {
					wp_send_json_success( __('Client has been deleted successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}
				wp_die();

			} else {
				wp_send_json_error();
			};
		}


	} // class end

	Hrm_Client::instance()->initialize();
}