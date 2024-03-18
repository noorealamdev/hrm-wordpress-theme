<?php
defined( 'ABSPATH' ) || exit;

use BitApps\WPValidator\Validator;

if ( ! class_exists( 'Hrm_Invoice' ) ) {
	class Hrm_Invoice {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {


			add_action('init', [$this, 'create_db_invoices_table']);
			add_shortcode('invoice_page', [$this, 'invoice_page_shortcode']);

			add_action('wp_ajax_create_invoice_ajax_action', [$this, 'create_invoice_ajax_action']);
			add_action('wp_ajax_update_invoice_ajax_action', [$this, 'update_invoice_ajax_action']);
			add_action('wp_ajax_delete_invoice_ajax_action', [$this, 'delete_invoice_ajax_action']);
			add_action('wp_ajax_change_payment_status_invoice_ajax_action', [$this, 'change_payment_status_invoice_ajax_action']);

		}

		public function create_db_invoices_table() {
			global $wpdb;

			$table_name = $wpdb->prefix . "invoices";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  date VARCHAR(255) NOT NULL,
                  invoice_name VARCHAR(255) NOT NULL,
                  invoice_address VARCHAR(255) NOT NULL,
                  invoice_email VARCHAR(255) NULL,
                  pay_name VARCHAR(255) NOT NULL,
                  pay_address VARCHAR(255) NOT NULL,
                  pay_email VARCHAR(255) NULL,
                  items_data JSON NOT NULL,
                  payment_status VARCHAR(20) NULL,
                  created_at datetime NULL,
                  PRIMARY KEY  (id)
            ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		public function invoice_page_shortcode( $atts ) {
			$attributes = shortcode_atts( array(
				'title' => 'Invoice Index Page',
			), $atts );
			ob_start();

			get_template_part('template-parts/invoice/index', null, $attributes);

			return ob_get_clean();
		}

		// invoice create
		public function create_invoice_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				$validator = new Validator;

				//get invoices table
				$table_invoices  = $wpdb->prefix . 'invoices';

				$date               = $_POST['date'];
				$invoice_name       = $_POST['invoice_name'];
				$invoice_address    = $_POST['invoice_address'];
				$invoice_email      = $_POST['invoice_email'];
				$payment_status     = $_POST['payment_status'];
				$pay_name           = $_POST['pay_name'];
				$pay_address        = $_POST['pay_address'];
				$pay_email          = $_POST['pay_email'];
				// Repeater fields data
				$item_name          = $_POST['item_name'];
				$item_desc          = $_POST['item_desc'];
				$item_qty           = $_POST['item_qty'];
				$item_price         = $_POST['item_price'];
				// Combine all arrays into one array $items_data
				$items_data = ['item_name' => $item_name, 'item_desc' => $item_desc, 'item_qty' => $item_qty, 'item_price' => $item_price];

				$data = [
					'date'              => $date,
					'invoice_name'      => $invoice_name,
					'invoice_address'   => $invoice_address,
					'invoice_email'     => $invoice_email,
					'payment_status'    => $payment_status,
					'pay_name'          => $pay_name,
					'pay_address'       => $pay_address,
					'pay_email'         => $pay_email,
					'items_data'        => json_encode($items_data),
					'created_at'        => current_time('mysql', false),
				];

				$format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

				$rules = [
					'invoice_name'     => [ 'required', 'string' ],
					'invoice_address'  => [ 'required', 'string' ],
					'invoice_email'    => [ 'nullable', 'email' ],
					'pay_name'         => [ 'required', 'string' ],
					'pay_address'      => [ 'required', 'string' ],
					'pay_email'        => [ 'nullable', 'email' ],
					'items_data'       => [ 'required', 'string' ],
					'item_name'        => [ 'required', 'string' ],
					'item_qty'         => [ 'required', 'string' ],
					'item_price'       => [ 'required', 'string' ],
				];

				$customMessages = [
					'invoice_name' => [
						'required' => __( 'Invoice Name is required' )
					],
					'invoice_address' => [
						'required' => __( 'Invoice Address is required' )
					],
					'pay_name' => [
						'required' => __( 'Pay Name is required' )
					],
					'pay_address' => [
						'required' => __( 'Pay Address is required' )
					],
					'invoice_email' => [
						'email' => __('The invoice provided email is not valid')
					],
					'pay_email' => [
						'email' => __('The pay provided email is not valid')
					],
				];


				$validation = $validator->make( $data, $rules, $customMessages );

				if ( $validation->fails() ) {
					wp_send_json( ['validationError' => true, 'validationMessage' => $validation->errors()] );

				} else {
					$insert_row = $wpdb->insert( $table_invoices, $data, $format );
					// if row inserted in table
					if( $insert_row ) {
						wp_send_json_success( __('Invoice has been created successfully.') );
					} else {
						wp_send_json_error( __('Something went wrong. Please try again.') );
					}
				}
				wp_die();

			} else {
				wp_send_json_error();
			};
		}

		// invoice delete
		public function delete_invoice_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				// Get invoice table
				$table_invoices     = $wpdb->prefix . 'invoices';
				$invoice_id      = intval($_POST['invoiceId']);

				$delete_row = $wpdb->delete( $table_invoices, ['id' => $invoice_id] );

				if( $delete_row ) {
					wp_send_json_success( __('Invoice has been deleted successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}

			} else {
				wp_send_json_error();
			};
		}

		// invoice change payment status ajax
		public function change_payment_status_invoice_ajax_action() {
			if ( check_ajax_referer( '_ajax_nonce' ) ) {
				global $wpdb;
				// Get invoice table
				$table_invoices  = $wpdb->prefix . 'invoices';
				$invoice_id      = $_POST['invoice_id'];
				$payment_status  = $_POST['paymentStatus'];

				$data = [
					'payment_status' => $payment_status,
				];
				$where = [ 'id' => $invoice_id ];
				$update_row = $wpdb->update( $table_invoices, $data, $where );
				// if row inserted in table
				if( $update_row ) {
					wp_send_json_success( __('Payment status has been updated successfully.') );
				} else {
					wp_send_json_error( __('Something went wrong. Please try again.') );
				}

			} else {
				wp_send_json_error();
			};
		}
		

	} // class end

	Hrm_Invoice::instance()->initialize();
}