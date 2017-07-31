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
class ControllerExtensionShippingNlpostnl extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/nlpostnl');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nlpostnl', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}
	
	
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		
		$data['text_help_nl_mailbox'] = $this->language->get('text_help_nl_mailbox');
		$data['text_help_nl_small'] = $this->language->get('text_help_nl_small');
		$data['text_help_nl_medium'] = $this->language->get('text_help_nl_medium');
		$data['text_help_nl_large'] = $this->language->get('text_help_nl_large');
		
		$data['text_help_small'] = $this->language->get('text_help_small');
		$data['text_help_medium'] = $this->language->get('text_help_medium');
		$data['text_help_large'] = $this->language->get('text_help_large');
			
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['text_help_nl_mailbox_20'] = $this->language->get('text_help_nl_mailbox_20');
		$data['text_help_nl_mailbox_50'] = $this->language->get('text_help_nl_mailbox_50');
		$data['text_help_nl_mailbox_100'] = $this->language->get('text_help_nl_mailbox_100');
		$data['text_help_nl_mailbox_250'] = $this->language->get('text_help_nl_mailbox_250');
		$data['text_help_nl_mailbox_2000'] = $this->language->get('text_help_nl_mailbox_2000');
		$data['text_help_nl_mailbox_track'] = $this->language->get('text_help_nl_mailbox_track');


		$data['entry_netherlands_cost_mailbox_20'] = $this->language->get('entry_netherlands_cost_mailbox_20');
		$data['entry_netherlands_cost_mailbox_50'] = $this->language->get('entry_netherlands_cost_mailbox_50');
		$data['entry_netherlands_cost_mailbox_100'] = $this->language->get('entry_netherlands_cost_mailbox_100');
		$data['entry_netherlands_cost_mailbox_250'] = $this->language->get('entry_netherlands_cost_mailbox_250');
		$data['entry_netherlands_cost_mailbox_2000'] = $this->language->get('entry_netherlands_cost_mailbox_2000');
		$data['entry_netherlands_cost_mailbox_track'] = $this->language->get('entry_netherlands_cost_mailbox_track');
		$data['entry_netherlands_cost_small'] = $this->language->get('entry_netherlands_cost_small');
		$data['entry_netherlands_cost_medium'] = $this->language->get('entry_netherlands_cost_medium');
		$data['entry_netherlands_cost_large'] = $this->language->get('entry_netherlands_cost_large');
		
		$data['entry_use_freeshipping'] = $this->language->get('entry_use_freeshipping');
		$data['entry_use_mailbox'] = $this->language->get('entry_use_mailbox');
		$data['entry_use_mailbox_track'] = $this->language->get('entry_use_mailbox_track');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['icon_nlpostnl_tarifs'] = $this->language->get('icon_nlpostnl_tarifs');
			
		$data['help_use_freeshipping'] = $this->language->get('help_use_freeshipping');
		$data['help_use_mailbox'] = $this->language->get('help_use_mailbox');
		$data['help_use_mailbox_track'] = $this->language->get('help_use_mailbox_track');
		
		$data['entry_nlpostnl_mailbox_lenght'] = $this->language->get('entry_nlpostnl_mailbox_lenght');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['icon_box_sizes'] = $this->language->get('icon_box_sizes');
		$data['icon_small_box_sizes'] = $this->language->get('icon_small_box_sizes');
		$data['icon_nlpostnl_settings'] = $this->language->get('icon_nlpostnl_settings');
		
		// Brievenbus formaten
		$data['text_help_nlpostnl_mailbox_lenght'] = $this->language->get('text_help_nlpostnl_mailbox_lenght');
		$data['text_help_nlpostnl_mailbox_width'] = $this->language->get('text_help_nlpostnl_mailbox_width');
		$data['text_help_nlpostnl_mailbox_height'] = $this->language->get('text_help_nlpostnl_mailbox_height');
		$data['text_help_nlpostnl_mailbox_weight'] = $this->language->get('text_help_nlpostnl_mailbox_weight');
		
		$data['entry_nlpostnl_mailbox_lenght'] = $this->language->get('entry_nlpostnl_mailbox_lenght');
		$data['entry_nlpostnl_mailbox_width'] = $this->language->get('entry_nlpostnl_mailbox_width');
		$data['entry_nlpostnl_mailbox_height'] = $this->language->get('entry_nlpostnl_mailbox_height');
		$data['entry_nlpostnl_mailbox_weight'] = $this->language->get('entry_nlpostnl_mailbox_weight');
		
		// verzenddoos formaten
		$data['text_help_nlpostnl_cardboard_box_lenght'] = $this->language->get('text_help_nlpostnl_cardboard_box_lenght');
		$data['text_help_nlpostnl_cardboard_box_width'] = $this->language->get('text_help_nlpostnl_cardboard_box_width');
		$data['text_help_nlpostnl_cardboard_box_height'] = $this->language->get('text_help_nlpostnl_cardboard_box_height');
		$data['text_help_nlpostnl_cardboard_box_weight'] = $this->language->get('text_help_nlpostnl_cardboard_box_weight');
		
		$data['entry_nlpostnl_cardboard_box_lenght'] = $this->language->get('entry_nlpostnl_cardboard_box_lenght');
		$data['entry_nlpostnl_cardboard_box_width'] = $this->language->get('entry_nlpostnl_cardboard_box_width');
		$data['entry_nlpostnl_cardboard_box_height'] = $this->language->get('entry_nlpostnl_cardboard_box_height');
		$data['entry_nlpostnl_cardboard_box_weight'] = $this->language->get('entry_nlpostnl_cardboard_box_weight');
		
		$data['text_countries_westeurope'] = $this->language->get('text_countries_westeurope');
		$data['text_countries_europe'] = $this->language->get('text_countries_europe');
		$data['text_countries_world'] = $this->language->get('text_countries_world');
		$data['text_countries_customs'] = $this->language->get('text_countries_customs');


 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/nlpostnl', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/nlpostnl', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);
  		

		// Netherlands
		
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_20'])) {
			$data['nlpostnl_netherlands_cost_mailbox_20'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_20'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_20'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_20');
		}
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_50'])) {
			$data['nlpostnl_netherlands_cost_mailbox_50'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_50'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_50'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_50');
		}
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_100'])) {
			$data['nlpostnl_netherlands_cost_mailbox_100'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_100'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_100'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_100');
		}
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_250'])) {
			$data['nlpostnl_netherlands_cost_mailbox_250'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_250'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_250'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_250');
		}
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_2000'])) {
			$data['nlpostnl_netherlands_cost_mailbox_2000'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_2000'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_2000'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_2000');
		}
		if (isset($this->request->post['nlpostnl_netherlands_cost_mailbox_track'])) {
			$data['nlpostnl_netherlands_cost_mailbox_track'] = $this->request->post['nlpostnl_netherlands_cost_mailbox_track'];
		} else {
			$data['nlpostnl_netherlands_cost_mailbox_track'] = $this->config->get('nlpostnl_netherlands_cost_mailbox_track');
		}
		

		if (isset($this->request->post['nlpostnl_netherlands_cost_small'])) {
			$data['nlpostnl_netherlands_cost_small'] = $this->request->post['nlpostnl_netherlands_cost_small'];
		} else {
			$data['nlpostnl_netherlands_cost_small'] = $this->config->get('nlpostnl_netherlands_cost_small');
		}
		
		if (isset($this->request->post['nlpostnl_netherlands_cost_medium'])) {
			$data['nlpostnl_netherlands_cost_medium'] = $this->request->post['nlpostnl_netherlands_cost_medium'];
		} else {
			$data['nlpostnl_netherlands_cost_medium'] = $this->config->get('nlpostnl_netherlands_cost_medium');
		}

		if (isset($this->request->post['nlpostnl_netherlands_cost_large'])) {
			$data['nlpostnl_netherlands_cost_large'] = $this->request->post['nlpostnl_netherlands_cost_large'];
		} else {
			$data['nlpostnl_netherlands_cost_large'] = $this->config->get('nlpostnl_netherlands_cost_large');
		}
		
		
		
		// Settings
		if (isset($this->request->post['nlpostnl_use_freeshipping'])) {
			$data['nlpostnl_use_freeshipping'] = $this->request->post['nlpostnl_use_freeshipping'];
		} else {
			$data['nlpostnl_use_freeshipping'] = $this->config->get('nlpostnl_use_freeshipping');
		}

		if (isset($this->request->post['nlpostnl_total'])) {
			$data['nlpostnl_total'] = $this->request->post['nlpostnl_total'];
		} else {
			$data['nlpostnl_total'] = $this->config->get('nlpostnl_total');
		}
		
		if (isset($this->request->post['nlpostnl_use_mailbox'])) {
			$data['nlpostnl_use_mailbox'] = $this->request->post['nlpostnl_use_mailbox'];
		} else {
			$data['nlpostnl_use_mailbox'] = $this->config->get('nlpostnl_use_mailbox');
		}

		if (isset($this->request->post['nlpostnl_use_mailbox_track'])) {
			$data['nlpostnl_use_mailbox_track'] = $this->request->post['nlpostnl_use_mailbox_track'];
		} else {
			$data['nlpostnl_use_mailbox_track'] = $this->config->get('nlpostnl_use_mailbox_track');
		}


		if (isset($this->request->post['nlpostnl_total'])) {
			$data['nlpostnl_total'] = $this->request->post['nlpostnl_total'];
		} else {
			$data['nlpostnl_total'] = $this->config->get('nlpostnl_total');
		}
		
		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['nlpostnl_geo_zone_id'])) {
			$data['nlpostnl_geo_zone_id'] = $this->request->post['nlpostnl_geo_zone_id'];
		} else {
			$data['nlpostnl_geo_zone_id'] = $this->config->get('nlpostnl_geo_zone_id');
		}

		if (isset($this->request->post['nlpostnl_tax_class_id'])) {
			$data['nlpostnl_tax_class_id'] = $this->request->post['nlpostnl_tax_class_id'];
		} else {
			$data['nlpostnl_tax_class_id'] = $this->config->get('nlpostnl_tax_class_id');
		}
		
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['nlpostnl_status'])) {
			$data['nlpostnl_status'] = $this->request->post['nlpostnl_status'];
		} else {
			$data['nlpostnl_status'] = $this->config->get('nlpostnl_status');
		}

		if (isset($this->request->post['nlpostnl_sort_order'])) {
			$data['nlpostnl_sort_order'] = $this->request->post['nlpostnl_sort_order'];
		} else {
			$data['nlpostnl_sort_order'] = $this->config->get('nlpostnl_sort_order');
		}

		// Brievenbus formaten
		if (isset($this->request->post['nlpostnl_mailbox_lenght'])) {
			$data['nlpostnl_mailbox_lenght'] = $this->request->post['nlpostnl_mailbox_lenght'];
		} else {
			$data['nlpostnl_mailbox_lenght'] = $this->config->get('nlpostnl_mailbox_lenght');
		}
		
		if (isset($this->request->post['nlpostnl_mailbox_width'])) {
			$data['nlpostnl_mailbox_width'] = $this->request->post['nlpostnl_mailbox_width'];
		} else {
			$data['nlpostnl_mailbox_width'] = $this->config->get('nlpostnl_mailbox_width');
		}
		
		if (isset($this->request->post['nlpostnl_mailbox_height'])) {
			$data['nlpostnl_mailbox_height'] = $this->request->post['nlpostnl_mailbox_height'];
		} else {
			$data['nlpostnl_mailbox_height'] = $this->config->get('nlpostnl_mailbox_height');
		}
		
		if (isset($this->request->post['nlpostnl_mailbox_weight'])) {
			$data['nlpostnl_mailbox_weight'] = $this->request->post['nlpostnl_mailbox_weight'];
		} else {
			$data['nlpostnl_mailbox_weight'] = $this->config->get('nlpostnl_mailbox_weight');
		}


		// Vrzenddoos formaten
		if (isset($this->request->post['nlpostnl_cardboard_box_lenght'])) {
			$data['nlpostnl_cardboard_box_lenght'] = $this->request->post['nlpostnl_cardboard_box_lenght'];
		} else {
			$data['nlpostnl_cardboard_box_lenght'] = $this->config->get('nlpostnl_cardboard_box_lenght');
		}
		
		if (isset($this->request->post['nlpostnl_cardboard_box_width'])) {
			$data['nlpostnl_cardboard_box_width'] = $this->request->post['nlpostnl_cardboard_box_width'];
		} else {
			$data['nlpostnl_cardboard_box_width'] = $this->config->get('nlpostnl_cardboard_box_width');
		}
		
		if (isset($this->request->post['nlpostnl_cardboard_box_height'])) {
			$data['nlpostnl_cardboard_box_height'] = $this->request->post['nlpostnl_cardboard_box_height'];
		} else {
			$data['nlpostnl_cardboard_box_height'] = $this->config->get('nlpostnl_cardboard_box_height');
		}
		
		if (isset($this->request->post['nlpostnl_cardboard_box_weight'])) {
			$data['nlpostnl_cardboard_box_weight'] = $this->request->post['nlpostnl_cardboard_box_weight'];
		} else {
			$data['nlpostnl_cardboard_box_weight'] = $this->config->get('nlpostnl_cardboard_box_weight');
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/shipping/nlpostnl.tpl', $data));
	}
	
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/nlpostnl')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	
}
?>