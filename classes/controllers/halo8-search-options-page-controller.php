<?php
class Halo8SearchOptionsPageController extends Halo8AbstractOptionsPageController {

	public function addMenuPage(){
        add_submenu_page( 'halo8_general_options',
                        sprintf(__('Slider %s',$this->plugin_slug),$this->model->getPageName()),
                        sprintf(__('Slider %s',$this->plugin_slug),$this->model->getPageName()),
                        'manage_options',
                        'halo8_'.$this->model->getPageName().'_options',
                        array( $this, 'renderView' ) );
	}

    public function setupSettingSection(){
        $setting_name = $this->registerSetting();

        $sections = array();
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_enable_section', 'label' => sprintf(__('Enable plugin for %s pages', $this->plugin_slug), $this->model->getPageName()));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_options_section', 'label' => sprintf(__('Configurations for %s pages', $this->plugin_slug), $this->model->getPageName()));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_search_slider_section', 'label' => sprintf(__('Slider for %s pages', $this->plugin_slug), $this->model->getPageName()));

        $this->addSettingsSection($sections);

        $fields = array();
        $fields[] = array('id' => 'enabled', 'label' => __('Enable or disabel the plugin for this page', $this->plugin_slug), 'callback' => array($this, 'checkboxEnable'), 'section_name' => 'halo8_'.$this->model->getPageName().'_enable_section');

        $fields[] = array('id' => 'show_controls', 'label' => __('Show controls', $this->plugin_slug), 'callback' => array($this, 'checkboxShowControls'), 'section_name' => 'halo8_'.$this->model->getPageName().'_enable_section');
        $fields[] = array('id' => 'position', 'label' => __('Slideshow position', $this->plugin_slug), 'callback' => array($this, 'selectPosition'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'container_selector', 'label' => __('Slideshow container selector', $this->plugin_slug), 'callback' => array($this, 'inputContainerSelector'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'type', 'label' => __('Choose if wonna use animations or transitions', $this->plugin_slug), 'callback' => array($this, 'radioType'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'transition', 'label' => __('Transition', $this->plugin_slug), 'callback' => array($this, 'formTransition'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'animation', 'label' => __('Animation', $this->plugin_slug), 'callback' => array($this, 'formAnimation'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'delay', 'label' => __('Delay between animations / transitiosn', $this->plugin_slug), 'callback' => array($this, 'inputDelay'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'autoplay', 'label' => __('Autoplay', $this->plugin_slug), 'callback' => array($this, 'checkboxAutoplay'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'shuffle', 'label' => __('Shuffle', $this->plugin_slug), 'callback' => array($this, 'checkboxShuffle'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'cover', 'label' => __('Cover', $this->plugin_slug), 'callback' => array($this, 'checkboxCover'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'align', 'label' => __('Horizontal align', $this->plugin_slug), 'callback' => array($this, 'selectAlign'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'valign', 'label' => __('Vertical align', $this->plugin_slug), 'callback' => array($this, 'selectValign'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'timer', 'label' => __('Show timer bar', $this->plugin_slug), 'callback' => array($this, 'checkboxTimer'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');

        $fields[] = array('id' => 'search_slider', 'label' => __('Search page slider', $this->plugin_slug), 'callback' => array($this, 'selectSlider'), 'section_name' => 'halo8_'.$this->model->getPageName().'_search_slider_section');

        $this->addSettingsField($fields);
    }

    public function validateInput($args){
        $result = parent::validateInput($args);
        $args = $result['args'];
        $errors = $result['errors'];

        // validate all checkboxes
        if(isset($args['enabled'])){
            $args['enabled'] = $this->stringToBool($args['enabled']);
        }else{
            $args['enabled'] = false;
            return $args;
        }

        foreach($errors as $error){
            add_settings_error(
                'halo8_search_options_report',
                esc_attr( 'settings_updated' ),
                $error,
                'error'
            );
        }

        if(empty($errors)){
            $this->model->import($args);
        }
        return $this->model->toArray();
    }

    public function checkboxEnable(){
        $current = $this->model->getEnabled();
        $args = array(
            'id' => 'enabled',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to activate the plugin for this page.',$this->plugin_slug)
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function renderView(){
        $args = array(
            'title'                     => 'Slider for '.$this->model->getPageName().' pages',
            'form_id'                   => 'halo8_'.$this->model->getPageName().'_form',
            'fields'                    => 'halo8_'.$this->model->getPageName().'_options',
            'sections'                  => 'halo8_'.$this->model->getPageName().'_options',
            'permission_error_message'  => __( 'You do not have sufficient permissions to access this page.', $this->plugin_slug )
        );

		$this->view->render($args);
		return;
	}
}
