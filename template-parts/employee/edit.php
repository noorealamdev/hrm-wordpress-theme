<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$employee_id = $args['data']['employee_id'];

if ( ! $employee_id ) {
	return;
} else {

	// Employee single table data
	$employee = Hrm_DbQuery::get_table_single_row('employees', $employee_id);

    // Employee attachment table data
	$attachments = Hrm_DbQuery::get_table_rows_where('attachments', 'employee_id', $employee_id);

	// Bank table data
	$bank_account = Hrm_DbQuery::get_table_rows_where('bank_account', 'employee_id', $employee_id);

	// Salary table data
	$salaries = Hrm_DbQuery::get_table_rows_where('salary', 'employee_id', $employee_id);
	?>

    <!-- Edit Employee Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-white border-0 rounded-10">
                <div class="card-body p-4">
                    <div id="validation_error_message" class="validation_error_message"></div>
                    <ul class="nav nav-tabs" id="employeeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employee-info-tab-pane" type="button" role="tab" aria-controls="employee-info-tab-pane" aria-selected="true"><?php echo esc_html__('Employee Info', 'cm-hrm') ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-attachment-tab" data-bs-toggle="tab" data-bs-target="#employee-attachment-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><?php echo esc_html__('Documents', 'cm-hrm') ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#employee-account-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false"><?php echo esc_html__('Bank Account', 'cm-hrm') ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#employee-salary-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false"><?php echo esc_html__('Salary', 'cm-hrm') ?></button>
                        </li>
                    </ul>
                    <div class="tab-content py-4" id="myTabContent">
                        <!--Employee Info Tab Start-->
                        <div class="tab-pane fade show active" id="employee-info-tab-pane" role="tabpanel" aria-labelledby="employee-tab" tabindex="0">
                            <form id="updateEmployeeForm" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <input id="urlRef" type="hidden" value="<?php echo get_permalink() ?>">
                                    <input id="employee_id" name="employee_id" type="hidden" value="<?php echo $employee_id ?>">
                                    <div class="col-lg-4">
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Name', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <input id="name" name="name" type="text" value="<?php echo $employee->name ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Gender', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <div class="form-group position-relative">
                                                <select id="gender" name="gender" class="form-select form-control">
                                                    <option value="male" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'employees', 'gender', $employee->id ) == 'male' ? 'selected' : '' ?>><?php esc_html_e( 'Male', 'cm-hrm' ); ?></option>
                                                    <option value="female" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'employees', 'gender', $employee->id ) == 'female' ? 'selected' : '' ?>><?php esc_html_e( 'Female', 'cm-hrm' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Phone', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <input id="phone" name="phone" type="text" value="<?php echo $employee->phone ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Email', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <input id="email" name="email" type="text" value="<?php echo $employee->email ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Company', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <div class="form-group position-relative">
                                                <select id="company_id" name="company_id" class="form-select form-control h-58">
							                        <?php
							                        $company = Hrm_DbQuery::get_table_data( 'company' );
							                        foreach ( $company as $company_item ) { ?>
                                                        <option value="<?php echo $company_item->id ?>" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'employees', 'company_id', $employee->id ) == $company_item->id ? 'selected' : '' ?>><?php echo $company_item->name ?></option>
							                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Department', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <div class="form-group position-relative">
                                                <select id="department_id" name="department_id"
                                                        class="form-select form-control h-58">
							                        <?php
							                        $departments = Hrm_DbQuery::get_table_data( 'departments' );
							                        foreach ( $departments as $department ) { ?>
                                                        <option value="<?php echo $department->id ?>"
                                                                class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'employees', 'department_id', $employee_id ) == $department->id ? 'selected' : '' ?>><?php echo $department->name ?></option>
							                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Designation', 'cm-hrm' ); ?> <span
                                                        style="color: red">*</span></label>
                                            <div class="form-group position-relative">
                                                <select name="designation_id" class="form-select form-control h-58">
							                        <?php
							                        $designations = Hrm_DbQuery::get_table_data( 'designation' );
							                        foreach ( $designations as $designation ) { ?>
                                                        <option value="<?php echo $designation->id ?>"
                                                                class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'employees', 'designation_id', $employee_id ) == $designation->id ? 'selected' : '' ?>><?php echo $designation->name ?></option>
							                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Date of Birth', 'cm-hrm' ); ?></label>
                                            <div class="form-group position-relative">
                                                <input type="date" id="dob" name="dob" value="<?php echo $employee->dob ?>" class="form-control text-dark">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Address', 'cm-hrm' ); ?></label>
                                            <input id="address" name="address" type="text" value="<?php echo $employee->address ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'City', 'cm-hrm' ); ?></label>
                                            <input id="city" name="city" type="text" value="<?php echo $employee->city ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Province', 'cm-hrm' ); ?></label>
                                            <input id="province" name="province" type="text" value="<?php echo $employee->province ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Zip Code', 'cm-hrm' ); ?></label>
                                            <input id="zip" name="zip" type="text" value="<?php echo $employee->zip ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Country', 'cm-hrm' ); ?></label>
                                            <input id="country" name="country" type="text" value="<?php echo $employee->country ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Joining Date', 'cm-hrm' ); ?></label>
                                            <div class="form-group position-relative">
                                                <input type="date" id="joining_date" name="joining_date" value="<?php echo $employee->joining_date ?>" class="form-control text-dark">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Employee Photo (only jpg or png file)', 'cm-hrm' ); ?></label>
                                            <input id="photo" name="photo" type="file" class="form-control text-dark" accept=".jpg,.jpeg,.png">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Skype', 'cm-hrm' ); ?></label>
                                            <input id="skype" name="skype" type="text" value="<?php echo $employee->skype ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'WhatsApp', 'cm-hrm' ); ?></label>
                                            <input id="whatsapp" name="whatsapp" type="text" value="<?php echo $employee->whatsapp ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Facebook', 'cm-hrm' ); ?></label>
                                            <input id="facebook" name="facebook" type="text" value="<?php echo $employee->facebook ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'LinkedIn', 'cm-hrm' ); ?></label>
                                            <input id="linkedin" name="linkedin" type="text" value="<?php echo $employee->linkedin ?>" class="form-control text-dark">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="label"><?php esc_html_e( 'Notes', 'cm-hrm' ); ?></label>
                                            <textarea id="notes" name="notes" class="form-control text-dark"
                                                      placeholder="Some notes ... " rows="2"><?php echo $employee->notes ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex gap-3">
                                    <input type="submit" name="submit"
                                           class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                           value="<?php esc_attr_e( 'Update Employee', 'cm-hrm' ); ?>">

                                </div>
                            </form>
                        </div>
                        <!--Employee Info Tab End-->

                        <!--Document Tab Start-->
                        <div class="tab-pane fade" id="employee-attachment-tab-pane" role="tabpanel" aria-labelledby="employee-attachment-tab" tabindex="0">
                            <a href="javascript:;" class="btn btn-primary text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                <span class="py-sm-1 d-block">
                                    <i class="ri-add-line text-white"></i>
                                    <span><?php esc_html_e('Add Document', 'cm-hrm'); ?></span>
                                </span>
                            </a>
                            <div class="default-table-area mt-5">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="dataTable">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php esc_html_e('Title', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Attachment', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Description', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
				                        <?php foreach( $attachments as $attachment ) : ?>
                                            <tr>
                                                <td><span><?php echo $attachment->title ?></span></td>
                                                <td><a target="_blank" href="<?php echo Hrm_Utils::get_uploaded_image($attachment->attachment) ?>">View Attachment</a></td>
                                                <td><span><?php echo $attachment->description ?></span></td>
                                                <td>
                                                    <div class="action-option">
                                                        <a href="javascript:" data-bs-toggle="modal" data-bs-target="#deleteAttachmentModal<?php echo $attachment->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="feather-20" data-feather="trash-2"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deleteAttachmentModal<?php echo $attachment->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Document', 'cm-hrm');?></h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button onclick="deleteAttachment(<?php echo $attachment->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
                                                            <script>
                                                                // Delete data
                                                                function deleteAttachment(attachmentId) {
                                                                    jQuery.ajax({
                                                                        type: 'POST',
                                                                        url: localize._ajax_url,
                                                                        data: {
                                                                            action: 'delete_employee_document_ajax_action',
                                                                            _ajax_nonce: localize._ajax_nonce,
                                                                            attachmentId,
                                                                        },
                                                                        success: (res) => {
                                                                            console.log(res);
                                                                            if (res.success === true) {
                                                                                toastr.success(res.data);
                                                                                setTimeout(function() {
                                                                                    window.location = window.location;
                                                                                }, 2000);
                                                                            } else {
                                                                                toastr.error(res.data);
                                                                            }
                                                                        },
                                                                        error: (err) => {
                                                                            console.log(err);
                                                                        }
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
				                        <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Modal Add Document -->
                            <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Add Document', 'cm-hrm');?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addDocumentForm" method="post" enctype="multipart/form-data">
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>">
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Title', 'cm-hrm' ); ?> <span
                                                                style="color: red">*</span></label>
                                                    <input id="title" name="title" type="text" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Attachment', 'cm-hrm' ); ?> <span
                                                                style="color: red">*</span></label>
                                                    <input id="attachment" name="attachment" type="file" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Provide any details', 'cm-hrm' ); ?></label>
                                                    <textarea id="document_description" name="document_description" class="form-control text-dark" rows="2"></textarea>
                                                </div>

                                                <input type="submit" name="submit"
                                                       class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                                       value="<?php esc_attr_e( 'Submit', 'cm-hrm' ); ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Document Tab End-->

                        <!--Bank Account Tab Start-->
                        <div class="tab-pane fade" id="employee-account-tab-pane" role="tabpanel" aria-labelledby="employee-account-tab" tabindex="0">
                            <a href="javascript:;" class="btn btn-primary text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#addBankModal">
                                <span class="py-sm-1 d-block">
                                    <i class="ri-add-line text-white"></i>
                                    <span><?php esc_html_e('Add Bank', 'cm-hrm'); ?></span>
                                </span>
                            </a>
                            <div class="default-table-area mt-5">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="dataTableBank">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php esc_html_e('Bank Name', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Bank Branch', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Account Number', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Description', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
				                        <?php foreach( $bank_account as $bank ) : ?>
                                            <tr>
                                                <td><span><?php echo $bank->bank_name ?></span></td>
                                                <td><span><?php echo $bank->bank_branch ?></span></td>
                                                <td><span><?php echo $bank->bank_account ?></span></td>
                                                <td><span><?php echo $bank->description ?></span></td>
                                                <td>
                                                    <div class="action-option">
                                                        <a class="me-3" href="javascript:" data-bs-toggle="modal" data-bs-target="#editBankModal<?php echo $bank->id ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="feather-20" data-feather="edit" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                        </a>
                                                        <a href="javascript:" data-bs-toggle="modal" data-bs-target="#deleteBankModal<?php echo $bank->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="feather-20" data-feather="trash-2"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Bank Edit -->
                                            <div class="modal fade" id="editBankModal<?php echo $bank->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Edit Bank', 'cm-hrm');?></h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="editBankForm" method="post">
                                                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>">
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Bank Name', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                                    <input id="bank_name" name="bank_name" type="text" value="<?php echo Hrm_DbQuery::get_table_field_value('bank_account', 'bank_name', $bank->id) ?>" class="form-control text-dark">
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Bank Branch', 'cm-hrm' ); ?></label>
                                                                    <input id="bank_branch" name="bank_branch" type="text" value="<?php echo Hrm_DbQuery::get_table_field_value('bank_account', 'bank_branch', $bank->id) ?>" class="form-control text-dark">
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Account Number', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                                    <input id="bank_account" name="bank_account" type="text" value="<?php echo Hrm_DbQuery::get_table_field_value('bank_account', 'bank_account', $bank->id) ?>" class="form-control text-dark">
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Provide any details', 'cm-hrm' ); ?></label>
                                                                    <textarea id="bank_description" name="bank_description" class="form-control text-dark" rows="2"><?php echo Hrm_DbQuery::get_table_field_value('bank_account', 'description', $bank->id) ?></textarea>
                                                                </div>

                                                                <button onclick="editBank(<?php echo $bank->id ?>)" type="button" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"><?php esc_attr_e( 'Update', 'cm-hrm' ); ?></button>
                                                            </form>

                                                            <script>
                                                                // Edit data
                                                                function editBank(bankId) {
                                                                    let employeeId      = jQuery('#employee_id').val();
                                                                    let bankName        = jQuery('#bank_name').val();
                                                                    let bankBranch      = jQuery('#bank_branch').val();
                                                                    let bankAccount     = jQuery('#bank_account').val();
                                                                    let bankDescription = jQuery('#bank_description').val();
                                                                    jQuery.ajax({
                                                                        type: 'POST',
                                                                        url: localize._ajax_url,
                                                                        data: {
                                                                            action: 'edit_employee_bank_ajax_action',
                                                                            _ajax_nonce: localize._ajax_nonce,
                                                                            bankId,
                                                                            employeeId,
                                                                            bankName,
                                                                            bankBranch,
                                                                            bankAccount,
                                                                            bankDescription,
                                                                        },
                                                                        success: (res) => {
                                                                            console.log(res);
                                                                            if (res.validationError === true) {
                                                                                toastr.error('Bank Name & Account Number fields are required');
                                                                            } else if (res.success === true) {
                                                                                toastr.success(res.data);
                                                                                setTimeout(function () {
                                                                                    window.location = window.location;
                                                                                }, 3000);
                                                                            } else {
                                                                                toastr.error(res.data);
                                                                            }
                                                                        },
                                                                        error: (err) => {
                                                                            console.log(err);
                                                                        }
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Bank Delete -->
                                            <div class="modal fade" id="deleteBankModal<?php echo $bank->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Bank', 'cm-hrm');?></h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button onclick="deleteBank(<?php echo $bank->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
                                                            <script>
                                                                // Delete data
                                                                function deleteBank(bankId) {
                                                                    jQuery.ajax({
                                                                        type: 'POST',
                                                                        url: localize._ajax_url,
                                                                        data: {
                                                                            action: 'delete_employee_bank_ajax_action',
                                                                            _ajax_nonce: localize._ajax_nonce,
                                                                            bankId,
                                                                        },
                                                                        success: (res) => {
                                                                            console.log(res);
                                                                            if (res.success === true) {
                                                                                toastr.success(res.data);
                                                                                setTimeout(function() {
                                                                                    window.location = window.location;
                                                                                }, 2000);
                                                                            } else {
                                                                                toastr.error(res.data);
                                                                            }
                                                                        },
                                                                        error: (err) => {
                                                                            console.log(err);
                                                                        }
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
				                        <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Modal Bank Add -->
                            <div class="modal fade" id="addBankModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Add Bank', 'cm-hrm');?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addBankForm" method="post">
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>">
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Bank Name', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                    <input id="bank_name" name="bank_name" type="text" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Bank Branch', 'cm-hrm' ); ?></label>
                                                    <input id="bank_branch" name="bank_branch" type="text" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Account Number', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                    <input id="bank_account" name="bank_account" type="text" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Provide any details', 'cm-hrm' ); ?></label>
                                                    <textarea id="description" name="description" class="form-control text-dark" rows="2"></textarea>
                                                </div>

                                                <input type="submit" name="submit"
                                                       class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                                       value="<?php esc_attr_e( 'Submit', 'cm-hrm' ); ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Bank Account Tab End-->

                        <!--Salary Tab Start-->
                        <div class="tab-pane fade" id="employee-salary-tab-pane" role="tabpanel" aria-labelledby="employee-salary-tab" tabindex="0">
                            <a href="javascript:" class="btn btn-primary text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#addSalaryModal">
                                <span class="py-sm-1 d-block">
                                    <i class="ri-add-line text-white"></i>
                                    <span><?php esc_html_e('Add Salary', 'cm-hrm'); ?></span>
                                </span>
                            </a>
                            <div class="default-table-area mt-5">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="dataTableSalary">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php esc_html_e('Date', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Amount', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Description', 'cm-hrm');?></th>
                                            <th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
					                    <?php foreach( $salaries as $salary ) : ?>
                                            <tr>
                                                <td><span><?php echo $salary->date ?></span></td>
                                                <td><span><?php echo $salary->amount ?></span></td>
                                                <td><span><?php echo $salary->description ?></span></td>
                                                <td>
                                                    <div class="action-option">
                                                        <a class="me-3" href="javascript:" data-bs-toggle="modal" data-bs-target="#editSalaryModal<?php echo $salary->id ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="feather-20" data-feather="edit" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                        </a>
                                                        <a href="javascript:" data-bs-toggle="modal" data-bs-target="#deleteSalaryModal<?php echo $salary->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="feather-20" data-feather="trash-2"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Salary Edit -->
                                            <div class="modal fade" id="editSalaryModal<?php echo $salary->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Edit Salary', 'cm-hrm');?></h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="editSalaryForm" method="post">
                                                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>">
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Salary Date', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                                    <div class="form-group position-relative">
                                                                        <input type="date" id="salary_date" name="salary_date" value="<?php echo $salary->date ?>" class="form-control text-dark">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Salary Amount', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                                    <input id="salary_amount" name="salary_amount" type="text" value="<?php echo $salary->amount ?>" class="form-control text-dark">
                                                                </div>
                                                                <div class="form-group mb-4">
                                                                    <label class="label"><?php esc_html_e( 'Provide any details', 'cm-hrm' ); ?></label>
                                                                    <textarea id="salary_description" name="salary_description" class="form-control text-dark" rows="2"><?php echo $salary->description ?></textarea>
                                                                </div>

                                                                <button onclick="editSalary(<?php echo $salary->id ?>)" type="button" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"><?php esc_attr_e( 'Update', 'cm-hrm' ); ?></button>
                                                            </form>

                                                            <script>
                                                                // Edit salary data
                                                                function editSalary(salaryId) {
                                                                    let employeeId        = jQuery('#employee_id').val();
                                                                    let salaryDate        = jQuery('#salary_date').val();
                                                                    let salaryAmount      = jQuery('#salary_amount').val();
                                                                    let salaryDescription = jQuery('#salary_description').val();
                                                                    jQuery.ajax({
                                                                        type: 'POST',
                                                                        url: localize._ajax_url,
                                                                        data: {
                                                                            action: 'edit_employee_salary_ajax_action',
                                                                            _ajax_nonce: localize._ajax_nonce,
                                                                            salaryId,
                                                                            employeeId,
                                                                            salaryDate,
                                                                            salaryAmount,
                                                                            salaryDescription,
                                                                        },
                                                                        success: (res) => {
                                                                            console.log(res);
                                                                            if (res.validationError === true) {
                                                                                toastr.error('Salary Date & Amount fields are required');
                                                                            } else if (res.success === true) {
                                                                                toastr.success(res.data);
                                                                                setTimeout(function () {
                                                                                    window.location = window.location;
                                                                                }, 3000);
                                                                            } else {
                                                                                toastr.error(res.data);
                                                                            }
                                                                        },
                                                                        error: (err) => {
                                                                            console.log(err);
                                                                        }
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Salary Delete -->
                                            <div class="modal fade" id="deleteSalaryModal<?php echo $salary->id ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Salary', 'cm-hrm');?></h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button onclick="deleteSalary(<?php echo $salary->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
                                                            <script>
                                                                // Delete data
                                                                function deleteBank(salaryId) {
                                                                    jQuery.ajax({
                                                                        type: 'POST',
                                                                        url: localize._ajax_url,
                                                                        data: {
                                                                            action: 'delete_employee_salary_ajax_action',
                                                                            _ajax_nonce: localize._ajax_nonce,
                                                                            salaryId,
                                                                        },
                                                                        success: (res) => {
                                                                            console.log(res);
                                                                            if (res.success === true) {
                                                                                toastr.success(res.data);
                                                                                setTimeout(function() {
                                                                                    window.location = window.location;
                                                                                }, 2000);
                                                                            } else {
                                                                                toastr.error(res.data);
                                                                            }
                                                                        },
                                                                        error: (err) => {
                                                                            console.log(err);
                                                                        }
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
					                    <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Modal Salary Add -->
                            <div class="modal fade" id="addSalaryModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Add Salary', 'cm-hrm');?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addSalaryForm" method="post">
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>">
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Salary Date', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                    <div class="form-group position-relative">
                                                        <input type="date" id="salary_date" name="salary_date" value="<?php echo date('Y-m-d') ?>" class="form-control text-dark">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Salary Amount', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                                    <input id="salary_amount" name="salary_amount" type="text" class="form-control text-dark">
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="label"><?php esc_html_e( 'Provide any details', 'cm-hrm' ); ?></label>
                                                    <textarea id="salary_description" name="salary_description" class="form-control text-dark" rows="2"></textarea>
                                                </div>

                                                <input type="submit" name="submit"
                                                       class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                                       value="<?php esc_attr_e( 'Submit', 'cm-hrm' ); ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Salary Tab End-->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        (function ($) {

            // Insert data
            $('#updateEmployeeForm').submit(function (e) {
                e.preventDefault();
                $("#validation_error_message ul").remove();

                let photo = $('#photo')[0].files[0];
                let formData = new FormData( $("#updateEmployeeForm")[0] );
                formData.append('photo', photo);
                formData.append('action', 'update_employee_ajax_action');
                formData.append('_ajax_nonce', localize._ajax_nonce);

                // Redirect url
                let urlRef = $('#urlRef').val();

                $.ajax({
                    type: 'POST',
                    url: localize._ajax_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        if (res.validationError === true) {
                            toastr.error('Please check required fields and try again');
                            let html = '<ul>';
                            Object.keys(res.validationMessage).forEach(key => {
                                html += `<li class="error">${res.validationMessage[key][0]}</li>`;
                            });
                            html += '</ul>';
                            $('#validation_error_message').append(html);
                            goUp();
                        } else if (res.success === true) {
                            toastr.success(res.data);
                            setTimeout(function () {
                                window.location = urlRef;
                            }, 3000);
                        } else {
                            toastr.error(res.data);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            });

            // Insert employee document data
            $('#addDocumentForm').submit(function (e) {
                e.preventDefault();
                $("#validation_error_message ul").remove();

                let attachment = $('#attachment')[0].files[0];
                let formData = new FormData( $("#addDocumentForm")[0] );
                formData.append('attachment', attachment);
                formData.append('action', 'add_employee_document_ajax_action');
                formData.append('_ajax_nonce', localize._ajax_nonce);

                $.ajax({
                    type: 'POST',
                    url: localize._ajax_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        if (res.validationError === true) {
                            toastr.error('Title & Attachment fields are required');
                        } else if (res.success === true) {
                            toastr.success(res.data);
                            setTimeout(function () {
                                window.location = window.location;
                            }, 3000);
                        } else {
                            toastr.error(res.data);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            });

            // Insert employee bank account data
            $('#addBankForm').submit(function (e) {
                e.preventDefault();
                $("#validation_error_message ul").remove();

                let formData = new FormData( $("#addBankForm")[0] );
                formData.append('action', 'add_employee_bank_ajax_action');
                formData.append('_ajax_nonce', localize._ajax_nonce);

                $.ajax({
                    type: 'POST',
                    url: localize._ajax_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        if (res.validationError === true) {
                            toastr.error('Bank Name & Account Number fields are required');
                        } else if (res.success === true) {
                            toastr.success(res.data);
                            setTimeout(function () {
                                window.location = window.location;
                            }, 3000);
                        } else {
                            toastr.error(res.data);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            });

            // Insert employee salary data
            $('#addSalaryForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData( $("#addSalaryForm")[0] );
                formData.append('action', 'add_employee_salary_ajax_action');
                formData.append('_ajax_nonce', localize._ajax_nonce);

                $.ajax({
                    type: 'POST',
                    url: localize._ajax_url,
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        if (res.validationError === true) {
                            toastr.error('Salary Date and Salary Amount fields are required');
                        } else if (res.success === true) {
                            toastr.success(res.data);
                            setTimeout(function () {
                                window.location = window.location;
                            }, 3000);
                        } else {
                            toastr.error(res.data);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            });

            // Go to top
            function goUp() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

        })(jQuery);
    </script>

<?php }