<?php
/**
 * Deliverable interface
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery;

interface Deliverable {

	/**
	 * Get name
	 *
	 * @access public
	 * @return string $name
	 */
	public function get_name();

	/**
	 * Get weight
	 *
	 * @access public
	 * @return double $weight (in kg)
	 */
	public function get_weight();
}
