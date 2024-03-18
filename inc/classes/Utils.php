<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Hrm_Utils' ) ) {
	class Hrm_Utils {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			//add_action('after_setup_theme', [$this, 'cm_after_setup_theme']);
			add_action('after_switch_theme', [$this, 'create_pages_on_theme_activation']);
			add_filter( 'display_post_states', [$this, 'add_display_post_states'], 10, 2 );
			add_action('admin_init', [$this, 'create_employee_role']);
			add_action('init', [$this, 'make_image_upload_directory']);
			add_action('wp_head', [$this, 'global_dynamic_css']);
		}

		/**
		 * Create employee role
		 * @return void
		 */
		public function create_employee_role() {
			add_role(
				'employee', //  System name of the role.
				__( 'Employee'  ), // Display name of the role.
				array(
					'read'  => true,
					'delete_posts'  => true,
					'delete_published_posts' => true,
					'edit_posts'   => true,
					'publish_posts' => true,
					'upload_files'  => true,
					'edit_pages'  => true,
					'edit_published_pages'  =>  true,
					'publish_pages'  => true,
					'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
				)
			);
        }

		/**
		 * Make image upload directory
		 */
		public function make_image_upload_directory() {
			$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'hrm';
			wp_mkdir_p( $uploads_dir );
		}

		/**
		 * After setup theme
		 */
		public function cm_after_setup_theme() {
			//Remove admin bar on frontend
			if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
				show_admin_bar( false );
			}
		}

		/**
		 * Create login page after theme activation
		 */
		public function create_pages_on_theme_activation() {
			// Create necessary pages
            $this->create_login_page();
			$this->create_home_page();
			$this->create_company_page();
			$this->create_department_page();
			$this->create_designation_page();
			$this->create_employee_page();
			$this->create_clients_page();
			$this->create_projects_page();
			$this->create_invoices_page();
		}

		/**
		 * Create login page
		 * @return void
		 */
		protected function create_login_page() {
			// Set the title, template, etc
			$new_page_title     = __('Login', 'cm-hrm');            // Page's title
			$new_page_content   = '';                               // Content goes here
			$new_page_template  = 'login.php';                      // The template to use for the page
			$page_check = $this->get_page_by_title($new_page_title); // Check if the page already exists
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'hrm-login'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
				if( !empty($new_page_template) ){
					update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
				}
			}
		}

		/**
		 * Create home page
		 * @return void
		 */
		protected function create_home_page() {
			$new_page_title     = __('Home', 'cm-hrm');
			$new_page_content   = '[dashboard_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'home'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);

				// Set Home page as front page
				update_option( 'page_on_front', $new_page_id );
				update_option( 'show_on_front', 'page' );
			}
        }

		/**
		 * Create company page
		 * @return void
		 */
		protected function create_company_page() {
			$new_page_title     = __('Company', 'cm-hrm');
			$new_page_content   = '[company_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'company'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create department page
		 * @return void
		 */
		protected function create_department_page() {
			$new_page_title     = __('Departments', 'cm-hrm');
			$new_page_content   = '[department_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'departments'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create designation page
		 * @return void
		 */
		protected function create_designation_page() {
			$new_page_title     = __('Designation', 'cm-hrm');
			$new_page_content   = '[designation_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'designation'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create employees page
		 * @return void
		 */
		protected function create_employee_page() {
			$new_page_title     = __('Employees', 'cm-hrm');
			$new_page_content   = '[employee_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'employees'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create projects page
		 * @return void
		 */
		protected function create_projects_page() {
			$new_page_title     = __('Projects', 'cm-hrm');
			$new_page_content   = '[project_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'projects'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create clients page
		 * @return void
		 */
		protected function create_clients_page() {
			$new_page_title     = __('Clients', 'cm-hrm');
			$new_page_content   = '[client_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'clients'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Create invoices page
		 * @return void
		 */
		protected function create_invoices_page() {
			$new_page_title     = __('Invoices', 'cm-hrm');
			$new_page_content   = '[invoice_page]';
			$page_check         = $this->get_page_by_title($new_page_title);
			// Store the above data in an array
			$new_page = array(
				'post_type'     => 'page',
				'post_title'    => $new_page_title,
				'post_content'  => $new_page_content,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_name'     => 'invoices'
			);
			// If the page doesn't already exist, create it
			if( !isset($page_check->ID) ) {
				$new_page_id = wp_insert_post($new_page);
			}
		}

		/**
		 * Get page by title
		 */
		private static function get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
			global $wpdb;
			if ( is_array( $post_type ) ) {
				$post_type           = esc_sql( $post_type );
				$post_type_in_string = "'" . implode( "','", $post_type ) . "'";
				$sql                 = $wpdb->prepare(
					"
			            SELECT ID
			            FROM $wpdb->posts
			            WHERE post_title = %s
			            AND post_type IN ($post_type_in_string)
			        ",
					$page_title
				);
			} else {
				$sql = $wpdb->prepare(
					"
			            SELECT ID
			            FROM $wpdb->posts
			            WHERE post_title = %s
			            AND post_type = %s
			        ",
					$page_title,
					$post_type
				);
			}

			$page = $wpdb->get_var( $sql );

			if ( $page ) {
				return get_post( $page, $output );
			}

			return null;
		}

		/**
		 * Add a post display state for HRM pages in the page list table.
		 *
		 * @param array   $post_states An array of post display states.
		 * @param WP_Post $post        The current post object.
		 */
		public function add_display_post_states( $post_states, $post ) {
			$login_page_check          = $this->get_page_by_title('Login');
			$company_page_check        = $this->get_page_by_title('Company');
			$departments_page_check    = $this->get_page_by_title('Departments');
			$designation_page_check    = $this->get_page_by_title('Designation');
			$employees_page_check      = $this->get_page_by_title('Employees');
			$clients_page_check        = $this->get_page_by_title('Clients');
			$projects_page_check       = $this->get_page_by_title('Projects');
			$invoices_page_check       = $this->get_page_by_title('Invoices');

			if ( $login_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_login'] = __( 'Login Page', 'cm-hrm' );
			}
			if ( $company_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_company'] = __( 'Company Page', 'cm-hrm' );
			}
			if ( $departments_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_department'] = __( 'Department Page', 'cm-hrm' );
			}
			if ( $designation_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_designation'] = __( 'Designation Page', 'cm-hrm' );
			}
			if ( $employees_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_employees'] = __( 'Employee Page', 'cm-hrm' );
			}
			if ( $clients_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_clients'] = __( 'Client Page', 'cm-hrm' );
			}
			if ( $projects_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_projects'] = __( 'Project Page', 'cm-hrm' );
			}
			if ( $invoices_page_check->ID === $post->ID ) {
				$post_states['hrm_page_for_invoices'] = __( 'Invoice Page', 'cm-hrm' );
			}

			return $post_states;
		}

		/**
		 * Get current user role
		 * 'administrator'
		 */
		public static function current_user_role() {
			if(is_user_logged_in()) {
				$user = wp_get_current_user();
				$role = (array) $user->roles;
				return $role[0];
			}
			else {
				return false;
			}
		}

		/**
		 * Get current page url
		 */
		public static function current_page_url() {
			$page_URL = 'http';
			if ($_SERVER["HTTPS"] == "on") {$page_URL .= "s";}

			$page_URL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$page_URL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$page_URL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}

			return $page_URL;
		}

		/**
		 * If not logged in, redirect user to login page
		 */
		public static function redirect_to_login_page() {
			$page = self::get_page_by_title('Login');
			if ( $page->ID ) {
				wp_redirect(get_permalink($page->ID));
				exit();
			}
		}

		/**
		 * Fields validation error alert
		 */
		public static function validation_error_alert( array $errors ): void {
			$alert_html = '<div class="validation_error_message alert alert-danger p-0" role="alert">';
			$alert_html .= '<ul style="margin: 5px 0">';
			foreach ( $errors as $error ) {
				$alert_html .= '<li>' . $error[0] . '</li>';
			}
			$alert_html .= '</ul>';
			$alert_html .= '</div>';
			echo $alert_html;
		}

		/**
		 * Set transient
		 */
		public static function set_transient( $transient = '', $message = '', $expire_seconds = 5 ): ?string {
			if ( $transient == '' ) {
				return null;
			}
			return set_transient( $transient, esc_html__( $message, 'cm-hrm' ), $expire_seconds );
		}

		/**
		 * Display toastr alert using transient
		 */
		public static function get_transient_toast( $transient = '' ): void {
			if ( get_transient($transient) == '' ) {
				return;
			}
			?>
            <script>toastr.success('<?php echo get_transient($transient) ?>')</script>
			<?php
		}

		/**
		 * Display toastr alert
		 */
		public static function show_toastr( string $type, string $message ): void {
			?>
			<script>toastr.<?php echo $type ?>('<?php echo $message ?>')</script>
			<?php
		}

		/**
		 * Get uploaded image from 'hrm' directory and database
		 */
		public static function get_uploaded_image($file_name): string {
			$upload_dir = wp_upload_dir();
			return $upload_dir['baseurl'] . '/hrm/' . $file_name . '';
		}

		public function global_dynamic_css() {
		    $css  = '';
		    $css .= '<style>
                        :root {
                          --body: '.get_theme_mod( 'bg_color', '#5e8fe4' ).';
                          --black: #000;
                          --white: #fff;
                          --theme: #001659;
                          --theme2: #FF5E15;
                          --header: #001659;
                          --base: #001659;
                          --text: #666;
                          --text2: #CFCFCF;
                          --border: #C5C5C5;
                          --border2: #E8E8E9;
                          --button: #1C2539;
                          --button2: #030734;
                          --ratting: #FF9F0D;
                          --bg: #F2F3F5;
                          --bg2: #DF0A0A0D;
                        }
                    </style>';

		    echo $css;
		}

	} // class end

	Hrm_Utils::instance()->initialize();
}