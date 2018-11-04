<?php
add_action( 'apollo13framework_generate_user_css', 'a13fe_generate_user_css' );
/**
 * Get name of user CSS file
 *
 * @since  1.4.0
 *
 * @param bool|false $public if false it will return path for include,
 *                           if true it will return path for source(http://path.to.file)
 *
 * @return string path to file
 */
function a13fe_user_css_name($public = false) {
	$name = ($public ? A13FRAMEWORK_GENERATED_CSS : A13FRAMEWORK_GENERATED_CSS_DIR) . '/user'; /* user.css - comment just for easier searching */
	if (is_multisite()) {
		//add blog id to file
		$name .= '_' . get_current_blog_id();
	}

	return $name . '.css';
}


/**
 * Make user CSS file from theme layout options
 *
 * @since  1.4.0
 *
 * @param bool $hide_errors
 *
 */
function a13fe_generate_user_css( $hide_errors = true ) {
	$save_result = 1;

	if($hide_errors){
		ob_start();
	}

	//prepare file system

	//just in case have these files included
	require_once(ABSPATH . '/wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/template.php');

	//we are checking if file system can operate without FTP creds
	$url = wp_nonce_url(admin_url(),'');
	if( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) ){
		$save_result = 0;
	}
	elseif ( ! WP_Filesystem($creds) ) {
		request_filesystem_credentials($url, '', true, false, null);
		$save_result = 0;
	}

	//if we have good FTP creds or system operates with "direct" method
	if( $save_result === 1 ){
		global $wp_filesystem;
		/* @var $wp_filesystem WP_Filesystem_Base */

		//make dir if it doesn't exist yet
		if ( ! is_dir( A13FRAMEWORK_GENERATED_CSS_DIR ) ) {
			wp_mkdir_p( A13FRAMEWORK_GENERATED_CSS_DIR );
		}

		if (is_writable(A13FRAMEWORK_GENERATED_CSS_DIR) ) {
			$file = a13fe_user_css_name();

			//in case of FTP access we need to make sure we have proper path
			$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);

			apollo13framework_enable_user_css_functions();
			$css = apollo13framework_get_user_css();
			$wp_filesystem->put_contents(
				$file,
				$css,
				FS_CHMOD_FILE
			);

			//remove any pending update request
			update_option('a13_user_css_update','off');
		}
	}
	//we couldn't save
	else{
		update_option('a13_user_css_update','on');
	}

	if($hide_errors){
		ob_end_clean();
	}
}

if( is_admin() ){
	add_action( 'after_setup_theme', 'a13fe_check_user_css' );
}

/**
 * If file system is not in direct mode, we need to ask for FTP creds to create user.css file
 *
 * @since  1.4.0
 */
function a13fe_check_user_css(){
	if(get_option('a13_user_css_update') === 'on'){
		add_action( 'admin_notices', 'a13fe_notice_about_user_css', 0 );
	}
}

/**
 * Displays FTP form in case of wrong permission or ownership to user directory
 *
 * @since  1.4.0
 */
function a13fe_notice_about_user_css(){
	echo '<div class="notice-warning notice">';
	echo '<p>'.
	     sprintf(
	     /* translators: %s: user CSS file name */
		     esc_html__( 'Creating %s file requires access to your FTP account. It is caused by the way your server is configured. This file is required so changes done in theme settings can take effect.', 'a13_framework_cpt' ),
		     '<strong>'.esc_html( a13fe_user_css_name() ).'</strong>'
	     ).
	     '</p>';
	echo '<p>'.
	     sprintf(
	     /* translators: %1$s: directory path, %2$s: directory path */
		     esc_html__( 'Changing permissions or ownership to directory: %1$s and/or %2$s should fix the issue for need of your FTP credentials.', 'a13_framework_cpt' ),
		     '<strong>'.esc_html( A13FRAMEWORK_GENERATED_CSS_DIR ).'</strong>'
	     ).
	     '</p>';

	//this will cause form from WP_Filesystem to display
	do_action( 'apollo13framework_generate_user_css', false );
	echo '</div>';
}