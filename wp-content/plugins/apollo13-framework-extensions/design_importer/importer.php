<?php


add_action( 'wp_ajax_apollo13framework_import_demo_data', 'a13fe_import_demo_data' );
/**
 * Function leading demo data import process
 * @since  1.4.0
 */
function a13fe_import_demo_data() {
	/* in case there is old version of theme which had this feature inside, we don't add it */
	if ( function_exists( 'apollo13framework_import_demo_data' ) ){
		return;
	}

	global $apollo13framework_a13;
	//check if we got license key
	if( ! $apollo13framework_a13->check_is_import_allowed() ){
		$msg    = esc_html__( 'Import stopped - there is no valid Purchase/License Code', 'a13_framework_cpt' );
		$result = array(
			'log'           => $msg,
			'sublevel_name' => '',
			'level_name'    => $msg,
			'is_it_end'     => true
		);

		//send AJAX response
		echo json_encode( sizeof( $result ) ? $result : false );
		die(); //this is required to return a proper result
	}
	/** @noinspection PhpIncludeInspection */
	$file_system_check = require_once A13FE_BASE_DIR . 'design_importer/actions.php';

	//error on file system access
	if( ! $file_system_check ){
		$result = array(
			'level'         => '',
			'level_name'    => esc_html__( 'Import Failed', 'a13_framework_cpt' ),
			'sublevel'      => '',
			'sublevel_name' => '',
			'log'           => esc_html__( 'Can not access File system!', 'a13_framework_cpt' ),
			'is_it_end'     => true,
			'alert'         => true
		);
	}
	else{
		$level         = isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : '';
		$sublevel      = isset( $_POST['sublevel'] ) ? sanitize_text_field( wp_unslash( $_POST['sublevel'] ) ) : '';
		$sublevel_name = '';
		$log           = '';
		$array_index   = 0;
		$alert         = false;

		$chosen_options = isset( $_POST['import_options'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['import_options'] ) ) : array();

		$levels = array(
			'_'                     => '', //empty to avoid bonus logic
			'start'                 => esc_html__( 'Starting import', 'a13_framework_cpt' ),
			'download_files'        => esc_html__( 'Downloading files', 'a13_framework_cpt' ),
			'clear_content'         => esc_html__( 'Removing content', 'a13_framework_cpt' ),
			'install_plugins'       => esc_html__( 'Installing plugins', 'a13_framework_cpt' ),
			'install_content'       => esc_html__( 'Importing content', 'a13_framework_cpt' ),
			'install_revo_sliders'  => esc_html__( 'Importing Revolution Slider', 'a13_framework_cpt' ),
			'setup_plugins_configs' => esc_html__( 'Setting up various plugins settings', 'a13_framework_cpt' ),
			'setup_wc'              => esc_html__( 'Setting up Woocommerce settings', 'a13_framework_cpt' ),
			'setup_fp'              => esc_html__( 'Setting up Front Page', 'a13_framework_cpt' ),
			'setup_menus'           => esc_html__( 'Setting menus to proper locations', 'a13_framework_cpt' ),
			'setup_widgets'         => esc_html__( 'Setting up widgets', 'a13_framework_cpt' ),
			'setup_permalinks'      => esc_html__( 'Setting up permalinks', 'a13_framework_cpt' ),
			'import_predefined_set' => esc_html__( 'Importing settings', 'a13_framework_cpt' ),
			'generate_custom_style' => esc_html__( 'Generate styles', 'a13_framework_cpt' ),
			'install_plugins_2'     => esc_html__( 'Installing plugins', 'a13_framework_cpt' ),
			'clean'                 => esc_html__( 'cleaning...', 'a13_framework_cpt' ),
			'end'                   => esc_html__( 'Everything done!', 'a13_framework_cpt' ),
		);

		//check what options are selected
		if( ! isset( $chosen_options['download_files'] ) ){
			unset( $levels['download_files'] );
		}
		if( ! isset( $chosen_options['clear_content'] ) ){
			unset( $levels['clear_content'] );
		}
		if( ! isset( $chosen_options['install_plugins'] ) ){
			unset( $levels['install_plugins'] );
			unset( $levels['setup_plugins_configs'] );
			unset( $levels['setup_wc'] );
			unset( $levels['install_plugins_2'] );
		}
		if( ! isset( $chosen_options['import_shop'] ) ){
			unset( $levels['setup_wc'] );
			unset( $levels['install_plugins_2'] );
		}
		if( ! isset( $chosen_options['install_content'] ) ){
			unset( $levels['install_content'] );
		}
		if( ! isset( $chosen_options['install_revo_sliders'] ) ){
			unset( $levels['install_revo_sliders'] );
		}
		if( ! isset( $chosen_options['install_site_settings'] ) ){
			unset( $levels['setup_fp'] );
			unset( $levels['setup_menus'] );
			unset( $levels['setup_widgets'] );
			unset( $levels['setup_permalinks'] );
		}
		if( ! isset( $chosen_options['install_theme_settings'] ) ){
			unset( $levels['import_predefined_set'] );
			unset( $levels['generate_custom_style'] );
		}
		if( ! isset( $chosen_options['clean'] ) ){
			unset( $levels['clean'] );
		}

		//get current level key
		if( strlen( $level ) === 0 ){
			//get first level to process
			$level = key( $levels );
		}
		else{
			//move array pointer to current importing level
			while( key( $levels ) !== $level ) {
				//and ask for next one
				next( $levels );
				$array_index ++;
			}
			//save new current level
			$level = key( $levels );
		}

		//Execute current level function
		$function = 'a13fe_demo_data_' . $level;
		if( function_exists( $function ) ){
			//no notices or other "echos", we put it in $log
			ob_start();

			$functions_with_1_param = array(
				'a13fe_demo_data_import_predefined_set',
				'a13fe_demo_data_start',
				'a13fe_demo_data_clean',
				'a13fe_demo_data_install_revo_sliders'
			);

			$demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['demo_id'] ) ) : '';
			//how many params should function receive
			if( in_array( $function, $functions_with_1_param ) ){
				$sublevel = $function( $demo_id );
			}
			else{
				$sublevel = $function( $sublevel, $sublevel_name, $demo_id, $chosen_options );
			}

			//collect all produced output to log
			$log = ob_get_contents();
			ob_end_clean();

			//should we move to next level
			if( $sublevel === true ){
				$sublevel = ''; //reset
				next( $levels );
				$level = key( $levels );
			}
		}
		//no function - move to next level. Some steps are just information without action
		else{
			next( $levels );
			$array_index ++;
			$level = key( $levels );
		}

		//check if this is last element
		$is_it_end = false;
		end( $levels );
		if( key( $levels ) === $level ){
			$is_it_end = true;
		}

		//prepare progress info
		$progress = round( 100 * ( 1 + $array_index ) / count( $levels ) );

		//special case - demo import files download failure
		$failure_codes = array(
			620,    // invalid purchase code
			621,    // trying to get paid demo
			//		1012,   // no available servers
			1013    // server directory no writable
		);
		if( is_array( $sublevel ) && $sublevel['sublevel'] === false && in_array( $sublevel['response']['code'], $failure_codes ) ){
			$log       = $sublevel['response']['message'];
			$sublevel  = false;
			$is_it_end = true;
			$alert     = true;
		}

		$result = array(
			'level'         => $level,
			'level_name'    => $levels[ $level ],
			'sublevel'      => $sublevel,
			'sublevel_name' => $sublevel_name,
			'log'           => $log,
			'progress'      => $progress,
			'is_it_end'     => $is_it_end,
			'alert'         => $alert
		);
	}

	//send AJAX response
	echo json_encode( sizeof( $result ) ? $result : false );

	die(); //this is required to return a proper result
}


