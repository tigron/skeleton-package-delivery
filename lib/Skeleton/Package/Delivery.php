<?php
/**
 * Delivery class
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package;

class Delivery {
	use \Skeleton\Object\Get;
	use \Skeleton\Pager\Page;
	use \Skeleton\Object\Model;
	use \Skeleton\Object\Delete;
	use \Skeleton\Object\Save;

	/**
	 * Get delivery_items
	 *
	 * @access public
	 * @return array $delivery_items
	 */
	public function get_delivery_items() {
		return \Skeleton\Package\Delivery\Item::get_by_delivery($this);
	}

	/**
	 * Add deliverable
	 *
	 * @access public
	 * @param \Skeleton\Package\Deliverable $deliverable
	 */
	public function add_deliverable(\Skeleton\Package\Deliverable $deliverable) {
		$item = new \Skeleton\Package\Delivery\Item();
		$item->delivery_id = $this->id;
		$item->set_deliverable($deliverable);
		$item->save();
	}

	/**
	 * Set recipient
	 *
	 * @access public
	 * @param \Skeleton\Package\Delivery\Recipient $recipient
	 */
	public function set_delivery_recipient(\Skeleton\Package\Delivery\Recipient $recipient) {
		$this->recipient_object_classname = get_class($recipient);
		$this->recipient_object_id = $recipient->id;
	}

	/**
	 * Set courier
	 *
	 * @access public
	 * @param \Skeleton\Package\Courier
	 */
	public function set_courier(Courier $courier) {
		$this->courier_object_classname = get_class($courier);
		$this->courier_object_id = $courier->id;
	}

	/**
	 * Set trigger
	 *
	 * @access public
	 * @param Object $object
	 */
	public function set_trigger($trigger) {
		$this->trigger_object_classname = get_class($trigger);
		$this->trigger_object_id = $trigger->id;
	}

	/**
	 * Get trigger
	 *
	 * @access public
	 * @return Object $trigger
	 */
	public function get_trigger() {
		$classname = $this->trigger_object_classname;
		$object = $classname::get_by_id($this->trigger_object_id);
		return $object;
	}

	/**
	 * Get recipient
	 *
	 * @access public
	 * @return \Skeleton\Package\Delivery\Recipient $recipient
	 */
	public function get_delivery_recipient() {
		$classname = $this->recipient_object_classname;
		$object = $classname::get_by_id($this->recipient_object_id);
		return $object;
	}

	/**
	 * Get courier
	 *
	 * @access public
	 * @return Courier $courier
	 */
	public function get_courier() {
		$classname = $this->courier_object_classname;
		$object = $classname::get_by_id($this->courier_object_id);
		return $object;
	}

	/**
	 * Get overview
	 * This function is used to show an overview of the items to be shipped
	 *
	 * @access public
	 * @return array $delivery_items_overview
	 */
	public function get_overview() {
		$items = $this->get_delivery_items();

		$result = [];
		foreach ($items as $item) {
			$deliverable = $item->get_deliverable();
			if (!isset($result[$deliverable->id])) {
				$result[$deliverable->id] = [
					'deliverable' => $deliverable,
					'shipped' => 0,
					'total' => 0
				];
			}

			$result[$deliverable->id]['total']++;
			if ($item->shipment_item_id != 0) {
				$result[$deliverable->id]['shipped']++;
			}
		}
		return $result;
	}

	/**
	 * Check shipped
	 *
	 * @access public
	 * @return bool $shipped
	 */
	public function check_shipped() {
		$shipped = true;
		foreach ($this->get_delivery_items() as $item) {
			if ($item->shipment_item_id == 0) {
				$shipped = false;
			}
		}
		$this->shipped = $shipped;
		$this->save();
	}

	/**
	 * Get shipments
	 *
	 * @access public
	 * @return array $shipments
	 */
	public function get_shipments() {
		return Shipment::get_by_delivery($this);
	}

}
