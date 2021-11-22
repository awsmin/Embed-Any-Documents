<?php
/**
 * Embed Any Document Settings Template.
 *
 * @package embed-any-document
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<div class="ead-title-wrap">
		<h1><?php esc_html_e( 'Embed Any Document by AWSM.in', 'embed-any-document' ); ?></h1>
	</div>
	<?php
	/**
	 * Customize the settings tabs.
	 * @param array $settings_tabs Settings tabs.
	 */
	$settings_tabs = apply_filters( 'awsm_ead_settings_tabs', array( 'general') );
	
	$settings_tab = isset( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : 'general';
	if ( ! in_array( $settings_tab, $settings_tabs, true ) ) {
		$settings_tab = 'general';
	}
	?>
	<div class="nav-tab-wrapper">
		<a class="nav-tab <?php echo esc_attr( $this->getactive_menu( $settings_tab, 'general' ) ); ?>" href="<?php echo esc_url( 'options-general.php?page=' . $this->settings_slug . '&tab=general' ); ?>" data-tab="general">
			<?php esc_html_e( 'General Settings', 'embed-any-document' ); ?>
		</a>

		<?php if ( in_array( 'cloud', $settings_tabs, true ) ) { ?>
			<a class="nav-tab <?php echo esc_attr( $this->getactive_menu( $settings_tab, 'cloud' ) ); ?>" href="<?php echo esc_url( 'options-general.php?page=' . $this->settings_slug . '&tab=cloud' ); ?>" data-tab="ead-cloud">
				<?php esc_html_e( 'Cloud Settings', 'embed-any-document' ); ?>
			</a>
		<?php } ?>

	</div>
	<div class="row clearfix">
		<div class="ead-left-wrap">
			<div class="ead-tabs">
				<?php if ( $settings_tab === 'general' ) : ?>
				<form method="post" action="options.php">
							<?php settings_fields( 'ead-settings-group' ); ?>
							<table class="form-table">
								<?php
									/**
									 * Hook: before_awsm_ead_general_settings.
									 *
									 * @since 3.0.0
									 */
									do_action( 'before_awsm_ead_general_settings' );
								?>
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Default Size', 'embed-any-document' ); ?></th>
									<td>
										<div class="f-left ead-frame-width"><?php esc_html_e( 'Width', 'embed-any-document' ); ?>
											<input type="text" class="small" name="ead_width" value="<?php echo esc_attr( get_option( 'ead_width', '100%' ) ); ?>"/>
										</div>
										<div class="f-left ead-frame-height">
											<?php esc_html_e( 'Height', 'embed-any-document' ); ?>
											<input type="text" class="small" name="ead_height" value="<?php echo esc_attr( get_option( 'ead_height', '100%' ) ); ?>"/>
										</div>
										<div class="clear"></div>
										<span class="note"><?php esc_html_e( 'Enter values in pixels or percentage (Example: 500px or 100%)', 'embed-any-document' ); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Show Download Link', 'embed-any-document' ); ?></th>
									<td>
										<?php
										$downoptions = array(
											'alluser' => __( 'For all users', 'embed-any-document' ),
											'logged'  => __( 'For Logged-in users', 'embed-any-document' ),
											'none'    => __( 'No Download', 'embed-any-document' ),
										);
										$this->selectbuilder( 'ead_download', $downoptions, esc_attr( get_option( 'ead_download', 'none' ) ) );
										?>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Download Text', 'embed-any-document' ); ?></th>
									<td>
										<input type="text" class="" name="ead_text" value="<?php echo esc_attr( get_option( 'ead_text', 'Download' ) ); ?>"/>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										<?php esc_html_e( 'Preloader for Viewer', 'embed-any-document' ); ?>
									</th>
									<td>
										<?php
											$preloader = get_option( 'ead_preloader', 'file' );
										?>
										<input type="checkbox" name="ead_preloader" id="ead_preloader_view" value="1" <?php checked(1, get_option('ead_preloader'), true); ?>><?php esc_html_e( 'Enable Preloader', 'embed-any-document' ); ?>
										
										<div class="clear"></div>
									    <span class="note"><?php esc_html_e( 'Check for enabling preloader', 'embed-any-document' ); ?></span>
									</td>
								</tr>
								<?php
									/**
									 * Hook: after_awsm_ead_general_settings.
									 *
									 * @since 3.0.0
									 */
									do_action( 'after_awsm_ead_general_settings' );
								?>
							</table>
							<div class="ead-form-footer">
								<?php submit_button(); ?>
							</div>
						</form>
				<?php endif; ?>
				<?php if ( $settings_tab === 'cloud' ) : ?>
				<form method="post" action="options.php">
					<?php settings_fields( 'ead-cloud-group' ); ?>
					<div class="form-table">
						<ul class="cloudform">
				<?php
				/**
					 * Hook: awsm_ead_cloud_settings..
					 *
					 * @since 3.0.0
					 */
					do_action( 'awsm_ead_cloud_settings' );
				?>
						</ul>
					</div>
					<div>
						<?php submit_button(); ?>
					</div>
				</form>
				<?php endif; ?>
			</div>
		</div>
		
	</div>
	<!-- .row -->
</div>
<!-- .wrap -->
