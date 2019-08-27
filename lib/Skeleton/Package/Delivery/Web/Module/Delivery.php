<?php
/**
 * Module Login
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Package\Delivery\Web\Module;

use \Skeleton\Pager\Web\Pager;
use \Skeleton\Core\Web\Session;
use \Skeleton\Core\Web\Template;
use \Skeleton\Package\Crud\Web\Module\Crud;
use \Skeleton\Package\Delivery\Shipment;

class Delivery extends Crud {

	/**
	 * Login required ?
	 * Default = yes
	 *
	 * @access public
	 * @var bool $login_required
	 */
	public $login_required = false;

	/**
	 * Template to use
	 *
	 * @access public
	 * @var string $template
	 */
	public $template = '@skeleton-package-delivery\content.twig';


	/**
	 * Get pager
	 *
	 * @access public
	 * @return Skeleton\Pager\Web\Pager $pager
	 */
	public function get_pager() {
		$pager = new Pager('\Skeleton\Package\Delivery\Delivery');
		$pager->add_sort_permission('id');
		if (isset($_POST['shipped'])) {
			if ($_POST['shipped'] == 1) {
				$pager->add_condition('shipped', 1);
			} elseif ($_POST['shipped'] == 0) {
				$pager->add_condition('shipped', 0);
			} else {
				$pager->clear_condition('shipped');
			}
		}
		return $pager;
	}

	/**
	 * Add packet
	 *
	 * @access public
	 */
	public function display_add_shipment() {
		$delivery = \Skeleton\Package\Delivery\Delivery::get_by_id($_GET['id']);

		$shipment = Shipment::get_by_id($_POST['shipment_id']);
		$shipment->handle();
		$delivery->check_shipped();

		Session::Redirect($this->get_module_path() . '?action=edit&id=' . $delivery->id);
	}

	/**
	 * validate shipment
	 *
	 * @access public
	 */
	public function display_validate_shipment() {
		$delivery_id = $_POST['delivery_id'];
		$form = parse_str($_POST['form'], $_POST);

		$delivery = \Skeleton\Package\Delivery\Delivery::get_by_id($delivery_id);

		$shipment = new Shipment();
		$shipment->delivery_id = $delivery_id;

		if ($_POST['address_input'] == 'manual') {
			unset($_POST['shipment']['street']);
			unset($_POST['shipment']['housenumber']);
		} else {
			unset($_POST['shipment']['line1']);
			unset($_POST['shipment']['line2']);
			unset($_POST['shipment']['line3']);
		}

		if (isset($_POST['shipment']['courier'])) {
			list($courier_object_classname, $courier_object_id) = explode('/', $_POST['shipment']['courier']);
			$_POST['shipment']['courier_object_classname'] = $courier_object_classname;
			$_POST['shipment']['courier_object_id'] = $courier_object_id;
			unset($_POST['shipment']['courier']);
		}

		$shipment->load_array($_POST['shipment']);
		$validated = true;

		if (!$shipment->validate($shipment_errors)) {
			$validated = false;
		}

		$total_items = 0;
		$shipment_item_errors = [];

		if (isset($_POST['shipment_item'])) {

			foreach ($_POST['shipment_item'] as $deliverable_object_classname => $array) {

				foreach ($array as $deliverable_object_id => $data) {
					$deliverable = $deliverable_object_classname::get_by_id($deliverable_object_id);

					$delivery_items = \Skeleton\Package\Delivery\Item::get_by_delivery_deliverable($delivery, $deliverable);
					// Remove the delivery_items that are already shipped
					foreach ($delivery_items as $key => $delivery_item) {
						if ($delivery_item->shipment_item_id != 0) {
							unset($delivery_items[$key]);
						}
					}

					if (!isset($data['to_ship'])) {
						$data['to_ship'] = 0;
					}

					if (count($delivery_items) < $data['to_ship']) {
						throw new Exception('This should not happen, more items are requested for shipment than allowed');
					}

					$total_items += $data['to_ship'];

					for ($i=1; $i<=$data['to_ship']; $i++) {
						$delivery_item = array_shift($delivery_items);

						$shipment_item = new \Skeleton\Package\Delivery\Shipment\Item();
						$shipment_item->delivery_item_id = $delivery_item->id;
						$shipment_item->shipment_id = $shipment->id;
						$shipment_item->weight = $data['weight'];
						$shipment_item->save();
						$delivery_item->shipment_item_id = $shipment_item->id;
						$delivery_item->save();
					}
				}
			}
		}

		if ($total_items <= 0) {
			$shipment_item_errors[] = 'no products shipped';
			$validated = false;
		}

		/**
		 * If not validated, cleanup
		 */
		if (!$validated) {
			$shipment_items = $shipment->get_shipment_items();
			foreach ($shipment_items as $shipment_item) {
				$delivery_item = $shipment_item->delivery_item;
				$delivery_item->shipment_item_id = 0;
				$delivery_item->save();
			}
			$shipment->delete();
		}

		$result = [
			'shipment_errors' => $shipment_errors,
			'shipment_item_errors' => $shipment_item_errors,
			'validated' => $validated
		];

		if ($validated) {
			$result['shipment_id'] = $shipment->id;
		}

		echo json_encode($result);
		$this->template = false;
	}

	/**
	 * Trace
	 *
	 * @access public
	 */
	public function display_trace() {
		$this->template = false;
		$template = Template::Get();

		$shipment = Shipment::get_by_id($_GET['shipment_id']);
		$template->assign('shipment', $shipment);

		$trace = $shipment->get_courier()->trace($shipment);
		$template->assign('trace', $trace);

		$this->template = '@skeleton-package-delivery/trace.twig';
	}

	/**
	 * Print label
	 *
	 * @access public
	 */
	public function display_label() {
		$this->template = false;
		$shipment = Shipment::get_by_id($_GET['shipment_id']);
		$template = Template::Get();

		$file = $shipment->get_courier()->get_label($shipment);
		$file->client_download();
	}


	/**
	 * Is deletable
	 *
	 * @access public
	 * @param Object $object
	 * @return bool $deletable
	 */
	public function is_deletable($object) {
		return false;
	}

}
