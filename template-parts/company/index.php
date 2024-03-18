<?php
defined( 'ABSPATH' ) || exit;

if ( Hrm_Utils::current_user_role() !== 'administrator' ) Hrm_Utils::redirect_to_login_page();


if ( isset( $_GET['edit_company'], $_GET['company_id'] ) && $_GET['edit_company'] == 'true' ) {
	get_template_part( 'template-parts/company/edit', null,
		array(
			'class' => 'company-edit-class',
			'data'  => [ 'company_id' => $_GET['company_id'] ]
		)
	);
} else {
	//Handle the company main page
    get_template_part('template-parts/company/main');
}



