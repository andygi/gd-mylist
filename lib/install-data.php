<?php
    global $wpdb;
?>

#
# Table structure for table 'mylist'
#

DROP TABLE IF EXISTS <?php echo $wpdb->prefix; ?>gd_mylist;
CREATE TABLE <?php echo $wpdb->prefix; ?>gd_mylist (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(15) DEFAULT NULL COMMENT 'post and pages',
  `user_id` varchar(15) DEFAULT NULL,
  `insert_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
