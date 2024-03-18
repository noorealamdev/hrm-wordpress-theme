<?php
defined( 'ABSPATH' ) || exit;

// Get employees table data
$employees = Hrm_DbQuery::get_table_data('employees');
?>

<div class="card bg-white border-0 rounded-10 mb-4">
	<div class="card-body p-4">
		<div class="d-sm-flex text-center justify-content-between align-items-center pb-20 mb-20">
			<a href="<?php echo get_permalink() . '?create_employee=true' ?>" class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3">
                <span class="py-sm-1 d-block">
                    <i class="ri-add-line text-white"></i>
                    <span><?php esc_html_e('Add Employee', 'cm-hrm'); ?></span>
                </span>
			</a>
		</div>
		<div class="default-table-area mt-5">
			<div class="table-responsive">
				<table class="table align-middle" id="dataTable">
					<thead>
					<tr>
						<th scope="col"><?php esc_html_e('Name', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Phone', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Company', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Department', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Designation', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach( $employees as $employee ) : ?>
						<tr>
							<td><span><?php echo $employee->name ?></span></td>
							<td><span><?php echo $employee->phone ?></span></td>
							<td><span><?php echo Hrm_DbQuery::get_table_field_value('company', 'name', $employee->company_id) ?></span></td>
							<td><span><?php echo Hrm_DbQuery::get_table_field_value('departments', 'name', $employee->department_id) ?></span></td>
							<td><span><?php echo Hrm_DbQuery::get_table_field_value('designation', 'name', $employee->designation_id) ?></span></td>
							<td>
								<div class="action-option">
                                    <a class="me-3" href="<?php echo get_permalink() . '?edit_employee=true&employee_id='. $employee->id .'' ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="feather-20" data-feather="edit"></i>
                                    </a>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal<?php echo $employee->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="feather-20" data-feather="trash-2"></i>
                                    </a>
								</div>
							</td>
						</tr>

						<!-- Modal Delete -->
						<div class="modal fade" id="deleteEmployeeModal<?php echo $employee->id ?>" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Employee', 'cm-hrm');?></h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body text-center">
										<h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
									</div>
									<div class="modal-footer">
										<button onclick="deleteEmployee(<?php echo $employee->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
										<script>
                                            // Delete data
                                            function deleteEmployee(employeeId) {
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: localize._ajax_url,
                                                    data: {
                                                        action: 'delete_employee_ajax_action',
                                                        _ajax_nonce: localize._ajax_nonce,
                                                        employeeId,
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