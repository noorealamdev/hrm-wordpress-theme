<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) Hrm_Utils::redirect_to_login_page();


if ( isset( $_GET['edit_project'], $_GET['project_id'] ) && $_GET['edit_project'] == 'true' ) {
	get_template_part( 'template-parts/project/edit', null,
		array(
			'class' => 'project-edit-class',
			'data'  => [ 'project_id' => $_GET['project_id'] ]
		)
	);
} elseif ( isset($_GET['create_project']) && $_GET['create_project'] == 'true' ) {
	get_template_part('template-parts/project/create');
}

else {
	//Handle the project main page
    get_template_part('template-parts/project/main');
}



