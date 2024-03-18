<?php
defined( 'ABSPATH' ) || exit;

$designations = Hrm_DbQuery::get_table_data('designation');
Hrm_Utils::get_transient_toast('designation_updated');
?>

<div class="card bg-white border-0 rounded-10 mb-4">
	<div class="card-body p-4">
		<div class="d-sm-flex text-center justify-content-between align-items-center pb-20 mb-20">
			<button class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3"
			        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="py-sm-1 d-block">
                    <i class="ri-add-line text-white"></i>
                    <span><?php esc_html_e('Add Designation', 'cm-hrm'); ?></span>
                </span>
			</button>
		</div>
		<div class="default-table-area mt-5">
			<div class="table-responsive">
				<table class="table align-middle" id="dataTable">
					<thead>
					<tr>
						<th scope="col"><?php esc_html_e('Designation', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Department', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Company', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach( $designations as $designation ) : ?>
						<tr>
							<td><span><?php echo $designation->name ?></span></td>
							<td><span><?php echo Hrm_DbQuery::get_table_field_value('departments', 'name', $designation->department_id) ?></span></td>
							<td><span><?php echo Hrm_DbQuery::get_table_field_value('company', 'name', $designation->company_id) ?></span></td>
							<td>
								<div class="action-option">
                                    <a class="me-3" href="<?php echo get_permalink() . '?edit_designation=true&designation_id='. $designation->id .'' ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="feather-20" data-feather="edit"></i>
                                    </a>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteDesignationModal<?php echo $designation->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="feather-20" data-feather="trash-2"></i>
                                    </a>
								</div>
							</td>
						</tr>

						<!-- Modal Delete -->
						<div class="modal fade" id="deleteDesignationModal<?php echo $designation->id ?>" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Department', 'cm-hrm');?></h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body text-center">
										<h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
									</div>
									<div class="modal-footer">
										<button onclick="deleteDesignation(<?php echo $designation->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
										<script>
                                            // Delete data
                                            function deleteDesignation(designationId) {
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: localize._ajax_url,
                                                    data: {
                                                        action: 'delete_designation_ajax_action',
                                                        _ajax_nonce: localize._ajax_nonce,
                                                        designationId,
                                                    },
                                                    success: (res) => {
                                                        console.log(res);
                                                        if (res.success === true) {
                                                            toastr.success(res.data);
                                                            setTimeout(function() {
                                                                window.location=window.location;
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
	</div>
</div>

<!-- Offcanvas Add Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
	<div class="offcanvas-header border-bottom p-4">
		<h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel"><?php esc_html_e('Create Designation', 'cm-hrm'); ?></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body p-4">
		<div id="validation_error_message" class="validation_error_message"></div>
		<form id="createDesignationForm" method="post">
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Designation', 'cm-hrm');?> <span style="color: red">*</span></label>
				<input id="designation_name" type="text" name="designation_name" class="form-control text-dark">
			</div>
            <div class="form-group mb-4">
                <label class="label"><?php esc_html_e('Department', 'cm-hrm');?> <span style="color: red">*</span></label>
                <div class="form-group position-relative">
                    <select id="department_id" name="department_id" class="form-select form-control h-58">
                        <?php
                        $departments = Hrm_DbQuery::get_table_data('departments');
                        foreach ($departments as $department) { ?>
                            <option value="<?php echo $department->id ?>" class="text-dark"><?php echo $department->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="label"><?php esc_html_e('Company', 'cm-hrm');?> <span style="color: red">*</span></label>
                <div class="form-group position-relative">
                    <select id="company_id" name="company_id" class="form-select form-control h-58">
                        <?php
                        $company = Hrm_DbQuery::get_table_data('company');
                        foreach ($company as $company_item) { ?>
                            <option value="<?php echo $company_item->id ?>" class="text-dark"><?php echo $company_item->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
			<div class="form-group d-flex gap-3">
				<input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e('Create Designation', 'cm-hrm');?>">

			</div>
		</form>
	</div>
</div>

<script>
    ( function( $ ) {

        // Insert data
        $('#createDesignationForm').submit(function(e) {
            e.preventDefault();
            $("#validation_error_message ul").remove();

            let designationName = $('#designation_name').val();
            let departmentId = $('#department_id').val();
            let companyId = $('#company_id').val();

            $.ajax({
                type: 'POST',
                url: localize._ajax_url,
                data: {
                    action: 'create_designation_ajax_action',
                    _ajax_nonce: localize._ajax_nonce,
                    designationName,
                    departmentId,
                    companyId,
                },
                success: (res) => {
                    console.log(res);
                    if (res.validationError === true) {
                        let html = '<ul>';
                        Object.keys(res.validationMessage).forEach(key => {
                            html += `<li class="error">${res.validationMessage[key][0]}</li>`;
                        });
                        html += '</ul>';
                        $('#validation_error_message').append(html);
                    }
                    else if (res.success === true) {
                        toastr.success(res.data);
                        setTimeout(function() {
                            window.location=window.location;
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

    })( jQuery );
</script>