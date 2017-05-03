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

class Migration_20170503_143228_Shipment extends \Skeleton\Database\Migration {

	/**
	 * Migrate up
	 *
	 * @access public
	 */
	public function up() {
		$db = Database::get();
		$db->query("ALTER TABLE `shipment` ADD `remote_identifier` text COLLATE 'utf8_unicode_ci' NOT NULL;", []);
	}

	/**
	 * Migrate down
	 *
	 * @access public
	 */
	public function down() {

	}
}
