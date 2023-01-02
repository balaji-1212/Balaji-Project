<div class="wrap about-wrap full-width-layout qlwrap">

  <h1><?php echo esc_html(QLWAPP_PLUGIN_NAME); ?></h1>

  <p class="about-text"><?php printf(esc_html__('Thanks for using %s! We will do our best to offer you the best and improved communication experience with your users.', 'wp-whatsapp-chat'), QLWAPP_PLUGIN_NAME); ?></p>

  <p class="about-text">
    <?php printf('<a href="%s" target="_blank">%s</a>', QLWAPP_PURCHASE_URL, esc_html__('Purchase', 'wp-whatsapp-chat')); ?></a> |
    <?php printf('<a href="%s" target="_blank">%s</a>', QLWAPP_DEMO_URL, esc_html__('Demo', 'wp-whatsapp-chat')); ?></a> |
    <?php printf('<a href="%s" target="_blank">%s</a>', QLWAPP_DOCUMENTATION_URL, esc_html__('Documentation', 'wp-whatsapp-chat')); ?></a>
  </p>

  <?php printf('<a href="%s" target="_blank"><div style="
               background: #006bff url(%s) no-repeat;
               background-position: top center;
               background-size: 130px 130px;
               color: #fff;
               font-size: 14px;
               text-align: center;
               font-weight: 600;
               margin: 5px 0 0;
               padding-top: 120px;
               height: 40px;
               display: inline-block;
               width: 140px;
               " class="wp-badge">%s</div></a>', 'https://quadlayers.com/?utm_source=qlwapp_admin', plugins_url('/assets/backend/img/quadlayers.jpg', QLWAPP_PLUGIN_FILE), esc_html__('QuadLayers', 'wp-whatsapp-chat')); ?>

</div>
<?php
if (isset($submenu[QLWAPP_DOMAIN])) {
  if (is_array($submenu[QLWAPP_DOMAIN])) {
?>
    <div class="wrap about-wrap full-width-layout qlwrap">
      <h2 class="nav-tab-wrapper">
        <?php
        if(!isset($button)){
          $button_model = new QLWAPP_Button();
          $button = $button_model->get();
        }
        foreach ($submenu[QLWAPP_DOMAIN] as $tab) {
          $hide = '';
          if (strpos($tab[2], '.php') !== false)
            continue;
          if ($button['box'] == 'no' &&
              ('admin.php?page=' . $tab[2] == 'admin.php?page=qlwapp_box' ||
               'admin.php?page=' . $tab[2] == 'admin.php?page=qlwapp_contacts') ){
                $hide = 'hidden ';
               }
        ?>
          <a href="<?php echo admin_url('admin.php?page=' . esc_attr($tab[2])); ?>" class="<?php echo esc_attr($hide); echo esc_attr($tab[2]); ?> nav-tab<?php echo (isset($_GET['page']) && $_GET['page'] == $tab[2]) ? ' nav-tab-active' : ''; ?> "><?php echo wp_kses_post($tab[0]); ?></a>
        <?php

        }
        ?>
      </h2>
    </div>
<?php
  }
}
