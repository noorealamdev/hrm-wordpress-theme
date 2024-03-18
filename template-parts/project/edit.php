<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;

$project_id = $args['data']['project_id'];

if ( ! $project_id ) {
	return;
} else {
	// Project single table data
	$project = Hrm_DbQuery::get_table_single_row('projects', $project_id);
	?>

    <!-- Edit Project Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-white border-0 rounded-10">
                <div class="card-body p-4">
                    <div id="validation_error_message" class="validation_error_message"></div>
                    <form id="updateProjectForm" method="post">
                        <div class="row">
                            <input id="urlRef" type="hidden" value="<?php echo get_permalink() ?>">
                            <input id="project_id" name="project_id" type="hidden" value="<?php echo $project_id ?>">
                            <div class="col-lg-4">
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Project Title', 'cm-hrm' ); ?> <span
                                                style="color: red">*</span></label>
                                    <input id="title" name="title" type="text" value="<?php echo $project->title ?>" class="form-control text-dark">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Client', 'cm-hrm' ); ?> <span
                                                style="color: red">*</span></label>
                                    <div class="form-group position-relative">
                                        <select name="client_id" class="form-select form-control h-58">
											<?php
											$clients = Hrm_DbQuery::get_table_data( 'clients' );
											foreach ( $clients as $client ) { ?>
                                                <option value="<?php echo $client->id ?>" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'client_id', $project->id ) == $client->id ? 'selected' : '' ?>><?php echo $client->name ?></option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Summary', 'cm-hrm' ); ?></label>
                                    <input id="summary" name="summary" type="text" value="<?php echo $project->summary ?>" class="form-control text-dark">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Start Date', 'cm-hrm' ); ?></label>
                                    <div class="form-group position-relative">
                                        <input type="date" id="start_date" name="start_date" value="<?php echo $project->start_date ?>" class="form-control text-dark">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Company', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                    <div class="form-group position-relative">
                                        <select id="company_id" name="company_id" class="form-select form-control h-58">
											<?php
											$company = Hrm_DbQuery::get_table_data( 'company' );
											foreach ( $company as $company_item ) { ?>
                                                <option value="<?php echo $company_item->id ?>" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'company_id', $project->id ) == $company_item->id ? 'selected' : '' ?>><?php echo $company_item->name ?></option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Priority', 'cm-hrm' ); ?></label>
                                    <div class="form-group position-relative">
                                        <select id="priority" name="priority" class="form-select form-control">
                                            <option value="urgent" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'priority', $project->id ) == 'urgent' ? 'selected' : '' ?>><?php esc_html_e( 'Urgent', 'cm-hrm' ); ?></option>
                                            <option value="high" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'priority', $project->id ) == 'high' ? 'selected' : '' ?>><?php esc_html_e( 'High', 'cm-hrm' ); ?></option>
                                            <option value="medium" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'priority', $project->id ) == 'medium' ? 'selected' : '' ?>><?php esc_html_e( 'Medium', 'cm-hrm' ); ?></option>
                                            <option value="low" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'priority', $project->id ) == 'low' ? 'selected' : '' ?>><?php esc_html_e( 'Low', 'cm-hrm' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Finish Date', 'cm-hrm' ); ?></label>
                                    <div class="form-group position-relative">
                                        <input type="date" id="finish_date" name="finish_date" value="<?php echo $project->finish_date ?>" class="form-control text-dark">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Assigned Employees', 'cm-hrm' ); ?></label>
                                    <div class="form-group position-relative">
                                        <select id="employee_ids" name="employee_ids[]" class="select2 form-select form-control h-58" multiple="multiple">
											<?php
											$employees = Hrm_DbQuery::get_table_data( 'employees' );
											$employee_ids = explode(',', $project->employee_ids);
											foreach ( $employees as $employee ) { ?>
                                                <option value="<?php echo $employee->id ?>" class="text-dark" <?php echo in_array($employee->id, $employee_ids) ? 'selected' : '' ?>><?php echo $employee->name ?></option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Status', 'cm-hrm' ); ?></label>
                                    <div class="form-group position-relative">
                                        <select id="status" name="status" class="form-select form-control">
                                            <option value="not_started" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'status', $project->id ) == 'not_started' ? 'selected' : '' ?>><?php esc_html_e( 'Not Started', 'cm-hrm' ); ?></option>
                                            <option value="in_progress" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'status', $project->id ) == 'in_progress' ? 'selected' : '' ?>><?php esc_html_e( 'In Progress', 'cm-hrm' ); ?></option>
                                            <option value="on_hold" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'status', $project->id ) == 'on_hold' ? 'selected' : '' ?>><?php esc_html_e( 'On Hold', 'cm-hrm' ); ?></option>
                                            <option value="completed" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'status', $project->id ) == 'completed' ? 'selected' : '' ?>><?php esc_html_e( 'Completed', 'cm-hrm' ); ?></option>
                                            <option value="cancelled" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value( 'projects', 'status', $project->id ) == 'cancelled' ? 'selected' : '' ?>><?php esc_html_e( 'Cancelled', 'cm-hrm' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-4">
                                    <label class="label"><?php esc_html_e( 'Description', 'cm-hrm' ); ?></label>
                                    <textarea id="description" name="description" class="form-control text-dark" placeholder="Project description ... " rows="4"><?php echo $project->description ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex gap-3">
                            <input type="submit" name="submit"
                                   class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                                   value="<?php esc_attr_e( 'Update Project', 'cm-hrm' ); ?>">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        (function ($) {

            // Insert data
            $('#updateProjectForm').submit(function (e) {
                e.preventDefault();
                $("#validation_error_message ul").remove();
                let formData = new FormData( $("#updateProjectForm")[0] );
                formData.append('action', 'update_project_ajax_action');
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