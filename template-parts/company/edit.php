<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$company_id = (int) $args['data']['company_id'];

$company = Hrm_DbQuery::get_table_single_row('company', $company_id);

if ( ! $company ) {
	return;
} else {

	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
		global $wpdb;
		$validator = new Validator;

		// Nonce check
		if ( isset( $_POST['_wpnonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_company_nonce' ) ) {
				wp_die( 'Are you cheating?' );
				// Anything that you want to display for unauthorized action
			}
		}

		//get table
		$table_company = $wpdb->prefix . 'company';

		$company_name    = $_POST['company_name'];
		$company_email   = $_POST['company_email'];
		$company_phone   = $_POST['company_phone'];
		$company_country = $_POST['company_country'];

		$data = [
			'name'    => $company_name,
			'email'   => $company_email,
			'phone'   => $company_phone,
			'country' => $company_country,
		];

		$rules = [
			'name'  => [ 'required', 'string' ],
			'email' => [ 'nullable', 'email' ],
		];

		$customMessages = [
			'name'  => [
				'required' => __( 'Company Name is required' )
			],
			'email' => [
				'email' => __( 'The provided email is not valid' )
			],
		];

		$where = [ 'id' => $company_id ];
		//print_r($where);

		$validation = $validator->make( $data, $rules, $customMessages );

		if ( $validation->fails() ) {
			Hrm_Utils::validation_error_alert( $validation->errors() );
		} else {
			$update_row = $wpdb->update( $table_company, $data, $where );
			// if row updated in table
			if ( $update_row ) {
				Hrm_Utils::set_transient('company_updated', 'Company has been updated successfully.');
				wp_redirect( get_permalink() );
				exit;

			} else {
				// Data has not updated
				Hrm_Utils::show_toastr('error', 'Something went wrong, please try again.');
			}
		}
	}
    ?>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-white border-0 rounded-10">
                <div class="card-body p-4">
                    <form action="" id="editCompanyForm" method="post">

                        <?php wp_nonce_field( 'edit_company_nonce' ); ?>

                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Company Name', 'cm-hrm' ); ?> <span
                                        style="color: red">*</span></label>
                            <input name="company_name" type="text" value="<?php echo $company->name ?>"
                                   class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Company Email', 'cm-hrm' ); ?></label>
                            <input name="company_email" type="text" value="<?php echo $company->email ?>"
                                   class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Company Phone', 'cm-hrm' ); ?></label>
                            <input name="company_phone" type="text" value="<?php echo $company->phone ?>"
                                   class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Company Country', 'cm-hrm' ); ?></label>
                            <input name="company_country" type="text" value="<?php echo $company->country ?>"
                                   class="form-control text-dark">
                        </div>
                        <div class="form-group d-flex gap-3">
                            <input type="submit" name="submit"
                                   class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                   value="<?php esc_attr_e( 'Update Company', 'cm-hrm' ); ?>">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php }