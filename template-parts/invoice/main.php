<?php
defined( 'ABSPATH' ) || exit;

// Get invoices table data
$invoices = Hrm_DbQuery::get_table_data('invoices');
Hrm_Utils::get_transient_toast('payment_status_updated');

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	global $wpdb;
	// Nonce check
	if ( isset( $_POST['_wpnonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'change_payment_status_nonce' ) ) {
			wp_die( 'Are you cheating?' );
			// Anything that you want to display for unauthorized action
		}
	}

	// Get table
	$table_invoices = $wpdb->prefix . 'invoices';

	$invoice_id      = $_POST['invoice_id'];
	$payment_status  = $_POST['payment_status'];

	$data = [
		'payment_status' => $payment_status,
	];

	$where = [ 'id' => $invoice_id ];

	$update_row = $wpdb->update( $table_invoices, $data, $where );
	// if row updated in table
	if ( $update_row ) {
		Hrm_Utils::set_transient('payment_status_updated', 'Payment status has been updated successfully.');
		wp_redirect( get_permalink() );
		exit;

	} else {
		// Data has not updated
		Hrm_Utils::show_toastr('error', 'Something went wrong, please try again.');
	}
}
?>

<div class="card bg-white border-0 rounded-10 mb-4">
	<div class="card-body p-4">
		<div class="d-sm-flex text-center justify-content-between align-items-center pb-20 mb-20">
			<a href="<?php echo get_permalink() . '?create_invoice=true' ?>" class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3">
                <span class="py-sm-1 d-block">
                    <i class="ri-add-line text-white"></i>
                    <span><?php esc_html_e('Create Invoice', 'cm-hrm'); ?></span>
                </span>
			</a>
		</div>
		<div class="default-table-area mt-5">
			<div class="table-responsive">
				<table class="table align-middle" id="dataTable">
					<thead>
					<tr>
						<th scope="col"><?php esc_html_e('Invoice No', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Date', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Pay To', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Address', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Payment Status', 'cm-hrm');?></th>
						<th scope="col"><?php esc_html_e('Actions', 'cm-hrm');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach( $invoices as $invoice ) : ?>
						<tr>
							<td><span><?php echo 'Inv-' .$invoice->id ?></span></td>
							<td><span><?php echo $invoice->date ?></span></td>
							<td><span><?php echo $invoice->pay_name ?></span></td>
							<td><span><?php echo $invoice->pay_address ?></span></td>
							<td><a href="javascript:" data-bs-toggle="modal" data-bs-target="#statusInvoiceModal<?php echo $invoice->id ?>" class="badge <?php echo $invoice->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark'?>"><?php echo esc_html__(strtoupper($invoice->payment_status), 'cm-hrm') ?></a></td>
							<td>
								<div class="action-option">
                                    <a class="me-2" href="<?php echo get_permalink() . '?show_invoice=true&invoice_id='. $invoice->id .'' ?>" data-toggle="tooltip" data-placement="top" title="View">
                                        <i class="feather-20" data-feather="eye"></i>
                                    </a>
                                    <a href="javascript:" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal<?php echo $invoice->id ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="feather-20" data-feather="trash-2"></i>
                                    </a>
								</div>
							</td>
						</tr>

                        <!-- Modal Change Payment Status -->
                        <div class="modal fade" id="statusInvoiceModal<?php echo $invoice->id ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Change Payment Status', 'cm-hrm');?></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <form action="" id="editPaymentStatusForm" method="post">
	                                        <?php wp_nonce_field( 'change_payment_status_nonce' ); ?>
                                            <input id="invoice_id" name="invoice_id" type="hidden" value="<?php echo $invoice->id ?>">
                                            <div class="form-group mb-4">
                                                <label class="label"><?php esc_html_e( 'Payment Status', 'cm-hrm' ); ?></label>
                                                <div class="form-group position-relative">
                                                    <select id="payment_status" name="payment_status" class="form-select form-control">
                                                        <option value="unpaid" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value('invoices', 'payment_status', $invoice->id) == 'unpaid' ? 'selected' : '' ?>><?php esc_html_e( 'UnPaid', 'cm-hrm' ); ?></option>
                                                        <option value="paid" class="text-dark" <?php echo Hrm_DbQuery::get_table_field_value('invoices', 'payment_status', $invoice->id) == 'paid' ? 'selected' : '' ?>><?php esc_html_e( 'Paid', 'cm-hrm' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group d-flex mt-4">
                                                <input type="submit" name="submit" class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" value="<?php esc_attr_e( 'Update', 'cm-hrm' ); ?>">

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

						<!-- Modal Delete -->
						<div class="modal fade" id="deleteInvoiceModal<?php echo $invoice->id ?>" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Delete invoice', 'cm-hrm');?></h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body text-center">
										<h5><?php esc_html_e('Are you sure?', 'cm-hrm');?></h5>
									</div>
									<div class="modal-footer">
										<button onclick="deleteEmployee(<?php echo $invoice->id ?>)" type="button" class="btn btn-danger text-white"><?php esc_html_e('Delete', 'cm-hrm');?></button>
										<script>
                                            // Delete data
                                            function deleteEmployee(invoiceId) {
                                                jQuery.ajax({
                                                    type: 'POST',
                                                    url: localize._ajax_url,
                                                    data: {
                                                        action: 'delete_invoice_ajax_action',
                                                        _ajax_nonce: localize._ajax_nonce,
                                                        invoiceId,
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