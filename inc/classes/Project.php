<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Project' ) ) {
	class Hrm_Project {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {


			add_action('init', [$this, 'create_db_projects_table']);
			add_shortcode('project_page', [$this, 'project_page_shortcode']);

			add_action('wp_ajax_create_project_ajax_action', [$this, 'create_project_ajax_action']);
			add_action('wp_ajax_update_project_ajax_action', [$this, 'update_project_ajax_action']);
			add_action('wp_ajax_delete_project_ajax_action', [$this, 'delete_project_ajax_action']);
			
		}

		public function create_db_projects_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . "projects";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  company_id int(11) NOT NULL,
                  client_id int(11) NOT NULL,
                  employee_ids VARCHAR(255) NULL,
                  title VARCHAR(255) NOT NULL,
                  start_date VARCHAR(255) NULL,
                  finish_date VARCHAR(255) NULL,
                  summary VARCHAR(255) NULL,
                  priority VARCHAR(20) NULL,
                  status VARCHAR(20) NULL,
                  progress int(11) NULL,
                  description LONGTEXT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		public function project_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Project Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/project/index', null, $attributes);

			return ob_get_clean();
		}

		// project create
		public function create_project_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;
				//print_r($_POST);
				//exit();

				//get projects table
				$table_projects  = $wpdb->prefix . 'projects';

				$company_id      = (int) $_POST['company_id'];
				$client_id       = (int) $_POST['client_id'];
				$employee_ids    = $_POST['employee_ids'];
				$title           = $_POST['title'];
				$start_date      = $_POST['start_date'];
				$finish_date     = $_POST['finish_date'];
				$summary         = $_POST['summary'];
				$priority        = $_POST['priority'];
				$status          = $_POST['status'];
				$progress        = 0;
				$description     = $_POST['description'];

				if ( ! empty( $employee_ids ) && is_array( $employee_ids ) ) {
					$employee_ids = implode(",", $employee_ids);
				}

				$data = [
					'company_id'      => $company_id,
					'client_id'       => $client_id,
					'employee_ids'    => $employee_ids,
					'title'           => $title,
					'start_date'      => $start_date,
					'finish_date'     => $finish_date,
					'summary'         => $summary,
					'priority'        => $priority,
					'status'          => $status,
					'progress'        => $progress,
					'description'     => $description,
					'created_at'      => current_time('mysql', false),
				];

				$format = array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' );

				$rules = [
					'title'           => [ 'required', 'string' ],
					'company_id'      => [ 'required', 'integer' ],
					'client_id'       => [ 'required', 'integer' ],
					'start_date'      => [ 'nullable', 'date' ],
					'finish_date'     => [ 'nullable', 'date' ],
				];

				$customMessages = [
					'title' => [
						'required' => __( 'Project Title is required' )
					],
					'company_id' => [
						'required' => __('Please choose a company')
					],
					'client_id' => [
						'required' => __('Please choose a client')
					],
				];


				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$insert_row = $wpdb->insert( $table_projects, $data, $format );
					// if row inserted in table
					if( $insert_row ) {
						wp_send_json_success( __('Project has been created successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}

		// project update
		public function update_project_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//get projects table
				$table_projects  = $wpdb->prefix . 'projects';

				$project_id      = (int) $_POST['project_id'];
				$company_id      = (int) $_POST['company_id'];
				$client_id       = (int) $_POST['client_id'];
				$employee_ids    = $_POST['employee_ids'];
				$title           = $_POST['title'];
				$start_date      = $_POST['start_date'];
				$finish_date     = $_POST['finish_date'];
				$summary         = $_POST['summary'];
				$priority        = $_POST['priority'];
				$status          = $_POST['status'];
				$progress        = 0;
				$description     = $_POST['description'];

				if ( ! empty( $employee_ids ) && is_array( $employee_ids ) ) {
					$employee_ids = implode(",", $employee_ids);
				}

				$data = [
					'company_id'      => $company_id,
					'client_id'       => $client_id,
					'employee_ids'    => $employee_ids,
					'title'           => $title,
					'start_date'      => $start_date,
					'finish_date'     => $finish_date,
					'summary'         => $summary,
					'priority'        => $priority,
					'status'          => $status,
					'progress'        => $progress,
					'description'     => $description,
				];

				$format = array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', );

				$rules = [
					'title'           => [ 'required', 'string' ],
					'company_id'      => [ 'required', 'integer' ],
					'client_id'       => [ 'required', 'integer' ],
					'start_date'      => [ 'nullable', 'date' ],
					'finish_date'     => [ 'nullable', 'date' ],
				];

				$customMessages = [
					'title' => [
						'required' => __( 'Project Title is required' )
					],
					'company_id' => [
						'required' => __('Please choose a company')
					],
					'client_id' => [
						'required' => __('Please choose a client')
					],
				];

				$where = [ 'id' => $project_id ];

				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$update_row = $wpdb->update( $table_projects, $data, $where );
					// if row inserted in table
					if( $update_row ) {
						wp_send_json_success( __('Project has been updated successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}

			} else {
				wp_send_json_error();
			};
		}

		// project delete
		public function delete_project_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				// Get project table
				$table_projects     = $wpdb->prefix . 'projects';
				$project_id      = intval($_POST['projectId']);

				$delete_row = $wpdb->delete( $table_projects, ['id' => $project_id] );

				if( $delete_row ) {
					wp_send_json_success( __('Project has been deleted successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}

			} else {
				wp_send_json_error();
			};
		}
		

	} // class end

	Hrm_Project::instance()->initialize();
}