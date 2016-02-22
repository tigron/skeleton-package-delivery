# skeleton-package-delivery

## Description

Gives delivery functionality of products in Skeleton


## Installation

Installation via composer:

    composer require tigron/skeleton-package-delivery

## Howto

Create a module in your application that extends from Skeleton\Package\Web\Module\Crud

    <?php
	/**
	 * Module Crud
	 *
	 * @author Christophe Gosiau <christophe@tigron.be>
	 * @author Gerry Demaret <gerry@tigron.be>
	 * @author David Vandemaele <david@tigron.be>
	 */

	use Skeleton\Package\Web\Module\Delivery

	class Web_Module_Deliver extends Delivery {

		/**
		 * The template
		 *
		 * @access public
		 */
		public $template = 'delivery.twig';
	}

Create a template for your module that injects the generated templates into your layout

	{% extends "_default/layout.base.twig" %}


	{% block header_js %}
		{% embed "@skeleton-package-crud/javascript.twig" %}{% endembed %}
	{% endblock header_js %}

	{% block header_css %}
		{% embed "@skeleton-package-crud/css.twig" %}{% endembed %}
	{% endblock header_css %}

	{% block content %}
		{% embed "@skeleton-package-crud/content.twig" %}{% endembed %}
	{% endblock content %}


Create a route in your application Config.php

	/**
	 * Routes
	 */
	'routes' => [
		'web_module_deliver' => [
			'$language/deliver'
		],
	]

Now make sure you implement the following classes:

*Deliverable*

	<?php
	/**
	 * Deliverable interface
	 *
	 *
	 * @author Gerry Demaret <gerry@tigron.be>
	 * @author Christophe Gosiau <christophe@tigron.be>
	 * @author David Vandemaele <david@tigron.be>
	 */

	namespace Skeleton\Package;

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

*Delivery\Recipient*

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

*Courier*

	<?php
	/**
	 * Shipping method interface
	 *
	 * @author Christophe Gosiau <christophe@tigron.be>
	 * @author Gerry Demaret <gerry@tigron.be>
	 * @author David Vandemaele <david@tigron.be>
	 */

	namespace Skeleton\Package;

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
		public function validate_shipment(\Skeleton\Package\Shipment $shipment);

		/**
		 * Handle a shipment
		 *
		 * @access public
		 * @param \Skeleton\Package\Shipment $shipment
		 */
		public function handle(\Skeleton\Package\Shipment $shipment);

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


Now start creating deliveries:

		$delivery = new \Skeleton\Package\Delivery();
		$delivery->set_trigger($my_class);
		$delivery->set_delivery_recipient($my_shipping_address); // Delivery Recipient
		$delivery->set_courier($this->shipping_method->shipping_method_courier); // Courier
		$delivery->save();

		foreach ($this->get_order_items() as $order_item) {
			$delivery->add_deliverable($order_item->product); // Deliverable
		}
