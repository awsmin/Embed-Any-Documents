<?php
if ( ! function_exists( 'ead_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ead_fs() {
        global $ead_fs;

        if ( ! isset( $ead_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $fs_secret_key = defined( 'AWSM_EAD_FS_SECRET_KEY' ) ? AWSM_EAD_FS_SECRET_KEY : '';

            $ead_fs = fs_dynamic_init( array(
                'id'                  => '9010',
                'slug'                => 'embed-any-document',
                'type'                => 'plugin',
                'public_key'          => 'pk_557fd46aa5f60c85d33e9555568c3',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
                'navigation'          => 'tabs',
                'menu'                => array(
                    'slug'           => 'ead-settings',
                    'first-path'     => 'options-general.php?page=ead-settings',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                    'parent'         => array(
                        'slug' => 'options-general.php',
                    ),
                ),
                // Development only.
                'secret_key'       => $fs_secret_key,
            ) );
        }

        return $ead_fs;
    }

    // Init Freemius.
    ead_fs();
    // Signal that SDK was initiated.
    do_action( 'ead_fs_loaded' );
}

?>