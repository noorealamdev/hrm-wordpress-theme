<?php
defined( 'ABSPATH' ) || exit;

$clients = Hrm_DbQuery::get_table_data('clients');

Hrm_Utils::get_transient_toast('client_updated');
?>

<div class="card bg-white border-0 rounded-10 mb-4">
	<div class="card-body p-4">
		<div class="d-sm-flex text-center justify-content-between align-items-center pb-20 mb-20">
			<button class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3"
			        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="py-sm-1 d-block">
                    <i class="ri-add-line text-white"></i>
                    <span><?php esc_html_e('Add Client', 'cm-hrm'); ?></span>
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
					<?php foreach( $clients as $client ) : ?>
						<tr>
							<td><span><?php echo $client->name ?></span></td>
							<td><span><?php echo $client->email ?></span></td>
							<td><span><?php echo $client->phone ?></span></td>
							<td><span><?php echo $client->country ?></span></td>
							<td>
								<div class="action-option">
                                    <a class="me-3" href="<?php echo get_permalink() . '?edit_client=true&client_id='. $client->id .'' ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="feather-20" data-feather="edit"></i>
                                    </a>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteClientModal<?php echo $client->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="feather-20" data-feather="trash-2"></i>
                                    </a>
								</div>
							</td>
						</tr>

						<!-- Modal Delete -->
						<div class="modal fade" id="deleteClientModal<?php echo $client->id ?>" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete Client', 'cm-hrm');?></h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body text-center">
										<h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
									</div>
									<div class="modal-footer">
										<input id=delete_company_id" type="hidden" value="<?php echo $client->id ?>">
										<button onclick="deleteClient(<?php echo $client->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
										<script>
                                            // Delete data
                                            function deleteClient(clientId) {
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: localize._ajax_url,
                                                    data: {
                                                        action: 'delete_client_ajax_action',
                                                        _ajax_nonce: localize._ajax_nonce,
                                                        clientId,
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
		<h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel"><?php esc_html_e('Create Client', 'cm-hrm'); ?></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body p-4">
		<div id="validation_error_message" class="validation_error_message"></div>
		<form id="createClientForm" method="post">
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Client Name', 'cm-hrm');?> <span style="color: red">*</span></label>
				<input id="name" type="text" name="name" class="form-control text-dark">
			</div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Email', 'cm-hrm');?></label>
				<input id="email" type="text" name="email" class="form-control text-dark">
			</div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Phone', 'cm-hrm');?></label>
				<input id="phone" type="text" name="phone" class="form-control text-dark">
			</div>
            <div class="form-group mb-4">
                <label class="label"><?php esc_html_e('Address', 'cm-hrm');?></label>
                <input id="address" type="text" name="address" class="form-control text-dark">
            </div>
            <div class="form-group mb-4">
                <label class="label"><?php esc_html_e('City', 'cm-hrm');?></label>
                <input id="city" type="text" name="city" class="form-control text-dark">
            </div>
            <div class="form-group mb-4">
                <label class="label"><?php esc_html_e('Zip', 'cm-hrm');?></label>
                <input id="zip" type="text" name="zip" class="form-control text-dark">
            </div>
			<div class="form-group mb-4">
				<label class="label"><?php esc_html_e('Country', 'cm-hrm');?></label>
				<input id="country" type="text" name="country" class="form-control text-dark">
			</div>
			<div class="form-group d-flex gap-3">
				<input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e('Create Client', 'cm-hrm');?>">

			</div>
		</form>
	</div>
</div>

<script>
    ( function( $ ) {

        // Insert data
        $('#createClientForm').submit(function(e) {
            e.preventDefault();
            $("#validation_error_message ul").remove();

            let name    = $('#name').val();
            let email   = $('#email').val();
            let phone   = $('#phone').val();
            let address = $('#address').val();
            let city    = $('#city').val();
            let zip     = $('#zip').val();
            let country = $('#country').val();

            $.ajax({
                type: 'POST',
                url: localize._ajax_url,
                data: {
                    action: 'create_client_ajax_action',
                    _ajax_nonce: localize._ajax_nonce,
                    name,
                    email,
                    phone,
                    address,
                    city,
                    zip,
                    country,
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
                        toastr.error('Form validation failed');
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

    })( jQuery );
</script>