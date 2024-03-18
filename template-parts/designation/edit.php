<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$designation_id = (int) $args['data']['designation_id'];
$designation = Hrm_DbQuery::get_table_single_row('designation', $designation_id);

if ( ! $designation ) {
	return;
} else {

	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
		global $wpdb;
		$validator = new Validator;

		// Nonce check
		if ( isset( $_POST['_wpnonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_designation_nonce' ) ) {
				wp_die( 'Are you cheating?' );
				// Anything that you want to display for unauthorized action
			}
		}

		//get table
		$table_designation = $wpdb->prefix . 'designation';

		$designation_name = $_POST['designation_name'];
		$department_id    = $_POST['department_id'];
		$company_id       = $_POST['company_id'];

		$data = [
			'name'          => $designation_name,
			'department_id' => $department_id,
			'company_id'    => $company_id,
		];

		$rules = [
			'name'          => [ 'required', 'string' ],
			'department_id' => [ 'required', 'integer' ],
			'company_id'    => [ 'required', 'integer' ],
		];

		$customMessages = [
			'name'          => [
				'required' => __( 'Designation Name is required' )
			],
			'department_id' => [
				'required' => __( 'Please choose a department' )
			],
			'company_id'    => [
				'required' => __( 'Please choose a company' )
			],
		];

		$where = [ 'id' => $designation_id ];


		$validation = $validator->make( $data, $rules, $customMessages );

		if ( $validation->fails() ) {
			Hrm_Utils::validation_error_alert( $validation->errors() );
		} else {
			$update_row = $wpdb->update( $table_designation, $data, $where );
			// if row updated in table
			if ( $update_row ) {
				Hrm_Utils::set_transient('designation_updated', 'Designation has been updated successfully.');
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
                    <form action="" id="editDesignationForm" method="post">

						<?php wp_nonce_field( 'edit_designation_nonce' ); ?>

                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Designation Name', 'cm-hrm' ); ?> <span
                                        style="color: red">*</span></label>
                            <input name="designation_name" type="text" value="<?php echo $designation->name ?>"
                                   class="form-control text-dark">
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Department', 'cm-hrm' ); ?> <span
                                        style="color: red">*</span></label>
                            <div class="form-group position-relative">
                                <select id="department_id" name="department_id" class="form-select form-control h-58">
									<?php
									$departments = Hrm_DbQuery::get_table_data( 'departments' );
									foreach ( $departments as $department ) { ?>
                                        <option value="<?php echo $department->id ?>"
                                                class="text-dark" <?php Hrm_DbQuery::get_table_field_value( 'designation', 'department_id', $designation->id ) == $department->id ? 'selected' : '' ?>><?php echo $department->name ?></option>
									<?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="label"><?php esc_html_e( 'Company', 'cm-hrm' ); ?> <span
                                        style="color: red">*</span></label>
                            <div class="form-group position-relative">
                                <select id="company_id" name="company_id" class="form-select form-control h-58">
									<?php
									$company = Hrm_DbQuery::get_table_data( 'company' );
									foreach ( $company as $company_item ) { ?>
                                        <option value="<?php echo $company_item->id ?>"
                                                class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'designation', 'company_id', $designation->id ) == $company_item->id ? 'selected' : '' ?>><?php echo $company_item->name ?></option>
									<?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-flex gap-3">
                            <input type="submit" name="submit"
                                   class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                   value="<?php esc_attr_e( 'Update Designation', 'cm-hrm' ); ?>">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php }