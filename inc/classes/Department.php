<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Departments' ) ) {
	class Hrm_Departments {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {


			add_action('init', [$this, 'create_db_departments_table']);
			add_shortcode('department_page', [$this, 'department_page_shortcode']);

			add_action('wp_ajax_create_department_ajax_action', [$this, 'create_department_ajax_action']);
			add_action('wp_ajax_delete_department_ajax_action', [$this, 'delete_department_ajax_action']);
		}

		public function create_db_departments_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . "departments";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  company_id int(11) NOT NULL,
                  name VARCHAR(255) NOT NULL,
                  department_head VARCHAR(255) NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}


		public function department_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Departments Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/department/index', null, $attributes);

			return ob_get_clean();
		}


		public function create_department_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//get department table
				$table_company   = $wpdb->prefix . 'departments';

				$department_name    = $_POST['departmentName'];
				$company_id         = (int) $_POST['companyId'];
				$department_head    = $_POST['departmentHead'];

				$data = [
					'name'              => $department_name,
					'company_id'        => $company_id,
					'department_head'   => $department_head,
					'created_at'        => current_time('mysql', false),
				];

				$rules = [
					'name'          => ['required', 'string'],
					'company_id'    => ['required', 'integer'],
				];

				$customMessages = [
					'name' => [
						'required' => __('Department Name is required')
					],
					'company_id' => [
						'required' => __('Please choose a company')
					],
				];

				$format = array( '%s', '%d', '%s' );

				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$insert_row = $wpdb->insert( $table_company, $data, $format );
					// if row inserted in table
					if( $insert_row ) {
						wp_send_json_success( __('Department has been created successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}

		public function delete_department_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				//get company table
				$table_departments   = $wpdb->prefix . 'departments';
				$department_id      = intval($_POST['departmentId']);

				$delete_row = $wpdb->delete( $table_departments, ['id' => $department_id] );

				if( $delete_row ) {
					wp_send_json_success( __('Department has been deleted successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}

			} else {
				wp_send_json_error();
			};
		}


	} // class end

	Hrm_Departments::instance()->initialize();
}