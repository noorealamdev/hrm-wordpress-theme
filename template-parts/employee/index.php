<?php
defined( 'ABSPATH' ) || exit;

if ( Hrm_Utils::current_user_role() !== 'administrator' ) Hrm_Utils::redirect_to_login_page();


if ( isset( $_GET['edit_employee'], $_GET['employee_id'] ) && $_GET['edit_employee'] == 'true' ) {
	get_template_part( 'template-parts/employee/edit', null,
		array(
			'class' => 'employee-edit-class',
			'data'  => [ 'employee_id' => $_GET['employee_id'] ]
		)
	);
} elseif ( isset($_GET['create_employee']) && $_GET['create_employee'] == 'true' ) {
	get_template_part('template-parts/employee/create');
}

else {
	//Handle the employee main page
    get_template_part('template-parts/employee/main');
}



