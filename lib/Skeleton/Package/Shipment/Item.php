<?php
/**
 * Shipment_Item class
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Shipment;

use Skeleton\Package\Shipment;
use Skeleton\Database\Database;

class Item {
	use \Skeleton\Object\Get;
	use \Skeleton\Pager\Page;
	use \Skeleton\Object\Model;
	use \Skeleton\Object\Delete;
	use \Skeleton\Object\Save;

	private static $class_configuration = [
	  'database_table' => 'shipment_item',
	];

	/**
	 * Get delivery_item
	 *
	 * @access public
	 * @return \Skeleton\Package\Delivery\Item $delivery_item
	 */
	public function get_delivery_item() {
		return \Skeleton\Package\deliver\Item::get_by_id($this->delivery_item_id);
	}

	/**
	 * Get by Shipment
	 *
	 * @access public
	 * @param Shipment $shipment
	 * @return array $shipment_items
	 */
	public static function get_by_shipment(Shipment $shipment) {
		$db = Database::Get();
		$ids = $db->get_column('SELECT id FROM shipment_item WHERE shipment_id=?', [ $shipment->id ]);
		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}
		return $objects;
	}
}
