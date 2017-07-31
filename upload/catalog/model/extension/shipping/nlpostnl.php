<?php
/*
   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

   ---------------------------------------------------------------------
   Jochem van den Anker 
   Dutch nlpostnl
   OpenCart 2.3.0.2
   Special thank you goes to
   Gerrit Bouweriks, SuperJuice (Sam), for conversions made to weight en length classes
   Sanne, Gijs and Cliff for testing and Norman for the translations
   Petran for old letterbox solution
   This new version uses a modified boxing.class.php as a letterbox solution.
   I was pointed out to this class by Ivo, another Dutch OpenCart user.
   https://github.com/yetzt/boxing/blob/master/boxing.class.php
*/
class ModelExtensionShippingnlpostnl extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/nlpostnl');

		if ($this->config->get('nlpostnl_status')) {
      		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('nlpostnl_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('nlpostnl_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
      		if (!$this->config->get('nlpostnl_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}

		if ($this->config->get('nlpostnl_use_freeshipping')) {
			if ($address['iso_code_2'] === 'NL') {
				if ($this->cart->getSubTotal() > $this->config->get('free_total')) {
					$status = false;
				}
			}
		}

		$verzendkosten = 0;
		$error = FALSE;
		$quote_data = array();
		$nlpostnl_box = false;

		if ($status) {
			//Query to find id of grams (g) as 1.5.1.1 removed the availability of the named unit
			$unit_query =  $this->db->query("SELECT weight_class_id FROM " . DB_PREFIX . "weight_class_description where LOWER(unit) = 'g'");

			if ($unit_query->num_rows) {$unit_g = $unit_query->row['weight_class_id'];}

			$weight = round((float) ($this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $unit_g)), 2);
			if ($weight >= 1000) {
				$show_weight = round((float) ($weight / 1000),2);

				$kg = $this->language->get('text_showweight_gram');
			}

			if ($weight < 1000) {
				$show_weight = floatval($this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $unit_g));
				$kg = $this->language->get('text_showweight_gram');
			}

			// subtotaal van het karretje exclusief BTW
			$sub_total = $this->cart->getSubTotal();

				if ($this->config->get('nlpostnl_cardboard_box_lenght')) {
					$boxlenght = $this->config->get('nlpostnl_cardboard_box_lenght');
				} else { $boxlenght = 800; }

				if ($this->config->get('nlpostnl_cardboard_box_width')) {
					$boxwidth = $this->config->get('nlpostnl_cardboard_box_width');
				} else { $boxwidth = 400; }

				if ($this->config->get('nlpostnl_cardboard_box_height')) {
					$boxheight = $this->config->get('nlpostnl_cardboard_box_height');
				} else { $boxheight = 400; }

				if ($this->config->get('nlpostnl_cardboard_box_weight')) {
					$boxweight = $this->config->get('nlpostnl_cardboard_box_weight');
				} else { $boxweight = 500; }

			require_once(DIR_APPLICATION . 'model/extension/shipping/postnl_boxing.class.php');
			$b = new boxing();
			$b -> add_outer_box($boxlenght,$boxwidth,$boxheight);

			// Query to find out if mm are configured in the database because OpenCart developers thought it wasn't needed in the API (currently no error condition if it doesn't exist)
			$unit_query =  $this->db->query("SELECT length_class_id FROM " . DB_PREFIX . "length_class_description where LOWER(unit) = 'mm'");

			if ($unit_query->num_rows) {$unit_mm = $unit_query->row['length_class_id'];}

			foreach ($this->cart->getProducts() as $cartitem) {

				if($cartitem['width'] != 0) {
					$cartitem['width'] = $this->length->convert($cartitem['width'], $cartitem['length_class_id'], $unit_mm);
				} else {
					$cartitem['width'] = 100;
				}

				if($cartitem['height'] != 0) {
					$cartitem['height'] = $this->length->convert($cartitem['height'], $cartitem['length_class_id'], $unit_mm);
				} else {
					$cartitem['height'] = 100;
				}

				if($cartitem['length'] != 0) {
					$cartitem['length'] = $this->length->convert($cartitem['length'], $cartitem['length_class_id'], $unit_mm);
				} else {
					$cartitem['length'] = 100;
				}

				for ($i = 1; $i <= $cartitem['quantity']; $i++) $b -> add_inner_box($cartitem['length'], $cartitem['width'], $cartitem['height']);

			}
			// eind formaat bepaling

			 	if ($b -> fits()) {
 					$nlpostnl_box = true;
 				} elseif (!$b -> fits()) {
 					$nlpostnl_box = false;
 				}


			// Maximaal te versturen pakketten, voor volgende versie
			$max_parcels = 1;

			// Zet alle maten voorlopig op 0
			$stuk_lenght = 0;
			$stuk_width = 0;
			$stuk_height = 0;
			//$nlpostnl_box = false;

 			if ($this->config->get('nlpostnl_use_mailbox') == 1 && $this->config->get('nlpostnl_netherlands_cost_mailbox') && $address['iso_code_2'] == 'NL' && $weight < 20000) {
 				$cost = 0;

 				if ($this->config->get('nlpostnl_mailbox_lenght')) {
 					$boxlenght = $this->config->get('nlpostnl_mailbox_lenght');
 				} else { $boxlenght = 380; }

 				if ($this->config->get('nlpostnl_mailbox_width')) {
 					$boxwidth = $this->config->get('nlpostnl_mailbox_width');
 				} else { $boxwidth = 260; }

 				if ($this->config->get('nlpostnl_mailbox_height')) {
 					$boxheight = $this->config->get('nlpostnl_mailbox_height');
 				} else { $boxheight = 30; }

 				if ($this->config->get('nlpostnl_mailbox_weight')) {
 					$boxweight = $this->config->get('nlpostnl_mailbox_weight');
 				} else { $boxweight = 150; }

 			require_once(DIR_APPLICATION . 'model/extension/shipping/postnl_boxing.class.php');
 			$b = new boxing();
 			$b -> add_outer_box($boxlenght,$boxwidth,$boxheight);

 			// Query to find out if mm are configured in the database because OpenCart developers thought it wasn't needed in the API (currently no error condition if it doesn't exist)
 			$unit_query =  $this->db->query("SELECT length_class_id FROM " . DB_PREFIX . "length_class_description where LOWER(unit) = 'mm'");

 			if ($unit_query->num_rows) {$unit_mm = $unit_query->row['length_class_id'];}

 			foreach ($this->cart->getProducts() as $cartitem) {
 				//if($cartitem['length_class_id'] != $unit_mm) {

 				if($cartitem['width'] != 0) {
 					$cartitem['width'] = $this->length->convert($cartitem['width'], $cartitem['length_class_id'], $unit_mm);
 				} else {
 					$cartitem['width'] = 100;
 				}

 				if($cartitem['height'] != 0) {
 					$cartitem['height'] = $this->length->convert($cartitem['height'], $cartitem['length_class_id'], $unit_mm);
 				} else {
 					$cartitem['height'] = 100;
 				}

 				if($cartitem['length'] != 0) {
 					$cartitem['length'] = $this->length->convert($cartitem['length'], $cartitem['length_class_id'], $unit_mm);
 				} else {
 					$cartitem['length'] = 100;
 				}

 				for ($i = 1; $i <= $cartitem['quantity']; $i++) $b -> add_inner_box($cartitem['length'], $cartitem['width'], $cartitem['height']);

 			}
 			// eind formaat bepaling

 			// als het past binen de opgegeven doos formaten
 			 if ($b -> fits()) {
  				$nlpostnl_box = true;

 				$weight = $weight + $boxweight;
 				if ($weight < 20){
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_mailbox_parcel_20');
 					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox');
 				}
				if ($weight < 50){
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_mailbox_parcel_50');
 					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox');
 				}
 				if ($weight < 100){
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_mailbox_parcel_100');
 					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox');
 				}
 				if ($weight < 250){
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_mailbox_parcel_250');
 					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox');
 				}
 				if ($weight < 2000){
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_mailbox_parcel_2000');
 					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox');
 				}



 				if ($weight <= $boxweight) {
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_title');
 					$error = $this->language->get('text_nlpostnl_error_zero_weight');
 				}

 				if ($weight > 20000) {
 					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
 					$title = $this->language->get('text_nlpostnl_title');
 					$error = $this->language->get('text_nlpostnl_error_max_weight');
 				}

 				$quote_data['nlpostnl_netherlands_cost_mailbox'] = array(
 					'code' => 'nlpostnl.nlpostnl_netherlands_cost_mailbox',
 					'title' => $title,
 					'cost' => $verzendkosten,
 					'tax_class_id' => $this->config->get('nlpostnl_tax_class_id'),
 					'text' => $this->currency->format($this->tax->calculate($verzendkosten, $this->config->get('nlpostnl_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
 					);
 				}

 			}

			// PostNL Nederland met brievenbus aangezet
			if (($this->config->get('nlpostnl_use_mailbox') == 1 || $this->config->get('nlpostnl_use_mailbox') == 0) && $address['iso_code_2'] == 'NL') {
				$cost = 0;
//				$weight = $weight + $boxweight;

				if ($weight <= 20) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_20');

					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_20');
				}

				elseif ($weight <= 50) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_50');

					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_50');
				}
				
				elseif ($weight <= 100) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_100');

					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_100');
				}
				/*
				if ($weight > $boxweight && $weight <= 100) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_100');

					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_100');
				}
				*/
				elseif ($weight <= 250) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_250');
					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_250');
				}

				elseif ($weight <= 2000) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_mailbox_parcel_2000');

					$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_2000');
				}


				if ($nlpostnl_box == false) {
					$title_nlpostnl = $this->language->get('text_nlpostnl_title');
					$title = $this->language->get('text_nlpostnl_title');
					$error = $this->language->get('text_nlpostnl_error_max_box');
				}

				$quote_data['nlpostnl_netherlands_cost'] = array(
					'code' => 'nlpostnl.nlpostnl_netherlands_cost',
					'title' => $title,
					'cost' => $verzendkosten,
					'tax_class_id' => $this->config->get('nlpostnl_tax_class_id'),
					'text' => $this->currency->format($this->tax->calculate($verzendkosten, $this->config->get('nlpostnl_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}


		
			if (($this->config->get('nlpostnl_use_mailbox') == 1 || $this->config->get('nlpostnl_use_mailbox') == 0) && $address['iso_code_2'] == 'NL' && $this->config->get('nlpostnl_use_mailbox_track') == 1)
				{
				$title_nlpostnl = $this->language->get('text_nlpostnl_title');
				$title = $this->language->get('text_nlpostnl_mailbox_parcel_track');
				$verzendkosten = $this->config->get('nlpostnl_netherlands_cost_mailbox_track');	
		
				$quote_data['nlpostnl_netherlands_cost_mailbox_track'] = array(
 					'code' => 'nlpostnl.nlpostnl_netherlands_cost_mailbox_track',
 					'title' => $title,
 					'cost' => $verzendkosten,
 					'tax_class_id' => $this->config->get('nlpostnl_tax_class_id'),
 					'text' => $this->currency->format($this->tax->calculate($verzendkosten, $this->config->get('nlpostnl_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}
		





		}

		$method_data = array();

		if ($quote_data) {

			$method_data = array(
				'code' => 'nlpostnl',
				'title' => $title_nlpostnl,
				'quote' => $quote_data,
				'sort_order' => $this->config->get('nlpostnl_sort_order'),
				'error' => $error
				);
		}
		return $method_data;
	}
}

?>