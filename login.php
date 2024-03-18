<?php
/*
 * Template Name: Login Template
*/

if ( is_user_logged_in() ) {
    wp_redirect( esc_url( home_url( '/' ) ) );
    exit();
}

// User login
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	$creds = array(
		'user_login'    => $_POST['user_login'],
		'user_password' => $_POST['user_password'],
		'remember'      => false
	);

	$user = wp_signon( $creds, false );

	if ( is_wp_error( $user ) ) {
		$error_message = $user->get_error_message();
		$alert_html  = '<div class="container text-center p-4">';
		$alert_html .= '<div class="validation_error_message alert alert-danger p-2" role="alert">';
		$alert_html .= '<div>' . $error_message . '</div>';
		$alert_html .= '</div>';
		$alert_html .= '</div>';
		echo $alert_html;
	}else{
		wp_clear_auth_cookie();
		wp_set_current_user ( $user->ID ); // Set the current user detail
		wp_set_auth_cookie  ( $user->ID ); // Set auth details in cookie
		wp_redirect( esc_url( home_url( '/' ) ) );
		exit();
	}
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/assets/css/bootstrap.min.css' ?>">
</head>
<body class="login-page">
    <div class="container-fluid login-page">
        <div class="d-flex flex-column px-0">

            <div class="w-25 m-auto py-5">
                <form id="loginform" action="" method="post">
                    <div class="text-center mb-2">
                        <h4 class="fs-3 mb-2">Log In</h4>
                    </div>
                    <div class="card bg-white border-0 rounded-10 mb-4">
                        <div class="card-body p-0">
                            <div class="form-group mb-4">
                                <label class="label">User Name or Email</label>
                                <input type="text" name="user_login" id="user_login" value="" class="form-control input h-58">
                            </div>
                            <div class="form-group mb-0">
                                <label class="label">Password</label>
                                <div class="form-group">
                                    <div class="password-wrapper position-relative">
                                        <input type="password" name="user_password" id="user_password" value="" class="form-control input h-58 text-dark" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex justify-content-between mb-4">
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="fs-16 text-primary text-decoration-none mt-2 mt-sm-0 d-block">
                            Forgot your password?
                        </a>
                    </div>
                    <input class="btn btn-primary fs-16 fw-semibold text-dark heading-fornt py-2 py-md-3 px-4 text-white w-100" type="submit" name="wp-submit" id="wp-submit" value="<?php echo esc_html__('Log in', 'cm-hrm'); ?>" />

                    <input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url( '/' ) ) ?>">
                </form>
            </div>

        </div>
    </div>
</body>
</html>