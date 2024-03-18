<?php
defined( 'ABSPATH' ) || exit;

if ( Hrm_Utils::current_user_role() !== 'administrator' ) Hrm_Utils::redirect_to_login_page();


if ( isset( $_GET['edit_client'], $_GET['client_id'] ) && $_GET['edit_client'] == 'true' ) {
	get_template_part( 'template-parts/client/edit', null,
		array(
			'class' => 'client-edit-class',
			'data'  => [ 'client_id' => $_GET['client_id'] ]
		)
	);
} else {
	//Handle the client main page
    get_template_part('template-parts/client/main');
}



