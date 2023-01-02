<?php

if ($active_plugins = get_option('active_plugins', array())) {

  foreach ($active_plugins as $key => $active_plugin) {

    if (strstr($active_plugin, '/whatsapp-chat.php')) {
      $active_plugins[$key] = str_replace('/whatsapp-chat.php', '/wp-whatsapp-chat.php', $active_plugin);
    }

    if (strstr($active_plugin, '/ql-whatsapp-chat.php')) {
      $active_plugins[$key] = str_replace('/ql-whatsapp-chat.php', '/wp-whatsapp-chat.php', $active_plugin);
    }
  }

  update_option('active_plugins', $active_plugins);
}