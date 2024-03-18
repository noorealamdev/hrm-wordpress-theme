<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$department_id = (int) $args['data']['department_id'];
$department = Hrm_DbQuery::get_table_single_row('departments', $department_id);

if ( ! $department ) {
	return;
} else {

	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
		global $wpdb;
		$validator = new Validator;

		// Nonce check
		if ( isset( $_POST['_wpnonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_department_nonce' ) ) {
				wp_die( 'Are you cheating?' );
				// Anything that you want to display for unauthorized action
			}
		}

		// Get table
		$table_departments = $wpdb->prefix . 'departments';

		$department_name = $_POST['department_name'];
		$company_id      = $_POST['company_id'];
		$department_head = $_POST['department_head'];

		$data = [
			'name'            => $department_name,
			'company_id'      => $company_id,
			'department_head' => $department_head,
		];

		$rules = [
			'name'       => [ 'required', 'string' ],
			'company_id' => [ 'required', 'integer' ],
		];

		$customMessages = [
			'name'       => [
				'required' => __( 'Department Name is required' )
			],
			'company_id' => [
				'required' => __( 'Please choose a company' )
			],
		];

		$where = [ 'id' => $department_id ];


		$validation = $validator->make( $data, $rules, $customMessages );

		if ( $validation->fails() ) {
			Hrm_Utils::validation_error_alert( $validation->errors() );
		} else {
			$update_row = $wpdb->update( $table_departments, $data, $where );
			// if row updated in table
			if ( $update_row ) {
			    Hrm_Utils::set_transient('department_updated', 'Department has been updated successfully.');
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
                <form action="" id="editDepartmentForm" method="post">

					<?php wp_nonce_field( 'edit_department_nonce' ); ?>

                    <div class="form-group mb-4">
                        <label class="label"><?php esc_html_e( 'Department Name', 'cm-hrm' ); ?> <span
                                    style="color: red">*</span></label>
                        <input name="department_name" type="text" value="<?php echo $department->name ?>"
                               class="form-control text-dark">
                    </div>
                    <div class="form-group mb-4">
                        <label class="label"><?php esc_html_e( 'Company', 'cm-hrm' ); ?> <span
                                    style="color: red">*</span></label>
                        <div class="form-group position-relative">
                            <select id="company_id" name="company_id" class="form-select form-control h-58">
								<?php
								$company = Hrm_DbQuery::get_table_data( 'company' );
								foreach ( $company as $item ) { ?>
                                    <option value="<?php echo $item->id ?>"
                                            class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'departments', 'company_id', $department->id ) == $item->id ? 'selected' : '' ?>><?php echo $item->name ?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="label"><?php esc_html_e( 'Department Head', 'cm-hrm' ); ?></label>
                        <input name="department_head" type="text" value="<?php echo $department->department_head ?>"
                               class="form-control text-dark">
                    </div>
                    <div class="form-group d-flex gap-3">
                        <input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e( 'Update Department', 'cm-hrm' ); ?>">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php }