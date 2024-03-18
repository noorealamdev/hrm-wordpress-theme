<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) Hrm_Utils::redirect_to_login_page();


if ( isset($_GET['create_invoice']) && $_GET['create_invoice'] == 'true' ) {
	get_template_part('template-parts/invoice/create');
} elseif ( isset( $_GET['show_invoice'], $_GET['invoice_id'] ) && $_GET['show_invoice'] == 'true' ) {
	get_template_part( 'template-parts/invoice/show', null,
		array(
			'class' => 'invoice-show-class',
			'data'  => [ 'invoice_id' => $_GET['invoice_id'] ]
		)
	);
}

else {
	//Handle the invoice main page
    get_template_part('template-parts/invoice/main');
}



