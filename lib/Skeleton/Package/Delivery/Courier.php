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

	/**
	 * Is traceable
	 *
	 * @access public
	 * @return bool $traceable
	 */
	public function is_traceable();

	/**
	 * Trace
	 *
	 * @access public
	 * @return array  $trace
	 */
	public function trace(\Skeleton\Package\Delivery\Shipment $shipment);

	/**
	 * Has label
	 *
	 * @access public
	 * @return bool $has_label
	 */
	public function has_label();

	/**
	 * Get label
	 *
	 * @access public
	 * @return File $label
	 */
	public function get_label(\Skeleton\Package\Delivery\Shipment $shipment);

}
