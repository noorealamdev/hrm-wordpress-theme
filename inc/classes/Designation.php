<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Designation' ) ) {
    class Hrm_Designation {
        protected static $instance = null;

        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function initialize() {


            add_action('init', [$this, 'create_db_designation_table']);
            add_shortcode('designation_page', [$this, 'designation_page_shortcode']);

            add_action('wp_ajax_create_designation_ajax_action', [$this, 'create_designation_ajax_action']);
            add_action('wp_ajax_delete_designation_ajax_action', [$this, 'delete_designation_ajax_action']);
        }

        public function create_db_designation_table() {
            global $wpdb;

            $table_name = $wpdb->prefix . "designation";

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  department_id int(11) NOT NULL,
                  company_id int(11) NOT NULL,
                  name VARCHAR(255) NOT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }


        public function designation_page_shortcode( $atts ) {
            $attributes = shortcode_atts( array(
                'title' => 'Designation Index Page',
            ), $atts );
            ob_start();

            get_template_part('template-parts/designation/index', null, $attributes);

            return ob_get_clean();
        }


        public function create_designation_ajax_action() {
            if ( check_ajax_referer( '_ajax_nonce' ) ) {
                global $wpdb;
                $validator = new Validator;

                //get designation table
                $table_designation   = $wpdb->prefix . 'designation';

                $designation_name   = $_POST['designationName'];
                $department_id      = (int) $_POST['departmentId'];
                $company_id         = (int) $_POST['companyId'];

                $data = [
                    'name'              => $designation_name,
                    'department_id'     => $department_id,
                    'company_id'        => $company_id,
                    'created_at'        => current_time('mysql', false),
                ];

                $rules = [
                    'name'          => ['required', 'string'],
                    'department_id' => ['required', 'integer'],
                    'company_id'    => ['required', 'integer'],
                ];

                $customMessages = [
                    'name' => [
                        'required' => __('Designation Name is required')
                    ],
                    'department_id' => [
                        'required' => __('Please choose a department')
                    ],
                    'company_id' => [
                        'required' => __('Please choose a company')
                    ],
                ];

                $format = array( '%s', '%d', '%d' );

                $validation = $validator->make( $data, $rules, $customMessages );

                if ( $validation->fails() ) {
                    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

                } else {
                    $insert_row = $wpdb->insert( $table_designation, $data, $format );
                    // if row inserted in table
                    if( $insert_row ) {
                        wp_send_json_success( __('Designation has been created successfully.') );
                    } else {
                        wp_send_json_error( __('Something went wrong. Please try again.') );
                    }
                }

            } else {
                wp_send_json_error();
            };
        }

        public function delete_designation_ajax_action() {
            if ( check_ajax_referer( '_ajax_nonce' ) ) {
                global $wpdb;
                //get company table
                $table_designation   = $wpdb->prefix . 'designation';
                $designation_id      = intval($_POST['designationId']);

                $delete_row = $wpdb->delete( $table_designation, ['id' => $designation_id] );

                if( $delete_row ) {
                    wp_send_json_success( __('Designation has been deleted successfully.') );
                } else {
                    wp_send_json_error( __('Something went wrong. Please try again.') );
                }

            } else {
                wp_send_json_error();
            };
        }


    } // class end

    Hrm_Designation::instance()->initialize();
}