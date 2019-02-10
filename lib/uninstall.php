<?php global $wpdb; ?>

DELETE TABLE IF EXISTS <?php echo $wpdb->prefix; ?>gd_mylist;

DELETE FROM <?php echo $wpdb->prefix; ?>options WHERE option_name='gd_mylist_settings';
