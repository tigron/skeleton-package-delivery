<?php
/**
 * Delivery_Item class
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery;
use Skeleton\Package\Delivery;
use Skeleton\Database\Database;

class Item {
	use \Skeleton\Object\Get;
	use \Skeleton\Object\Model;
	use \Skeleton\Object\Delete;
	use \Skeleton\Object\Save;

	private static $class_configuration = [
	  'database_table' => 'delivery_item',
	];

	/**
	 * Set deliverable
	 *
	 * @access public
	 * @param Deliverable $deliverable
	 */
	public function set_deliverable(\Skeleton\Package\Deliverable $deliverable) {
		$this->deliverable_object_classname = get_class($deliverable);
		$this->deliverable_object_id = $deliverable->id;
	}

	/**
	 * Get deliverable
	 *
	 * @access public
	 * @return Deliverable $deliverable
	 */
	public function get_deliverable() {
		$classname = $this->deliverable_object_classname;
		$object = $classname::get_by_id($this->deliverable_object_id);
		return $object;
	}

	/**
	 * Get by delivery
	 *
	 * @access public
	 * @param Delivery $delivery
	 * @return array $delivery_items
	 */
	public static function get_by_delivery(Delivery $delivery) {
		$db = Database::Get();
		$ids = $db->get_column('SELECT id FROM delivery_item WHERE delivery_id=?', [ $delivery->id ]);
		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}
		return $objects;
	}

	/**
	 * get_by_delivery_deliverable
	 *
	 * @access public
	 * @param Delivery $delivery
	 * @param Deliverable $deliverable
	 * @return array $delivery_items
	 */
	public static function get_by_delivery_deliverable(Delivery $delivery, \Skeleton\Package\Deliverable $deliverable) {
		$db = Database::get();
		$ids = $db->get_column('SELECT id FROm delivery_item WHERE delivery_id=? AND deliverable_object_classname=? AND deliverable_object_id=?', [ $delivery->id, get_class($deliverable), $deliverable->id ]);
		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}
		return $objects;
	}

}
