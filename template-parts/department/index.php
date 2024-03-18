<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) Hrm_Utils::redirect_to_login_page();


if (isset( $_GET['edit_department'], $_GET['department_id'] ) && $_GET['edit_department'] == 'true' ) {
	get_template_part( 'template-parts/department/edit', null,
		array(
			'class' => 'department-edit-class',
			'data'  => [ 'department_id' => $_GET['department_id'] ]
		)
	);
} else {
	//Handle the department main page
    get_template_part('template-parts/department/main');
}



