<?php
defined( 'ABSPATH' ) || exit;

if ( Hrm_Utils::current_user_role() !== 'administrator' ) Hrm_Utils::redirect_to_login_page();

//Handle the dashboard main page
get_template_part('template-parts/dashboard/main');



