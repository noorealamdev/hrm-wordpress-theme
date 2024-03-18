<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;
?>


<!-- Add Employee Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-10">
            <div class="card-body p-4">
                <div id="validation_error_message" class="validation_error_message"></div>
                <form id="createEmployeeForm" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <input id="urlRef" type="hidden" value="<?php echo get_permalink() ?>">
                        <div class="col-lg-4">
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Name', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="name" name="name" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Gender', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <div class="form-group position-relative">
                                    <select id="gender" name="gender" class="form-select form-control">
                                        <option value="male" class="text-dark"><?php esc_html_e( 'Male', 'cm-hrm' ); ?></option>
                                        <option value="female" class="text-dark"><?php esc_html_e( 'Female', 'cm-hrm' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Phone', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="phone" name="phone" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Email', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="email" name="email" type="text" class="form-control text-dark">
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
                                                    class="text-dark"><?php echo $company_item->name ?></option>
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
                                                    class="text-dark"><?php echo $department->name ?></option>
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
                                                    class="text-dark"><?php echo $designation->name ?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Date of Birth', 'cm-hrm' ); ?></label>
                                <div class="form-group position-relative">
                                    <input type="date" id="dob" name="dob" class="form-control text-dark">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Address', 'cm-hrm' ); ?></label>
                                <input id="address" name="address" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'City', 'cm-hrm' ); ?></label>
                                <input id="city" name="city" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Province', 'cm-hrm' ); ?></label>
                                <input id="province" name="province" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Zip Code', 'cm-hrm' ); ?></label>
                                <input id="zip" name="zip" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Country', 'cm-hrm' ); ?></label>
                                <input id="country" name="country" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Joining Date', 'cm-hrm' ); ?></label>
                                <div class="form-group position-relative">
                                    <input type="date" id="joining_date" name="joining_date"
                                           class="form-control text-dark">
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
                                <input id="skype" name="skype" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'WhatsApp', 'cm-hrm' ); ?></label>
                                <input id="whatsapp" name="whatsapp" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Facebook', 'cm-hrm' ); ?></label>
                                <input id="facebook" name="facebook" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'LinkedIn', 'cm-hrm' ); ?></label>
                                <input id="linkedin" name="linkedin" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Notes', 'cm-hrm' ); ?></label>
                                <textarea id="notes" name="notes" class="form-control text-dark"
                                          placeholder="Some notes ... " rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex gap-3">
                        <input type="submit" name="submit"
                               class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                               value="<?php esc_attr_e( 'Create Employee', 'cm-hrm' ); ?>">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    (function ($) {

        // Insert data
        $('#createEmployeeForm').submit(function (e) {
            e.preventDefault();
            $("#validation_error_message ul").remove();

            let photo = $('#photo')[0].files[0];
            let formData = new FormData( $("#createEmployeeForm")[0] );
            formData.append('photo', photo);
            formData.append('action', 'create_employee_ajax_action');
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