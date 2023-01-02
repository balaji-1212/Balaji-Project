Do not enqueue or merge files from this folder permanently!
Its fixes for different plugins that not for all users!
Enqueue it only from child theme!

Instructions
1) Copy file to child-theme/js/custom-file.js
2) Enqueue script by using code below
   wp_enqueue_script('any-key', get_stylesheet_directory_uri() . '/js/custom-file.js', array('etheme'));