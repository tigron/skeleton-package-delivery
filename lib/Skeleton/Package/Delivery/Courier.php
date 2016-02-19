<?php
/**
 * Shipping method interface
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery;

interface Courier {

	/**
	 * Get name
	 *
	 * @abstract
	 * @access public
	 * @param Basket $basket
	 * @return string $name
	 */
	public function get_name();

	/**
	 * Validate shipment
	 *
	 * @access public
	 * @param Shipment $shipment
	 * @return array $errors
	 */
	public function validate_shipment(\Skeleton\Package\Delivery\Shipment $shipment);

	/**
	 * Handle a shipment
	 *
	 * @access public
	 * @param \Skeleton\Package\Shipment $shipment
	 */
	public function handle(\Skeleton\Package\Delivery\Shipment $shipment);

}
