CREATE TABLE IF NOT EXISTS `menu_items` (
  `page_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(100) DEFAULT NULL,
  `uri` varchar(150) DEFAULT NULL,
  `fragment` varchar(50) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `rel` varchar(30) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `id` varchar(30) DEFAULT NULL,
  `link_order` int(11) DEFAULT NULL,
  `sub_page_of` int(11) UNSIGNED DEFAULT NULL,
  `li_class` varchar(50) DEFAULT NULL,
  `li_id` varchar(30) DEFAULT NULL,
  `ul_class` varchar(50) DEFAULT NULL,
  `ul_id` varchar(30) DEFAULT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`page_id`),
  KEY `subof` (`sub_page_of`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`sub_page_of`) REFERENCES `menu_items` (`page_id`) ON DELETE SET NULL ON UPDATE CASCADE;
