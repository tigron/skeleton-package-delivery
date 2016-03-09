<?php
/**
 * Shipment class
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery;

use \Skeleton\Package\Delivery\Shipment\Item;
use \Skeleton\Database\Database;

class Shipment {
	use \Skeleton\Object\Get;
	use \Skeleton\Pager\Page;
	use \Skeleton\Object\Model;
	use \Skeleton\Object\Delete;
	use \Skeleton\Object\Save;

	/**
	 * Get courier
	 *
	 * @access public
	 * @return \Skeleton\Package\Courier
	 */
	public function get_courier() {
		$classname = $this->courier_object_classname;
		return $classname::get_by_id($this->courier_object_id);
	}

	/**
	 * Set courier
	 *
	 * @access public
	 * @param \Skeleton\Package\Courier
	 */
	public function set_courier(\Skeleton\Package\Delivery\Courier $courier) {
		$this->courier_object_classname = get_class($courier);
		$this->courier_object_id = $courier->id;
	}

	/**
	 * Set courier_data
	 *
	 * @access public
	 * @param array $courier_data
	 */
	public function set_courier_data($data) {
		$this->details['courier_data'] = serialize($data);
	}

	/**
	 * Get courier_data
	 *
	 * @access public
	 * @return array $courier_data
	 */
	public function get_courier_data() {
		$data = @unserialize($this->courier_data);
		if ($data === false) {
			return [];
		} else {
			return $data;
		}
	}

	/**
	 * Get shipment_items
	 *
	 * @access public
	 * @return array $shipment_items
	 */
	public function get_shipment_items() {
		return Item::get_by_shipment($this);
	}

	/**
	 * Get weight
	 *
	 * @access public
	 * @return double $weight (in gramm)
	 */
	public function get_weight() {
		if (!isset($this->id)) {
			// We return a dummy number to allow validation
			return 1000;
		}
		$items = $this->get_shipment_items();
		$weight = 0;
		foreach ($items as $item) {
			$delivery_item = \Skeleton\Package\Delivery\Item::get_by_id($item->delivery_item_id);
			$weight += $delivery_item->get_deliverable()->get_weight();
		}
		return $weight;
	}



	/**
	 * Get overview
	 * This function is used to show an overview of the items to be shipped
	 *
	 * @access public
	 * @return array $delivery_items_overview
	 */
	public function get_overview() {
		$items = $this->get_shipment_items();

		$result = [];
		foreach ($items as $item) {
			$delivery_item = \Skeleton\Package\Delivery\item::get_by_id($item->delivery_item_id);
			$deliverable = $delivery_item->get_deliverable();
			if (!isset($result[$deliverable->id])) {
				$result[$deliverable->id] = [
					'deliverable' => $deliverable,
					'shipped' => 0,
					'total' => 0
				];
			}

			$result[$deliverable->id]['total']++;
			if ($delivery_item->shipment_item_id > 0) {
				$result[$deliverable->id]['shipped']++;
			}
		}
		return $result;
	}

	/**
	 * Handle
	 *
	 * @access public
	 */
	public function handle() {
		$this->get_courier()->handle($this);

		/**
		 * Manage the stock
		 */
		if (!class_exists('\Skeleton\Package\Stock\Stock')) {
			return;
		}

		foreach ($this->get_overview() as $item) {
			\Skeleton\Package\Stock\Stock::change($item['deliverable'], $item['shipped'] * (-1), $this, 'Delivery ' . $this->delivery_id);
		}
	}

	/**
	 * Check delivered
	 *
	 * @access public
	 * @return bool $delivered
	 */
	public function check_delivered() {
		$delivered = $this->shipping_method_type->check_delivered($this);
		$this->delivered = $delivered;
		$this->save();
		return $delivered;
	}

	/**
	 * Validates the given input before insertion
	 *
	 * @param array The array that will contain the errors encountered. Passed by reference.
	 * @param bool Indicates if email is a mandatory field
	 * @return array The array containing the errors
	 * @access public
	 */
	public function validate(&$errors = []) {
		$required_fields = array('zip', 'city', 'country_id');

		$errors = array();
		foreach ($required_fields as $field) {
			if (!isset($this->details[$field]) || $this->details[$field] == '') {
				$errors[$field] = $field;
			}
		}

		$errors = array_merge($errors, $this->get_courier()->validate_shipment($this));


		if (isset($this->details['city']) AND strlen($this->details['city']) < 2) {
			$errors['city'] = 'city';
		}

		if (count($errors) > 0) {
			return false;
		}
		return true;
	}

	/**
	 * Get undelivered
	 *
	 * @access public
	 * @return array $undelivered
	 */
	public static function get_undelivered() {
		$db = Database::get();
		$ids = $db->get_column('SELECT id FROM shipment WHERE delivered=0', []);

		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}
		return $objects;
	}

	/**
	 * Get by delivery
	 *
	 * @access public
	 * @param Delivery $delivery
	 * @return array $shipments
	 */
	public static function get_by_delivery(Delivery $delivery) {
		$db = Database::Get();
		$ids = $db->get_column('SELECT id FROM shipment WHERE delivery_id=?', [ $delivery->id ]);
		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}
		return $objects;
	}
}
