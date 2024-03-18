<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) Hrm_Utils::redirect_to_login_page();


if ( isset( $_GET['edit_designation'], $_GET['designation_id'] ) && $_GET['edit_designation'] == 'true' ) {
	get_template_part( 'template-parts/designation/edit', null,
		array(
			'class' => 'designation-edit-class',
			'data'  => [ 'designation_id' => $_GET['designation_id'] ]
		)
	);
} else {
	//Handle the designation main page
    get_template_part('template-parts/designation/main');
}



