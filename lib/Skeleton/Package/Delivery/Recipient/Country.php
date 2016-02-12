<?php
/**
 * Recipient interface
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery\Recipient;

interface Country {

	/**
	 * Get ISO2
	 *
	 * @access public
	 * @return string $iso2
	 */
	public function get_iso2();

	/**
	 * Get all
	 *
	 * @access public
	 * @return array $countries
	 */
	public static function get_all();

}
