<?php
/**
 * Database migration class
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */
namespace Skeleton\Package\Delivery;
use \Skeleton\Database\Database;

class Migration_20160214_225022_Init extends \Skeleton\Database\Migration {

	/**
	 * Migrate up
	 *
	 * @access public
	 */
	public function up() {
		$db = Database::get();
		$db->query('
			CREATE TABLE `delivery` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `trigger_object_classname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `trigger_object_id` int(11) NOT NULL,
			  `recipient_object_classname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `recipient_object_id` int(11) NOT NULL,
			  `courier_object_classname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `courier_object_id` int(11) NOT NULL,
			  `created` datetime NOT NULL,
			  `customer_contact_id` int(11) NOT NULL,
			  `shipped` tinyint(4) NOT NULL,
			  `shipping_method_type_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		', []);

		$db->query('
			CREATE TABLE `delivery_item` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `delivery_id` int(11) NOT NULL,
			  `deliverable_object_classname` varchar(64) NOT NULL,
			  `deliverable_object_id` int(11) NOT NULL,
			  `shipment_item_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		', []);

		$db->query('
			CREATE TABLE `shipment` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `delivery_id` int(11) NOT NULL,
			  `created` datetime NOT NULL,
			  `delivered` tinyint(4) NOT NULL,
			  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `firstname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `lastname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `housenumber` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `line1` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `line2` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `line3` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `fax` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `country_id` int(11) NOT NULL,
			  `vat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `courier_object_classname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `courier_object_id` int(11) NOT NULL,
			  `weight` decimal(10,3) NOT NULL,
			  `label_file_id` int(11) NOT NULL,
			  `courier_data` text COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		', []);

		$db->query('
			CREATE TABLE `shipment_item` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `shipment_id` int(11) NOT NULL,
			  `delivery_item_id` int(11) NOT NULL,
			  `weight` decimal(10,3) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		', []);

	}

	/**
	 * Migrate down
	 *
	 * @access public
	 */
	public function down() {

	}
}
