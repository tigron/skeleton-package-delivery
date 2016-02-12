<?php
/**
 * Recipient interface
 *
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Package\Delivery;

interface Recipient {

	/**
	 * Get firstname
	 *
	 * @access public
	 * @return string $firstname
	 */
	public function get_firstname();

	/**
	 * Get lastname
	 *
	 * @access public
	 * @return string $lastname
	 */
	public function get_lastname();

	/**
	 * Get company
	 *
	 * @access public
	 * @return string $company
	 */
	public function get_company();

	/**
	 * Get street
	 *
	 * @access public
	 * @return string $street
	 */
	public function get_street();

	/**
	 * Get housenumber
	 *
	 * @access public
	 * @return string $housenumber
	 */
	public function get_housenumber();

	/**
	 * Get zipcode
	 *
	 * @access public
	 * @return string $zipcode
	 */
	public function get_zipcode();

	/**
	 * Get city
	 *
	 * @access public
	 * @return string $city
	 */
	public function get_city();

	/**
	 * Get country
	 *
	 * @access public
	 * @return Country $country
	 */
	public function get_country();

	/**
	 * Get phone
	 *
	 * @access public
	 * @return string $phone
	 */
	public function get_phone();

	/**
	 * Get fax
	 *
	 * @access public
	 * @return string $fax
	 */
	public function get_fax();

	/**
	 * Get vat
	 *
	 * @access public
	 * @return string $vat
	 */
	public function get_vat();

	/**
	 * Get email
	 *
	 * @access public
	 * @return string $email
	 */
	public function get_email();


}
