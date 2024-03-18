<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$client_id = (int) $args['data']['client_id'];
$client = Hrm_DbQuery::get_table_single_row('clients', $client_id);

if ( ! $client ) {
	return;
} else {
	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
		global $wpdb;
		$validator = new Validator;

		// Nonce check
		if ( isset( $_POST['_wpnonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_client_nonce' ) ) {
				wp_die( 'Are you cheating?' );
				// Anything that you want to display for unauthorized action
			}
		}

		//get company table
		$table_clients = $wpdb->prefix . 'clients';

		$name     = $_POST['client_name'];
		$email    = $_POST['client_email'];
		$phone    = $_POST['client_phone'];
		$address  = $_POST['client_address'];
		$city     = $_POST['client_city'];
		$zip      = $_POST['client_zip'];
		$country  = $_POST['client_country'];

		$data = [
			'name'        => $name,
			'email'       => $email,
			'phone'       => $phone,
			'address'     => $address,
			'city'        => $city,
			'zip'         => $zip,
			'country'     => $country,
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

		$where = [ 'id' => $client_id ];

		$validation = $validator->make( $data, $rules, $customMessages );

		if ( $validation->fails() ) {
			Hrm_Utils::validation_error_alert( $validation->errors() );
		} else {
			$update_row = $wpdb->update( $table_clients, $data, $where );
			// if row updated in table
			if ( $update_row ) {
				Hrm_Utils::set_transient('client_updated', 'Client has been updated successfully.');
				wp_redirect( get_permalink() );
				exit;

			} else {
				// Data has not updated
				$message = esc_html__( 'Something went wrong, please try again.', 'cm-hrm' );
				?>
                <script>toastr.error('<?php echo $message ?>');</script>
				<?php
			}
		}
	}
	?>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-white border-0 rounded-10">
                <div class="card-body p-4">
                    <form action="" id="editClientForm" method="post">

						<?php wp_nonce_field( 'edit_client_nonce' ); ?>

                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Client Name', 'cm-hrm');?> <span style="color: red">*</span></label>
                            <input id="client_name" type="text" name="client_name" value="<?php echo $client->name ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Email', 'cm-hrm');?></label>
                            <input id="client_email" type="text" name="client_email" value="<?php echo $client->email ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Phone', 'cm-hrm');?></label>
                            <input id="client_phone" type="text" name="client_phone" value="<?php echo $client->phone ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Address', 'cm-hrm');?></label>
                            <input id="client_address" type="text" name="client_address" value="<?php echo $client->address ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('City', 'cm-hrm');?></label>
                            <input id="client_city" type="text" name="client_city" value="<?php echo $client->city ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Zip', 'cm-hrm');?></label>
                            <input id="client_zip" type="text" name="client_zip" value="<?php echo $client->zip ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e('Country', 'cm-hrm');?></label>
                            <input id="client_country" type="text" name="client_country" value="<?php echo $client->country ?>" class="form-control text-dark">
                        </div>
                        <div class="form-group d-flex gap-3">
                            <input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e( 'Update Client', 'cm-hrm' ); ?>">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php }