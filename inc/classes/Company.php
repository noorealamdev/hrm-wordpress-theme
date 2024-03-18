<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Company' ) ) {
	class Hrm_Company {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {


			add_action('init', [$this, 'create_db_company_table']);
			add_shortcode('company_page', [$this, 'company_page_shortcode']);

			add_action('wp_ajax_create_company_ajax_action', [$this, 'create_company_ajax_action']);
			//add_action('wp_ajax_edit_company_ajax_action', [$this, 'edit_company_ajax_action']);
			add_action('wp_ajax_delete_company_ajax_action', [$this, 'delete_company_ajax_action']);
		}

		public function create_db_company_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . "company";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  name VARCHAR(255) NOT NULL,
                  email VARCHAR(255) NULL,
                  phone VARCHAR(20) NULL,
                  country VARCHAR(50) NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}


		public function company_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Company Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/company/index', null, $attributes);

			return ob_get_clean();
		}


		public function create_company_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//print_r($_POST);
				//exit();

				//get company table
				$table_company   = $wpdb->prefix . 'company';

				$company_name    = $_POST['companyName'];
				$company_email   = $_POST['companyEmail'];
				$company_phone   = $_POST['companyPhone'];
				$company_country = $_POST['companyCountry'];

				$data = [
					'name'        => $company_name,
					'email'       => $company_email,
					'phone'       => $company_phone,
					'country'     => $company_country,
					'created_at'  => current_time('mysql', false),
				];

				$rules = [
					'name' => ['required', 'string'],
					'email' => ['nullable', 'email'],
				];

				$customMessages = [
					'name' => [
						'required' => __('Company Name is required')
					],
					'email' => [
						'email' => __('The provided email is not valid')
					],
				];

				$format = array( '%s', '%s', '%s', '%s' );

				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$insert_row = $wpdb->insert( $table_company, $data, $format );
					// if row inserted in table
					if( $insert_row ) {
						wp_send_json_success( __('Company has been created successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}


		public function edit_company_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//get company table
				$table_company   = $wpdb->prefix . 'company';

				$company_id      = intval($_POST['companyId']);
				$company_name    = $_POST['companyName'];
				$company_email   = $_POST['companyEmail'];
				$company_phone   = $_POST['companyPhone'];
				$company_country = $_POST['companyCountry'];

				$data = [
					'name'        => $company_name,
					'email'       => $company_email,
					'phone'       => $company_phone,
					'country'     => $company_country,
				];

				$where = [ 'id' => $company_id ];

				$rules = [
					'name' => ['required', 'string'],
					'email' => ['nullable', 'email'],
				];

				$customMessages = [
					'name' => [
						'required' => __('Company name is required')
					],
					'email' => [
						'email' => __('The provided email is not valid')
					],
				];

				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$update_row = $wpdb->update( $table_company, $data, $where);
					// if row updated in table
					if( $update_row ) {
						wp_send_json_success( __('Company has been updated successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}

		public function delete_company_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				//get company table
				$table_company   = $wpdb->prefix . 'company';
				$company_id      = intval($_POST['companyId']);

				$delete_row = $wpdb->delete( $table_company, ['id' => $company_id] );

				if( $delete_row ) {
					wp_send_json_success( __('Company has been deleted successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}

			} else {
				wp_send_json_error();
			};
		}


	} // class end

	Hrm_Company::instance()->initialize();
}