<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Employee' ) ) {
    class Hrm_Employee {
        protected static $instance = null;

        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function initialize() {
            add_action('init', [$this, 'create_db_employee_table']);
            add_action('init', [$this, 'create_db_attachment_table']);
            add_action('init', [$this, 'create_db_bank_account_table']);
            add_action('init', [$this, 'create_db_salary_table']);
            add_shortcode('employee_page', [$this, 'employee_page_shortcode']);

            add_action('wp_ajax_create_employee_ajax_action', [$this, 'create_employee_ajax_action']);
            add_action('wp_ajax_update_employee_ajax_action', [$this, 'update_employee_ajax_action']);
            add_action('wp_ajax_delete_employee_ajax_action', [$this, 'delete_employee_ajax_action']);

            add_action('wp_ajax_add_employee_document_ajax_action', [$this, 'add_employee_document_ajax_action']);
            add_action('wp_ajax_delete_employee_document_ajax_action', [$this, 'delete_employee_document_ajax_action']);

	        add_action('wp_ajax_add_employee_bank_ajax_action', [$this, 'add_employee_bank_ajax_action']);
	        add_action('wp_ajax_edit_employee_bank_ajax_action', [$this, 'edit_employee_bank_ajax_action']);
	        add_action('wp_ajax_delete_employee_bank_ajax_action', [$this, 'delete_employee_bank_ajax_action']);

	        add_action('wp_ajax_add_employee_salary_ajax_action', [$this, 'add_employee_salary_ajax_action']);
	        add_action('wp_ajax_edit_employee_salary_ajax_action', [$this, 'edit_employee_salary_ajax_action']);
	        add_action('wp_ajax_delete_employee_salary_ajax_action', [$this, 'delete_employee_salary_ajax_action']);
        }

	    /**
	     * Create employee table
	     * @return void
	     */
        public function create_db_employee_table() {
            global $wpdb;

            $table_name = $wpdb->prefix . "employees";

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  company_id int(11) NOT NULL,
                  department_id int(11) NOT NULL,
                  designation_id int(11) NOT NULL,
                  name VARCHAR(255) NOT NULL,
                  photo VARCHAR(255) NULL,
                  gender VARCHAR(255) NOT NULL,
                  dob VARCHAR(255) NULL,
                  email VARCHAR(255) NOT NULL,
                  phone VARCHAR(255) NOT NULL,
                  address TEXT NULL,
                  city VARCHAR(255) NULL,
                  province VARCHAR(255) NULL,
                  zip VARCHAR(255) NULL,
                  country VARCHAR(255) NULL,
                  joining_date VARCHAR(255) NULL,
                  skype VARCHAR(255) NULL,
                  whatsapp VARCHAR(255) NULL,
                  facebook VARCHAR(255) NULL,
                  linkedin VARCHAR(255) NULL,
                  notes LONGTEXT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

	    /**
	     * Create attachment table for employee
	     * @return void
	     */
	    public function create_db_attachment_table() {
		    global $wpdb;

		    $table_name = $wpdb->prefix . "attachments";

		    $charset_collate = $wpdb->get_charset_collate();

		    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  employee_id int(11) NOT NULL,
                  title VARCHAR(255) NOT NULL,
                  attachment VARCHAR(255) NOT NULL,
                  description LONGTEXT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql);
	    }

	    /**
	     * Create bank account table
	     * @return void
	     */
	    public function create_db_bank_account_table() {
		    global $wpdb;

		    $table_name = $wpdb->prefix . "bank_account";

		    $charset_collate = $wpdb->get_charset_collate();

		    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  employee_id int(11) NOT NULL,
                  bank_name VARCHAR(255) NOT NULL,
                  bank_branch VARCHAR(255) NULL,
                  bank_account VARCHAR(255) NOT NULL,
                  description LONGTEXT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql);
	    }

	    /**
	     * Create salary table
	     * @return void
	     */
	    public function create_db_salary_table() {
		    global $wpdb;

		    $table_name = $wpdb->prefix . "salary";

		    $charset_collate = $wpdb->get_charset_collate();

		    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  employee_id int(11) NOT NULL,
                  date VARCHAR(255) NOT NULL,
                  amount VARCHAR(255) NOT NULL,
                  description LONGTEXT NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		    dbDelta($sql);
	    }

	    /**
	     * Create employee page shortcode
	     * @return mixed
	     */
        public function employee_page_shortcode( $atts ) {
            $attributes = shortcode_atts( array(
                'title' => 'Employee Index Page',
            ), $atts );
            ob_start();

            get_template_part('template-parts/employee/index', null, $attributes);

            return ob_get_clean();
        }

	    /**
	     * Create employee form submit ajax
	     * @return void
	     */
	    public function create_employee_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    $uploaded_photo = $_FILES['photo']['name'];
			    $new_photo_name = null;
			    if( $uploaded_photo ) {
				    $tmp_photo_name = $_FILES['photo']['tmp_name'];
				    $new_photo_name = date('dmYHis').str_replace(' ', '', $uploaded_photo);
				    $upload_dir = wp_upload_dir();
				    move_uploaded_file( $tmp_photo_name, $upload_dir['basedir'] .'/hrm/'. $new_photo_name );
			    }

			    //get employees table
		        $table_employees  = $wpdb->prefix . 'employees';

			    $company_id       = (int) $_POST['company_id'];
			    $department_id    = (int) $_POST['department_id'];
			    $designation_id   = (int) $_POST['designation_id'];
			    $name             = $_POST['name'];
			    $gender           = $_POST['gender'];
			    $dob              = $_POST['dob'];
			    $email            = $_POST['email'];
			    $phone            = $_POST['phone'];
			    $address          = $_POST['address'];
			    $city             = $_POST['city'];
			    $province         = $_POST['province'];
			    $zip              = $_POST['zip'];
			    $country          = $_POST['country'];
			    $joining_date     = $_POST['joining_date'];
			    $skype            = $_POST['skype'];
			    $whatsapp         = $_POST['whatsapp'];
			    $facebook         = $_POST['facebook'];
			    $linkedin         = $_POST['linkedin'];
			    $notes            = $_POST['notes'];

			    $data = [
				    'company_id'      => $company_id,
				    'department_id'   => $department_id,
				    'designation_id'  => $designation_id,
				    'name'            => $name,
				    'photo'           => $new_photo_name,
				    'gender'          => $gender,
				    'dob'             => $dob,
				    'email'           => $email,
				    'phone'           => $phone,
				    'address'         => $address,
				    'city'            => $city,
				    'province'        => $province,
				    'zip'             => $zip,
				    'country'         => $country,
				    'joining_date'    => $joining_date,
				    'skype'           => $skype,
				    'whatsapp'        => $whatsapp,
				    'facebook'        => $facebook,
				    'linkedin'        => $linkedin,
				    'notes'           => $notes,
				    'created_at'      => current_time('mysql', false),
			    ];

			    $format = array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'name'            => [ 'required', 'string' ],
				    'company_id'      => [ 'required', 'integer' ],
				    'department_id'   => [ 'required', 'integer' ],
				    'designation_id'  => [ 'required', 'integer' ],
				    'gender'          => [ 'required', 'string' ],
				    'email'           => [ 'required', 'email' ],
				    'phone'           => [ 'required', 'string' ],
			    ];

			    $customMessages = [
				    'name' => [
					    'required' => __( 'Name is required' )
				    ],
				    'email' => [
					    'email' => __('The provided email is not valid')
				    ],
				    'gender' => [
					    'required' => __( 'Please choose a gender' )
				    ],
				    'phone' => [
					    'required' => __( 'Phone is required' )
				    ],
				    'company_id' => [
					    'required' => __('Please choose a company')
				    ],
				    'department_id' => [
					    'required' => __('Please choose a department')
				    ],
				    'designation_id' => [
					    'required' => __('Please choose a designation')
				    ],
			    ];


			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $insert_row = $wpdb->insert( $table_employees, $data, $format );
				    // if row inserted in table
				    if( $insert_row ) {
					    wp_send_json_success( __('Employee has been created successfully.') );
				    } else {
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Update employee form submit ajax
	     * @return void
	     */
	    public function update_employee_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    $uploaded_photo = $_FILES['photo']['name'];
			    $new_photo_name = null;
			    if( $uploaded_photo ) {
				    $tmp_photo_name = $_FILES['photo']['tmp_name'];
				    $new_photo_name = date('dmYHis').str_replace(' ', '', $uploaded_photo);
				    $upload_dir = wp_upload_dir();
				    move_uploaded_file( $tmp_photo_name, $upload_dir['basedir'] .'/hrm/'. $new_photo_name );
			    }

			    //get employees table
			    $table_employees  = $wpdb->prefix . 'employees';

			    $employee_id      = (int) $_POST['employee_id'];
			    $company_id       = (int) $_POST['company_id'];
			    $department_id    = (int) $_POST['department_id'];
			    $designation_id   = (int) $_POST['designation_id'];
			    $name             = $_POST['name'];
			    $gender           = $_POST['gender'];
			    $dob              = $_POST['dob'];
			    $email            = $_POST['email'];
			    $phone            = $_POST['phone'];
			    $address          = $_POST['address'];
			    $city             = $_POST['city'];
			    $province         = $_POST['province'];
			    $zip              = $_POST['zip'];
			    $country          = $_POST['country'];
			    $joining_date     = $_POST['joining_date'];
			    $skype            = $_POST['skype'];
			    $whatsapp         = $_POST['whatsapp'];
			    $facebook         = $_POST['facebook'];
			    $linkedin         = $_POST['linkedin'];
			    $notes            = $_POST['notes'];

			    $data = [
				    'company_id'      => $company_id,
				    'department_id'   => $department_id,
				    'designation_id'  => $designation_id,
				    'name'            => $name,
				    'photo'           => $new_photo_name,
				    'gender'          => $gender,
				    'dob'             => $dob,
				    'email'           => $email,
				    'phone'           => $phone,
				    'address'         => $address,
				    'city'            => $city,
				    'province'        => $province,
				    'zip'             => $zip,
				    'country'         => $country,
				    'joining_date'    => $joining_date,
				    'skype'           => $skype,
				    'whatsapp'        => $whatsapp,
				    'facebook'        => $facebook,
				    'linkedin'        => $linkedin,
				    'notes'           => $notes,
			    ];

			    $format = array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'name'            => [ 'required', 'string' ],
				    'company_id'      => [ 'required', 'integer' ],
				    'department_id'   => [ 'required', 'integer' ],
				    'designation_id'  => [ 'required', 'integer' ],
				    'gender'          => [ 'required', 'string' ],
				    'email'           => [ 'required', 'email' ],
				    'phone'           => [ 'required', 'string' ],
			    ];

			    $customMessages = [
				    'name' => [
					    'required' => __( 'Name is required' )
				    ],
				    'email' => [
					    'email' => __('The provided email is not valid')
				    ],
				    'gender' => [
					    'required' => __( 'Please choose a gender' )
				    ],
				    'phone' => [
					    'required' => __( 'Phone is required' )
				    ],
				    'company_id' => [
					    'required' => __('Please choose a company')
				    ],
				    'department_id' => [
					    'required' => __('Please choose a department')
				    ],
				    'designation_id' => [
					    'required' => __('Please choose a designation')
				    ],
			    ];

			    $where = [ 'id' => $employee_id ];

			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $update_row = $wpdb->update( $table_employees, $data, $where );
				    // if row inserted in table
				    if( $update_row ) {
					    wp_send_json_success( __('Employee has been updated successfully.') );
				    } else {
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Delete employee form submit ajax
	     * @return void
	     */
        public function delete_employee_ajax_action() {
            if ( check_ajax_referer( '_ajax_nonce' ) ) {
                global $wpdb;
                // Get employee table
                $table_employees     = $wpdb->prefix . 'employees';
                $employee_id      = intval($_POST['employeeId']);

                $delete_row = $wpdb->delete( $table_employees, ['id' => $employee_id] );

                if( $delete_row ) {
                    wp_send_json_success( __('Employee has been deleted successfully.') );
                } else {
                    wp_send_json_error( __('Something went wrong. Please try again.') );
                }

            } else {
                wp_send_json_error();
            };
        }

	    /**
	     * Employee attachment add ajax
	     * @return void
	     */
	    public function add_employee_document_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    $uploaded_attachment = $_FILES['attachment']['name'];
			    $new_attachment_name = null;
			    if( $uploaded_attachment ) {
				    $tmp_attachment_name = $_FILES['attachment']['tmp_name'];
				    $new_attachment_name = date('dmYHis').str_replace(' ', '', $uploaded_attachment);
				    $upload_dir = wp_upload_dir();
				    move_uploaded_file( $tmp_attachment_name, $upload_dir['basedir'] .'/hrm/'. $new_attachment_name );
			    }

			    //get attachments table
			    $table_attachments = $wpdb->prefix . 'attachments';

			    $employee_id            = (int) $_POST['employee_id'];
			    $title                  = $_POST['title'];
			    $document_description   = $_POST['document_description'];

			    $data = [
				    'employee_id'     => $employee_id,
				    'title'           => $title,
				    'attachment'      => $new_attachment_name,
				    'description'     => $document_description,
				    'created_at'      => current_time('mysql', false),
			    ];

			    $format = array( '%d', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'title'           => [ 'required', 'string' ],
				    'employee_id'     => [ 'required', 'integer' ],
				    'attachment'      => [ 'required', 'string'],
			    ];

			    $customMessages = [
				    'title' => [
					    'required' => __( 'Title is required' )
				    ],
			    ];


			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $insert_row = $wpdb->insert( $table_attachments, $data, $format );
				    // if row inserted in table
				    if( $insert_row ) {
					    wp_send_json_success( __('Document has been added successfully.') );
				    } else {
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }


	    /**
	     * Employee attachment delete ajax
	     * @return void
	     */
	    public function delete_employee_document_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    //get attachment table
			    $table_attachments   = $wpdb->prefix . 'attachments';
			    $attachment_id       = (int) $_POST['attachmentId'];

			    $delete_row = $wpdb->delete( $table_attachments, ['id' => $attachment_id] );

			    if( $delete_row ) {
				    wp_send_json_success( __('Document has been deleted successfully.') );
			    } else {
				    wp_send_json_error( __('Something went wrong. Please try again.') );
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Employee bank add ajax
	     * @return void
	     */
	    public function add_employee_bank_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    // Get bank_account table
			    $table_bank_account = $wpdb->prefix . 'bank_account';

			    $employee_id      = (int) $_POST['employee_id'];
			    $bank_name        = $_POST['bank_name'];
			    $bank_branch      = $_POST['bank_branch'];
			    $bank_account     = $_POST['bank_account'];
			    $bank_description = $_POST['bank_description'];

			    $data = [
				    'employee_id'     => $employee_id,
				    'bank_name'       => $bank_name,
				    'bank_branch'     => $bank_branch,
				    'bank_account'    => $bank_account,
				    'description'     => $bank_description,
				    'created_at'      => current_time('mysql', false),
			    ];

			    $format = array( '%d', '%s', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'bank_name'       => [ 'required', 'string' ],
				    'employee_id'     => [ 'required', 'integer' ],
				    'bank_account'    => [ 'required', 'string'],
			    ];

			    $customMessages = [
				    'bank_name' => [
					    'required' => __( 'Bank Name is required' )
				    ],
				    'bank_account' => [
					    'required' => __( 'Bank Account Number is required' )
				    ],
			    ];


			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $insert_row = $wpdb->insert( $table_bank_account, $data, $format );
				    // if row inserted in table
				    if( $insert_row ) {
					    wp_send_json_success( __('Bank account has been added successfully.') );
				    } else {
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Employee bank edit ajax
	     * @return void
	     */
	    public function edit_employee_bank_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    // Get bank_account table
			    $table_bank_account = $wpdb->prefix . 'bank_account';

			    $bank_account_id  = (int) $_POST['bankId'];
			    $employee_id      = (int) $_POST['employeeId'];
			    $bank_name        = $_POST['bankName'];
			    $bank_branch      = $_POST['bankBranch'];
			    $bank_account     = $_POST['bankAccount'];
			    $bank_description = $_POST['bankDescription'];

			    $data = [
				    'employee_id'     => $employee_id,
				    'bank_name'       => $bank_name,
				    'bank_branch'     => $bank_branch,
				    'bank_account'    => $bank_account,
				    'description'     => $bank_description,
				    'created_at'      => current_time('mysql', false),
			    ];

			    $format = array( '%d', '%s', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'bank_name'       => [ 'required', 'string' ],
				    'employee_id'     => [ 'required', 'integer' ],
				    'bank_account'    => [ 'required', 'string'],
			    ];

			    $customMessages = [
				    'bank_name' => [
					    'required' => __( 'Bank Name is required' )
				    ],
				    'bank_account' => [
					    'required' => __( 'Bank Account Number is required' )
				    ],
			    ];

			    $where = [ 'id' => $bank_account_id ];

			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $update_row = $wpdb->update( $table_bank_account, $data, $where );
				    // if row updated in table
				    if ( $update_row ) {
					    wp_send_json_success( __('Bank account has been updated successfully.') );
				    } else {
					    // Data has not updated
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Employee bank delete ajax
	     * @return void
	     */
	    public function delete_employee_bank_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    //get bank account table
			    $table_bank_account  = $wpdb->prefix . 'bank_account';
			    $bank_id             = (int) $_POST['bankId'];

			    $delete_row = $wpdb->delete( $table_bank_account, ['id' => $bank_id] );

			    if( $delete_row ) {
				    wp_send_json_success( __('Bank has been deleted successfully.') );
			    } else {
				    wp_send_json_error( __('Something went wrong. Please try again.') );
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Add employee salary ajax
	     * @return void
	     */
	    public function add_employee_salary_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    $validator = new Validator;

			    // Get salary table
			    $table_salary = $wpdb->prefix . 'salary';

			    $employee_id        = (int) $_POST['employee_id'];
			    $salary_date        = $_POST['salary_date'];
			    $salary_amount      = $_POST['salary_amount'];
			    $salary_description = $_POST['salary_description'];

			    $data = [
				    'employee_id'   => $employee_id,
				    'date'          => $salary_date,
				    'amount'        => $salary_amount,
				    'description'   => $salary_description,
				    'created_at'    => current_time('mysql', false),
			    ];

			    $format = array( '%d', '%s', '%s', '%s', '%s' );

			    $rules = [
				    'employee_id'    => [ 'required', 'integer' ],
				    'date'           => [ 'required', 'string' ],
				    'amount'         => [ 'required', 'string'],
			    ];

			    $customMessages = [
				    'date' => [
					    'required' => __( 'Date is required' )
				    ],
				    'amount' => [
					    'required' => __( 'Amount is required' )
				    ],
			    ];


			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $insert_row = $wpdb->insert( $table_salary, $data, $format );
				    // if row inserted in table
				    if( $insert_row ) {
					    wp_send_json_success( __('Salary has been added successfully.') );
				    } else {
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Employee salary edit ajax
	     * @return void
	     */
	    public function edit_employee_salary_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;

				//print_r($_POST);
				//exit();

			    $validator = new Validator;
			    // Get salary table
			    $table_salary = $wpdb->prefix . 'salary';

			    $salary_id          = (int) $_POST['salaryId'];
			    $employee_id        = (int) $_POST['employeeId'];
			    $salary_date        = $_POST['salaryDate'];
			    $salary_amount      = $_POST['salaryAmount'];
			    $salary_description = $_POST['salaryDescription'];

			    $data = [
				    'employee_id'   => $employee_id,
				    'date'          => $salary_date,
				    'amount'        => $salary_amount,
				    'description'   => $salary_description,
			    ];

			    $format = array( '%d', '%s', '%s', '%s' );

			    $rules = [
				    'employee_id'    => [ 'required', 'integer' ],
				    'date'           => [ 'required', 'string' ],
				    'amount'         => [ 'required', 'string'],
			    ];

			    $customMessages = [
				    'date' => [
					    'required' => __( 'Date is required' )
				    ],
				    'amount' => [
					    'required' => __( 'Amount is required' )
				    ],
			    ];

			    $where = [ 'id' => $salary_id ];

			    $validation = $validator->make( $data, $rules, $customMessages );

			    if ( $validation->fails() ) {
				    wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

			    } else {
				    $update_row = $wpdb->update( $table_salary, $data, $where );
				    // if row updated in table
				    if ( $update_row ) {
					    wp_send_json_success( __('Salary has been updated successfully.') );
				    } else {
					    // Data has not updated
					    wp_send_json_error( __('Something went wrong. Please try again.') );
				    }
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }

	    /**
	     * Employee salary delete ajax
	     * @return void
	     */
	    public function delete_employee_salary_ajax_action() {
		    if ( check_ajax_referer( '_ajax_nonce' ) ) {
			    global $wpdb;
			    // get salary table
			    $table_salary  = $wpdb->prefix . 'salary';
			    $salary_id     = (int) $_POST['salaryId'];

			    $delete_row = $wpdb->delete( $table_salary, ['id' => $salary_id] );

			    if( $delete_row ) {
				    wp_send_json_success( __('Salary has been deleted successfully.') );
			    } else {
				    wp_send_json_error( __('Something went wrong. Please try again.') );
			    }

		    } else {
			    wp_send_json_error();
		    };
	    }



    } // class end

	Hrm_Employee::instance()->initialize();
}