<?php

use BitApps\WPValidator\Validator;

defined( 'ABSPATH' ) || exit;
?>


<!-- Add Project Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-10">
            <div class="card-body p-4">
                <div id="validation_error_message" class="validation_error_message"></div>
                <form id="createInvoiceForm" method="post">
                    <div class="row">
                        <input id="urlRef" type="hidden" value="<?php echo get_permalink() ?>">
                        <input id="date" name="date" type="hidden" value="<?php echo date('Y-m-d') ?>" class="form-control text-dark">

                        <div class="col-lg-6">
                            <h4 class="pb-2">Invoiced To:</h4>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Name', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="invoice_name" name="invoice_name" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Address', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="invoice_address" name="invoice_address" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Email', 'cm-hrm' ); ?></label>
                                <input id="invoice_email" name="invoice_email" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-4">
                                <label class="label"><?php esc_html_e( 'Payment Status', 'cm-hrm' ); ?></label>
                                <div class="form-group position-relative">
                                    <select id="payment_status" name="payment_status" class="form-select form-control">
                                        <option value="unpaid" class="text-dark"><?php esc_html_e( 'UnPaid', 'cm-hrm' ); ?></option>
                                        <option value="paid" class="text-dark"><?php esc_html_e( 'Paid', 'cm-hrm' ); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="pb-2">Pay To:</h4>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Name', 'cm-hrm' ); ?> <span
                                            style="color: red">*</span></label>
                                <input id="pay_name" name="pay_name" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Address', 'cm-hrm' ); ?> <span style="color: red">*</span></label>
                                <input id="pay_address" name="pay_address" type="text" class="form-control text-dark">
                            </div>
                            <div class="form-group mb-2">
                                <label class="label"><?php esc_html_e( 'Email', 'cm-hrm' ); ?></label>
                                <input id="pay_email" name="pay_email" type="text" class="form-control text-dark">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <h4 class="pb-2">Items:</h4>
                            <table class="table table-bordered" id="dynamic_field">
                                <tr>
                                    <td><input type="text" name="item_name[]" placeholder="Item Name" class="form-control name_list" /></td>
                                    <td><input type="text" name="item_desc[]" placeholder="Description" class="form-control name_list" /></td>
                                    <td><input type="text" name="item_qty[]" placeholder="Quantity" class="form-control name_list" /></td>
                                    <td><input type="text" name="item_price[]" placeholder="Price" class="form-control name_list" /></td>
                                    <td><button type="button" name="add_more" id="add_more" class="btn btn-success">Add More</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <input type="submit" name="submit"
                               class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3"
                               value="<?php esc_attr_e( 'Create Invoice', 'cm-hrm' ); ?>">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    (function ($) {

        // Insert data
        $('#createInvoiceForm').submit(function (e) {
            e.preventDefault();
            $("#validation_error_message ul").remove();
            let formData = new FormData( $("#createInvoiceForm")[0] );
            formData.append('action', 'create_invoice_ajax_action');
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

        let i = 1;
        $('#add_more').click(function(){
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="item_name[]" placeholder="Item Name" class="form-control name_list" /></td><td><input type="text" name="item_desc[]" placeholder="Description" class="form-control name_list" /></td><td><input type="text" name="item_qty[]" placeholder="Quantity" class="form-control name_list" /></td><td><input type="text" name="item_price[]" placeholder="Price" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
        });
        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id+'').remove();
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