/**
 * Retrieves list of Designs to import
 *
 * @since  1.4.0
 * @return bool|array list of designs or false on error
 */
function a13fe_get_demo_list() {

	$demos_definition = array();

	$response = wp_remote_get( A13FRAMEWORK_IMPORT_SERVER . '/definitions/' . A13FRAMEWORK_TPL_SLUG . '_demos_definition.php', array('timeout' => 20) );
	if( !is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ){
		$demos_definition = json_decode( wp_remote_retrieve_body( $response ), true );
	}

	if(!isset($demos_definition['demos'])){
		return false;
	}

	return $demos_definition;
}


/**
 * Displays whole Designs import interface
 *
 * @since  1.4.0
 */
function a13fe_get_demo_importer_content() {
	global $apollo13framework_a13;
	$demos_definition = a13fe_get_demo_list();
	$demos            = $demos_definition['demos'];
	$demo_count       = $demos_definition === false ? 0 : count( $demos );
	$all_categories   = array();
	$available_demos  = array();

	$available_demos_number = 0;
	if($demos_definition !== false){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		foreach ( $demos as $demo ) {
			//check if demo is available for this configuration
			if( isset( $demo['must_have_plugins'] ) && is_array( $demo['must_have_plugins'] ) ){
				$not_available = false;
				foreach( $demo['must_have_plugins'] as $plugin ) {
					if( ! is_plugin_active( $plugin ) ){
						$not_available = true;
					}
				}
				if( $not_available ){
					//skip this demo
					continue;
				}
			}

			//count this demo
			$available_demos_number++;
			$available_demos[] = $demo;

			//collect categories
			$all_categories = array_merge( $all_categories, $demo['categories'] );
		}
	}
	?>




		<div class="demo_import_wrapper">
			<?php
			if($demos === false){
				$demos_list_url = A13FRAMEWORK_IMPORT_SERVER . '/definitions/' . A13FRAMEWORK_TPL_SLUG . '_demos_definition.php';
				echo '<p class="info_box">'.
				     sprintf(
						/* translators: %1$s: URL */
						esc_html__( 'There is problem with getting list of available demos for import. Either our server is down, and you should be able to import demo at later time, or your server has problem with reaching our server. Just in case ask your server admin if your server has any problems reaching this URL %1$s - usually server DNS issue or Firewall is blocking it.', 'a13_framework_cpt'),
						'<a target="_blank" href="'.esc_url( $demos_list_url ).'">'.esc_html( $demos_list_url ).'</a>'
				     ).
					'</p>';
			}

			//we have some demos
			if($demo_count){
				?>

			<div id="a13-import-step-1" data-step="1" class="import-step step-1">
				<h2><?php echo esc_html__( 'Designs to choose from:', 'a13_framework_cpt' ); ?> <strong><?php echo intval($available_demos_number); ?></strong></h2>
				<p class="center"><?php echo esc_html__( 'Please select design for import to move to next step.', 'a13_framework_cpt' ); ?></p>

				<?php if($demo_count > 1){ ?>
					<span class="demo_search_wrap">
					<label><i class="fa fa-search"></i><?php echo esc_html__( 'Search designs', 'a13_framework_cpt' ); ?>
						<input class="demo_search" type="search" value="" name="demo_search" placeholder="<?php esc_attr_e( 'At least 3 chars: name, category', 'a13_framework_cpt' ); ?>" />
					</label>
					</span>

					<div class="filter_wrapper">
						<?php
						//categories
						if(count($all_categories) > 1){
							$all_categories_unique = array_unique( $all_categories );
							sort( $all_categories_unique );

							echo '<ul class="demo_filter_categories">';
							echo '<li data-filter="*" class="active"> ' . esc_html__( 'All', 'a13_framework_cpt' ) . ' </li>';
							foreach ( $all_categories_unique as $category ) {
								echo '<li data-filter="' . esc_attr( str_replace( ' ', '_', strtolower( $category ) ) ) . '"> ' . esc_html( $category ). ' </li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				<?php }
				do_action('apollo13framework_before_designs_list');
				?>

				<div id="a13_demo_grid" class="demo_grid">
				<?php
				foreach ( $available_demos as $demo ) {
					//check for setting telling proper path to thumbnails
					if(isset( $demos_definition['settings'] ) && isset($demos_definition['settings']['files_path'])  ){
						$files_directory = A13FRAMEWORK_IMPORT_SERVER . '/files/'.$demos_definition['settings']['files_path'].'/demo_data/' . $demo['id'] . '/';
					}
					else{
						$files_directory = A13FRAMEWORK_IMPORT_SERVER . '/files/' . A13FRAMEWORK_TPL_SLUG . '/demo_data/' . $demo['id'] . '/';
					}

					apollo13framework_importer_grid_item( $files_directory, $demo );
				}
				?>
				</div>

			<?php
				do_action('apollo13framework_after_designs_list');
			?>
			</div>

			<div id="a13-import-step-2" data-step="2" class="import-step step-2 hidden">
				<h2><?php echo esc_html__( 'About Design Importer', 'a13_framework_cpt' ); ?></h2>
				<?php
				echo '<p>'.
				     esc_html__( 'This importer can be used to import whole demo look &amp; content to your site. Use below configuration and designs to achieve desired results.', 'a13_framework_cpt').
				     ' <a href="'.esc_url( $apollo13framework_a13->get_docs_link('importer-configuration') ).'">'.esc_html__( 'Read more about using Design Importer.', 'a13_framework_cpt').'</a>'.
				     '</p>';
				echo '<p>'.
				     esc_html__( 'While using Design Importer feature some data will be stored on our server. These are: Date of action, your site URL, IP address, imported Design name. All these data is used for statistic and for protection against abuse of our services. These data are not shared with any third party.', 'a13_framework_cpt').
				     '</p>';
				?>

				<h2><?php echo esc_html__( 'Configuration &amp; Requirements', 'a13_framework_cpt' ); ?></h2>

				<div class="config-tables clearfix">
					<?php
					a13fe_theme_import_configuration();
					apollo13framework_theme_requirements_table();
					?>
				</div>

				<div class="import-navigation">
					<button class="button previous-step"><?php echo esc_html__( 'Previous step', 'a13_framework_cpt' ); ?></button>
					<button class="button button-primary button-hero next-step"><?php echo esc_html__( 'Next step', 'a13_framework_cpt' ); ?></button>
				</div>
			</div>

			<div id="a13-import-step-3" data-step="3" class="import-step step-3 hidden">
				<h2><?php echo esc_html__( 'You are about to import:', 'a13_framework_cpt' ); ?></h2>
				<div class="import-summary">
					<h3 class="design-name"></h3>
					<img src="" alt="<?php echo esc_attr( __( 'Design Preview', 'a13_framework_cpt' ) ); ?>" />
				</div>

				<div class="status_info">
					<strong id="demo_data_import_status"><?php esc_html_e( 'The Importer is ready to start.', 'a13_framework_cpt' ); ?></strong>
					<a id="a13_import_demo_data_log_link" href="#"><?php esc_html_e( 'Show/hide log', 'a13_framework_cpt' ); ?></a>
				</div>

				<div class="import_progress_bar">
					<div class="import_progress"></div>
				</div>

				<div id="demo_data_import_log">
					<p class="info"><?php esc_html_e( 'This log is only usable for developers, so please don\'t interpret it on your own. Most of the notices displayed here have nothing to do with problems that you might encounter while importing demo data.', 'a13_framework_cpt' ); ?></p>
					<div></div>
				</div>

				<div class="import-navigation">
					<button class="button previous-step"><?php echo esc_html__( 'Previous step', 'a13_framework_cpt' ); ?></button>
					<button class="button button-primary button-hero" id="start-demo-import" data-confirm="<?php echo esc_attr( __( 'Do you want to import selected Design?', 'a13_framework_cpt' ) ); ?>" data-confirm-remove-content="<?php echo esc_attr( __( 'All your current content will be removed prior to import!', 'a13_framework_cpt' ) ); ?>"><?php echo esc_html__( 'Start Design Import', 'a13_framework_cpt' ); ?></button>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	<?php
}
add_action('apollo13framework_apollo13_importer_page_content', 'a13fe_get_demo_importer_content');



/**
 * Displays Designs import configuration
 *
 * @since  1.4.0
 */
function a13fe_theme_import_configuration(){
	global $apollo13framework_a13;
	?>
	<div class="import-config">
		<table class="status_table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3"><?php esc_html_e( 'Import configuration', 'a13_framework_cpt' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><label for="import-clear-content"><strong style="color: #ca2121;"><?php esc_html_e( 'Remove current content', 'a13_framework_cpt' ); ?>:</strong></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( '<p>In order to achieve import results as close as possible to original demo, the importer will have to remove all your current content.</p><p>If you are on fresh WordPress install and you want to get best import results you should check this option.</p><p>If however you are just updating your existing site, then stay away from this option:-)</p>', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="clear_content" id="import-clear-content" /><label for="import-clear-content"><?php esc_html_e( 'Caution!', 'a13_framework_cpt' ); ?></label></td>
			</tr>

			<tr>
				<td><label for="import-install-plugins"><?php esc_html_e( 'Install plugins', 'a13_framework_cpt' ); ?>:</label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( '<p>It will install plugins that are needed to reproduce selected demo.</p><p>Not installing plugins may prohibit some content from importing.</p>', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install_plugins" id="import-install-plugins" value="off" checked /></td>
			</tr>
			<tr>
				<td><label for="import-install-shop"><?php esc_html_e( 'Import shop', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( '<p>If you plan to use shop on your page then check this option. It will install WooCommerce plugin and settings for it(if used in selected demo).</p><p>Leaving this option not checked will make your site faster while import and after it, as it will need much less memory - each active plugin makes your site slower.</p>', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="import_shop" id="import-install-shop" /></td>
			</tr>

			<tr>
				<td><label for="import-install-content"><?php esc_html_e( 'Import demo content', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( 'It installs all the content created for the selected demo. These are pages, posts, works, albums and content from other "post types".', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install_content" id="import-install-content" checked />
				<?php if ( !apollo13framework_is_home_server() ){ ?>
					<input class="hidden" type="checkbox" name="install-attachments" id="import-install-attachments" checked />
				<?php } ?>
				</td>
			</tr>
			<?php if ( apollo13framework_is_home_server() ){ ?>
			<tr>
				<td><label for="import-install-attachments"><?php esc_html_e( 'Import media attachments', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( 'It import images from our demo content.', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install-attachments" id="import-install-attachments" checked /></td>
			</tr>
			<?php } ?>

			<?php if ( $apollo13framework_a13->check_for_valid_license() ){ ?>
			<tr>
				<td><label for="import-install-sliders"><?php esc_html_e( 'Import sliders', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( 'Imports sliders created with "Slider Revolution" plugin that is used in demo content.', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install_revo_sliders" id="import-install-sliders" checked /></td>
			</tr>
			<?php } ?>

			<tr>
				<td><label for="import-site-settings"><?php esc_html_e( 'Import site settings', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( '<p>Site settings are various setting mostly not dependent on a theme you use. These are: permalinks and front page.</p><p>Partly theme dependent settings are menus and sidebars with widgets.</p>', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install_site_settings" id="import-site-settings" checked /></td>
			</tr>

			<tr>
				<td><label for="import-theme-settings"><?php esc_html_e( 'Import theme settings', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( '<p>The theme settings are all settings that you can later change on in Customizer.</p><p>If you wish only to change the look of your existing site to one from our demos, then mark only this option.</p>', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="install_theme_settings" id="import-theme-settings" checked /></td>
			</tr>

			<tr class="readonly">
				<td><label for="import-cleanup"><?php esc_html_e( 'Clean up after import', 'a13_framework_cpt' ); ?></label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( 'It deletes all downloaded demo data files, and clean up some entries in the database that were used for the import process.', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="clean" id="import-cleanup" checked /></td>
			</tr>

			<tr class="readonly">
				<td><label for="import-download"><?php esc_html_e( 'Download demo files', 'a13_framework_cpt' ); ?>:</label></td>
				<td class="help"><?php apollo13framework_input_help_tip( __( 'The importer will download demo data files that are needed to start the import process.', 'a13_framework_cpt' ) ); ?></td>
				<td><input type="checkbox" name="download_files" id="import-download" checked /></td>
			</tr>
			</tbody>
		</table>
	</div>

	<?php
}


//prepare directory for demo data
if ( !is_writable( A13FRAMEWORK_IMPORTER_TMP_DIR ) ) {
	wp_mkdir_p(A13FRAMEWORK_IMPORTER_TMP_DIR);
}