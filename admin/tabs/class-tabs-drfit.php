<?php
            
class DrFit_Tabs {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {

        /** Top Menu **/
        add_menu_page( __( 'Dr. Fit Payment System', 'drfitpay' ), __(  'Dr. Fit Payment System', 'drfitpay' ), 'manage_options', 'drfit-tabs', array( $this, 'plugin_page' ), 'dashicons-cart', null );
    }

    public function plugin_page_new() {
        $template = dirname( __FILE__ ) . '/views/tabs-new.php';
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
	
    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'list';
        $id     = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        switch ($action) {
            case 'view':

                $template = dirname( __FILE__ ) . '/views/tabs-single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/tabs-edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/tabs-new.php';
                break;

            case 'readonly':
                $template = dirname( __FILE__ ) . '/views/tabs-readonly.php';
                break;
			
            case 'delete':
                $ids=isset( $_REQUEST['ids'] ) ? $_REQUEST['ids'] : null;
                if(!empty($ids)){
                    foreach ($ids as $key => $id) {
                        scc_delete_tabs_by_id($id);
                    }
                }else if(!empty($id)){
                    scc_delete_tabs_by_id($id);
                }
            default:
                $template = dirname( __FILE__ ) . '/views/tabs-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}

new DrFit_Tabs();