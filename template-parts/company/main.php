<?php
defined( 'ABSPATH' ) || exit;

$companies = Hrm_DbQuery::get_table_data('company');
Hrm_Utils::get_transient_toast('company_updated');
?>

<div class="card bg-white border-0 rounded-10 mb-4">
	<div class="card-body p-4">
		<div class="d-sm-flex text-center justify-content-between align-items-center pb-20 mb-20">
			<button class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3"
			        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="py-sm-1 d-block">
                    <i class="ri-add-line text-white"></i>
                    <span><?php esc_html_e('Add Company', 'cm-hrm'); ?></span>
                </span>
			</button>
		</div>
		<div class="default-table-area mt-5">
			<div class="table-responsive">
				<table class="table align-middle" id="dataTable">
					<thead>
					<tr>
						<th scope="col"><?php esc_html_e('Name', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Email', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Phone', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Country', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach( $companies as $company ) : ?>
						<tr>
							<td><span><?php echo $company->name ?></span></td>
							<td><span><?php echo $company->email ?></span></td>
							<td><span><?php echo $company->phone ?></span></td>
							<td><span><?php echo $company->country ?></span></td>
							<td>
								<div class="action-option">
                                    <a class="me-3" href="<?php echo get_permalink() . '?edit_company=true&company_id='. $company->id .'' ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="feather-20" data-feather="edit"></i>
                                    </a>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal<?php echo $company->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="feather-20" data-feather="trash-2"></i>
                                    </a>
								</div>
							</td>
						</tr>

						<!-- Modal Delete -->
						<div class="modal fade" id="deleteCompanyModal<?php echo $company->id ?>" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Company', 'cm-hrm');?></h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body text-center">
										<h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
									</div>
									<div class="modal-footer">
										<input id=delete_company_id" type="hidden" value="<?php echo $company->id ?>">
										<button onclick="deleteCompany(<?php echo $company->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
										<script>
                                            // Delete data
                                            function deleteCompany(companyId) {
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: localize._ajax_url,
                                                    data: {
                                                        action: 'delete_company_ajax_action',
                                                        _ajax_nonce: localize._ajax_nonce,
                                                        companyId,
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
		<h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel"><?php esc_html_e('Create Company', 'cm-hrm'); ?></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body p-4">
		<div id="validation_error_message" class="validation_error_message"></div>
		<form id="createCompanyForm" method="post">
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Company Name', 'cm-hrm');?> <span style="color: red">*</span></label>
				<input id="company_name" type="text" name="company_name" class="form-control text-dark">
			</div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Company Email', 'cm-hrm');?></label>
				<input id="company_email" type="text" name="company_email" class="form-control text-dark">
			</div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Company Phone', 'cm-hrm');?></label>
				<input id="company_phone" type="text" name="company_phone" class="form-control text-dark">
			</div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Company Country', 'cm-hrm');?></label>
				<input id="company_country" type="text" name="company_country" class="form-control text-dark">
			</div>
			<div class="form-group d-flex gap-3">
				<input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e('Create Company', 'cm-hrm');?>">

			</div>
		</form>
	</div>
</div>

<script>
    ( function( $ ) {

        // Insert data
        $('#createCompanyForm').submit(function(e) {
            e.preventDefault();
            $("#validation_error_message ul").remove();

            let companyName = $('#company_name').val();
            let companyEmail = $('#company_email').val();
            let companyPhone = $('#company_phone').val();
            let companyCountry = $('#company_country').val();
            $.ajax({
                type: 'POST',
                url: localize._ajax_url,
                data: {
                    action: 'create_company_ajax_action',
                    _ajax_nonce: localize._ajax_nonce,
                    companyName,
                    companyEmail,
                    companyPhone,
                    companyCountry,
                },
                success: (res) => {
                    //console.log(res);
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
                        //$("#createCompanyForm")[0].reset();
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

        // Edit data
        // $(`#editCompanyForm${ $('#edit_company_id').val() }`).submit(function(e) {
        //     e.preventDefault();
        //     $(".submitBtnEdit").attr("disabled", true);
        //     $(`#validation_error_message${ $('#company_id').val() } ul`).remove();
        //
        //     let companyId = $('#company_id').val();
        //     let companyName = $('#edit_company_name').val();
        //     let companyEmail = $('#edit_company_email').val();
        //     let companyPhone = $('#edit_company_phone').val();
        //     let companyCountry = $('#edit_company_country').val();
        //     $.ajax({
        //         type: 'POST',
        //         url: localize._ajax_url,
        //         data: {
        //             action: 'edit_company_ajax_action',
        //             _ajax_nonce: localize._ajax_nonce,
        //             companyId,
        //             companyName,
        //             companyEmail,
        //             companyPhone,
        //             companyCountry,
        //         },
        //         success: (res) => {
        //             //console.log(res);
        //             if (res.validationError === true) {
        //                 let html = '<ul>';
        //                 Object.keys(res.validationMessage).forEach(key => {
        //                     html += `<li class="error">${res.validationMessage[key][0]}</li>`;
        //                 });
        //                 html += '</ul>';
        //                 $(`#validation_error_message${ $('#company_id').val() }`).append(html);
        //                 $(".submitBtnEdit").attr("disabled", false);
        //             }
        //             else if (res.success === true) {
        //                 toastr.success(res.data);
        //                 setTimeout(function() {
        //                     window.location=window.location;
        //                 }, 3000);
        //             } else {
        //                 toastr.error(res.data);
        //                 $(".submitBtnEdit").attr("disabled", false);
        //             }
        //         },
        //         error: (err) => {
        //             console.log(err);
        //             $(".submitBtnEdit").attr("disabled", false);
        //         }
        //     });
        // });


        // With server validation
        // $('#createCompanyForm22222').submit(function(e) {
        //     e.preventDefault();
        //     $("#validation_error_message ul").remove();
        //
        //     let companyName = $('#company_name').val();
        //     let companyEmail = $('#company_email').val();
        //     let companyPhone = $('#company_phone').val();
        //     let companyCountry = $('#company_country').val();
        //     $.ajax({
        //         type: 'POST',
        //         url: localize._ajax_url,
        //         data: {
        //             action: 'create_company_ajax_action',
        //             _ajax_nonce: localize._ajax_nonce,
        //             companyName,
        //             companyEmail,
        //             companyPhone,
        //             companyCountry,
        //         },
        //         success: (res) => {
        //             console.log(res);
        //             if (res.validationError === true) {
        //                 let html = '<ul>';
        //                 Object.keys(res.validationMessage).forEach(key => {
        //                     html += `<li class="error">${res.validationMessage[key][0]}</li>`;
        //                 });
        //                 html += '</ul>';
        //                 $('#validation_error_message').append(html);
        //             }
        //             else if (res.success === true) {
        //                 toastr.success(res.data);
        //                 $("#createCompanyForm22222")[0].reset();
        //                 setTimeout(function() {
        //                     location.reload();
        //                 }, 3000);
        //             } else {
        //                 toastr.error(res.data);
        //             }
        //         },
        //         error: (err) => {
        //             console.log(err);
        //         }
        //     });
        // });

    })( jQuery );
</script>