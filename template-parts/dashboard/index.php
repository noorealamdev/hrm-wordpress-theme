<?php
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) Hrm_Utils::redirect_to_login_page();

//Handle the dashboard main page
get_template_part('template-parts/dashboard/main');



