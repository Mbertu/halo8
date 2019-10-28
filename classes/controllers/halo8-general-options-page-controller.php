<?php
class Halo8GeneralOptionsPageController extends Halo8AbstractOptionsPageController {

	public function addMenuPage(){
		add_menu_page(  __('Halo8',$this->plugin_slug),
                        __('Halo8',$this->plugin_slug),
                        'manage_options',
                        'halo8_general_options',
                        array( $this, 'renderView' ),
						'',
						108 );
        add_submenu_page( 'halo8_general_options',
                        __('General',$this->plugin_slug),
                        __('General',$this->plugin_slug),
                        'manage_options',
                        'halo8_general_options',
                        array( $this, 'renderView' ) );
	}

    public function setupSettingSection(){
        $setting_name = $this->registerSetting();

        $sections = array();
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_options_section', 'label' => __('Global configuration', $this->plugin_slug));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_overwritten_section', 'label' => __('Overwritten global configuration', $this->plugin_slug));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_generic_slider_section', 'label' => __('Generic slider', $this->plugin_slug));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_exclude_post_type_section', 'label' => __('Exclude Posts Type', $this->plugin_slug));
        $sections[] = array('name' => 'halo8_'.$this->model->getPageName().'_exclude_taxonomies_section', 'label' => __('Exclude Taxonomies', $this->plugin_slug));

        $this->addSettingsSection($sections);

        $fields = array();
        $fields[] = array('id' => 'preload', 'label' => __('Images preload', $this->plugin_slug), 'callback' => array($this, 'checkboxPreload'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'overlay', 'label' => __('Images Overlay', $this->plugin_slug), 'callback' => array($this, 'selectOverlay'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'color', 'label' => __('Main color', $this->plugin_slug), 'callback' => array($this, 'inputColor'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');

        $fields[] = array('id' => 'show_controls', 'label' => __('Show controls', $this->plugin_slug), 'callback' => array($this, 'checkboxShowControls'), 'section_name' => 'halo8_'.$this->model->getPageName().'_enable_section');
        $fields[] = array('id' => 'position', 'label' => __('Slideshow position', $this->plugin_slug), 'callback' => array($this, 'selectPosition'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'container_selector', 'label' => __('Slideshow container selector', $this->plugin_slug), 'callback' => array($this, 'inputContainerSelector'), 'section_name' => 'halo8_'.$this->model->getPageName().'_options_section');
        $fields[] = array('id' => 'type', 'label' => __('Choose if wonna use animations or transitions', $this->plugin_slug), 'callback' => array($this, 'radioType'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'transition', 'label' => __('Transition', $this->plugin_slug), 'callback' => array($this, 'formTransition'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'animation', 'label' => __('Animation', $this->plugin_slug), 'callback' => array($this, 'formAnimation'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'delay', 'label' => __('Delay between animations / transitiosn', $this->plugin_slug), 'callback' => array($this, 'inputDelay'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'autoplay', 'label' => __('Autoplay', $this->plugin_slug), 'callback' => array($this, 'checkboxAutoplay'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'shuffle', 'label' => __('Shuffle', $this->plugin_slug), 'callback' => array($this, 'checkboxShuffle'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'cover', 'label' => __('Cover', $this->plugin_slug), 'callback' => array($this, 'checkboxCover'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'align', 'label' => __('Horizontal align', $this->plugin_slug), 'callback' => array($this, 'selectAlign'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'valign', 'label' => __('Vertical align', $this->plugin_slug), 'callback' => array($this, 'selectValign'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');
        $fields[] = array('id' => 'timer', 'label' => __('Show timer bar', $this->plugin_slug), 'callback' => array($this, 'checkboxTimer'), 'section_name' => 'halo8_'.$this->model->getPageName().'_overwritten_section');

        $fields[] = array('id' => 'generic_slider', 'label' => __('Default slider', $this->plugin_slug), 'callback' => array($this, 'selectSlider'), 'section_name' => 'halo8_'.$this->model->getPageName().'_generic_slider_section');

        $fields[] = array('id' => 'exclude_post_types', 'label' => __('Post Types to exclude', $this->plugin_slug), 'callback' => array($this, 'formExcludePostTypes'), 'section_name' => 'halo8_'.$this->model->getPageName().'_exclude_post_type_section');
        $fields[] = array('id' => 'exclude_taxonomies', 'label' => __('Taxonomies to exclude', $this->plugin_slug), 'callback' => array($this, 'formExcludeTaxonomies'), 'section_name' => 'halo8_'.$this->model->getPageName().'_exclude_taxonomies_section');

        $this->addSettingsField($fields);
    }

    public function validateInput($args){
        $result = parent::validateInput($args);
        $args = $result['args'];
        $errors = $result['errors'];

        if($args['type'] == 'type_transition'){
            if(!isset($args['transition']) || empty($args['transition'])){
                $error[] = __('Inconsistece between type of movements and input fileds.', $this->plugin_slug);
            }
        }else if($args['type'] == 'type_animation'){
            if(!isset($args['animation']) || empty($args['animation'])){
                $error[] = __('Inconsistece between type of movements and input fileds.', $this->plugin_slug);
            }
        }else{
            $error[] = __('Inconsistece between type of movements and input fileds.', $this->plugin_slug);
        }

        // validate all checkboxes
        if(isset($args['preload']))
            $args['preload'] = $this->stringToBool($args['preload']);
        else
            $args['preload'] = false;


        if(isset($args['main_color']) && !empty($args['main_color']) && !$this->validateColorInput($args['main_color'])){
            $errors[] = __('Wrong input fot base color. Only hex values are valid. Ex:#ffffff', $this->plugin_slug);
        }

        if(isset($args['overlay']) && !empty($args['overlay'])){
            if(!$this->validateInputFromArray($args['overlay'],$this->model->getOverlays())){
                $errors[] = __('Invalid overlay.', $this->plugin_slug);
            }
        }

        foreach($errors as $error){
            add_settings_error(
                'halo8_general_options_report',
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

    public function checkboxPreload() {
        $current = $this->model->getPreload();
        $args = array(
            'id' => 'preload',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to activete the preload funciton.',$this->plugin_slug)
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function selectOverlay() {
        $elements = array();
        $elements[] = array(
            'label' => __('none',$this->plugin_slug),
            'value' => '',
            'current' => $this->model->getOverlay()
        );
        foreach($this->model->getOverlays() as $overlay){
            $elements[] = array(
                'label' => $overlay,
                'value' => $overlay,
                'current' => $this->model->getOverlay() == $overlay ? true : false
            );
        }
        $args = array(
            'id' => 'overlay',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Select the overlay pattern to put over the images.',$this->plugin_slug)
        );
        $this->view->renderOverlayPreview($this->model->getOverlay());
        $this->view->renderSelect($args);
        return;
    }

    public function inputColor() {
        $args = array(
            'id' => 'main_color',
            'name' => $this->model->getOptionName(),
            'value' => $this->model->getMainColor(),
            'message' => __('Input the main theme color. es:#ffffff.',$this->plugin_slug),
        );
        $this->view->renderInput($args);
        return;
    }

    /**
     * Lista di checkbox dinamica che include tutti i tipi di post con checkbox per configurare su quali pagine mostrare la form
     * di configurazione di halo8
     * @since 1.0.0
     */
    public function formExcludePostTypes() {
        $post_types = $this->cleanArray(get_post_types(),array('attachment','revision','nav_menu_item','sliders'));
        $exclude_post_types = $this->model->getExcludePostTypes();
        foreach ($post_types as $key => $post_type) {
            $current = isset($exclude_post_types[$key]) ? $exclude_post_types[$key] : false;
            $args = array(
                'id' => $key,
                'name' => $this->model->getOptionName()."[exclude_post_types]",
                'label' => $post_type,
                'checked' => $current,
                'message' => __('Check to disable Halo8 plugin for this post type.', $this->plugin_slug),
                'extra_classes' => array('exclude_posts_container')
            );
            $this->view->renderCheckbox($args);
        }
        return;
    }

    /**
     * Lista di checkbox dinamica che include tutte le tassonomie attive con checkbox per configurare su quali pagine mostrare la form
     * di configurazione di halo8
     * @since 1.0.0
     */
    public function formExcludeTaxonomies() {
        $taxonomies = $this->cleanArray(get_taxonomies(), array('nav_menu'));
        $exclude_taxonomies = $this->model->getExcludeTaxonomies();
        foreach ($taxonomies as $key => $taxonomy) {
            $current = isset($exclude_taxonomies[$key]) ? $exclude_taxonomies[$key] : false;
            $args = array(
                'id' => $key,
                'name' => $this->model->getOptionName()."[exclude_taxonomies]",
                'label' => $taxonomy,
                'checked' => $current,
                'message' => __('Check to disable Halo8 plugin for this taxonomy.', $this->plugin_slug),
                'extra_classes' => array('exclude_taxonomies_container')
            );
            $this->view->renderCheckbox($args);
        }
    }

    /**
     * Renderizza la view richiamando il metodo render dell'oggetto view
     * @since 1.0.0
     */
	public function renderView(){
        $args = array(
            'title'                     => 'General configuration for Halo8 full screen slider plugin.',
            'form_id'                   => 'halo8_'.$this->model->getPageName().'_form',
            'fields'                    => 'halo8_'.$this->model->getPageName().'_options',
            'sections'                  => 'halo8_'.$this->model->getPageName().'_options',
            'permission_error_message'  => __( 'You do not have sufficient permissions to access this page.', $this->plugin_slug )
        );

		$this->view->render($args);
		return;
	}

    private function cleanArray($array, $to_delete){
        if(is_array($to_delete)){
            foreach($to_delete as $element){
                unset($array[$element]);
            }
        }else{
            unset($array, $to_delete);
        }
        return $array;
    }


    public function enqueueJs(){
        parent::enqueueJs();
        wp_enqueue_script("halo8-general-options-page-javascript", $this->plugin_url."js/halo8-general-options-page.js", array("jquery"), $this->version);
        return;
    }

    /**
     * Il metodo genera la risposta alla chiamata ajax che permette il refresh del'anteprima
     * dell'overlay
     * @since 1.0.0
     */
    public function refreshOverlayPreview($selectOverlay){
        if($this->validateInputFromArray($selectOverlay, $this->overlays) || $selectOverlay == ''){
            $this->view->renderOverlayPreview($selectOverlay);
        }
    }
}
