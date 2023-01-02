<?php
function pmwi_admin_footer(){
    // Disable admin meta boxes script in WP All Import pages.
    if (!empty($_GET['page']) && ($_GET['page'] == 'pmxi-admin-manage' || $_GET['page'] == 'pmxi-admin-import')) {
        wp_dequeue_script('wc-admin-meta-boxes');
    }
}