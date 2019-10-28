<?php
abstract class Halo8AbstractOptionsPageController extends Halo8AbstractController {

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected function init($args){
    	parent::init($args);
    	return;
    }

    protected function registerSetting(){
        $name = 'halo8_'.$this->model->getPageName().'_options';
        register_setting( $name, $this->model->getOptionName(), array($this, 'validateInput'));
        return $name;
    }

    protected function addSettingsSection($sections) {
        foreach($sections as $section){
            add_settings_section(
                $section['name'],
                $section['label'],
                null,
                'halo8_'.$this->model->getPageName().'_options'
            );
        }
    }

    protected function addSettingsField($fields){
        foreach($fields as $field){
            add_settings_field(
                $field['id'],
                $field['label'],
                $field['callback'],
                'halo8_'.$this->model->getPageName().'_options',
                $field['section_name']
            );
        }
    }

    public function validateInput($args){
        $errors = array();

        // validate all checkboxes
        if(isset($args['cover']))
            $args['cover'] = $this->stringToBool($args['cover']);
        else
            $args['cover'] = false;

        if(isset($args['shuffle']))
            $args['shuffle'] = $this->stringToBool($args['shuffle']);
        else
            $args['shuffle'] = false;

        if(isset($args['autoplay']))
            $args['autoplay'] = $this->stringToBool($args['autoplay']);
        else
            $args['autoplay'] = false;

        if(isset($args['timer']))
            $args['timer'] = $this->stringToBool($args['timer']);
        else
            $args['timer'] = false;

        if(isset($args['container_selector']))
            $args['container_selector'] = sanitize_text_field($args['container_selector']);

        if(isset($args['animation'])){
            if(!$this->validateInputFromArray($args['animation'],$this->model->getAnimations())){
                $errors[] = __('Invalid animation.', $this->plugin_slug);
            }
        }

        if(isset($args['transition'])){
            if(!$this->validateInputFromArray($args['transition'],$this->model->getTransitions())){
                $errors[] = __('Invalid transition.', $this->plugin_slug);
            }
        }

        if(isset($args['align'])){
            if(!$this->validateInputFromArray($args['align'],$this->model->getAligns())){
                $errors[] = __('Invalid horizontal align.', $this->plugin_slug);
            }
        }

        if(isset($args['valign'])){
            if(!$this->validateInputFromArray($args['valign'],$this->model->getAligns())){
                $errors[] = __('Invalid vertical align.', $this->plugin_slug);
            }
        }

        if(isset($args['position'])){
            if(!$this->validateInputFromArray($args['position'],$this->model->getPositions())){
                $errors[] = __('Invalid position.', $this->plugin_slug);
            }
        }

        if(isset($args['animation_duration']) && !empty($args['animation_duration'])){
            $args['animation_duration'] = intval($args['animation_duration']);
            if(!$this->validateIneger($args['animation_duration'])){
                $errors[] = __('Animation duration must be an integer.', $this->plugin_slug);
            }else{
                if($args['animation_duration'] < 100){
                    $errors[] = __('Animation duration is milliseconds value, your input is too small.', $this->plugin_slug);
                }
            }
        }

        if(isset($args['transition_duration']) && !empty($args['transition_duration'])){
            $args['transition_duration'] = intval($args['transition_duration']);
            if(!$this->validateIneger($args['transition_duration'])){
                $errors[] = __('Transition duration must be an integer.', $this->plugin_slug);
            }else{
                if($args['transition_duration'] < 100){
                    $errors[] = __('Transition duration is milliseconds value, your input is too small.', $this->plugin_slug);
                }
            }
        }

        if(isset($args['delay']) && !empty($args['delay'])){
            $args['delay'] = intval($args['delay']);
            if(!$this->validateIneger($args['delay'])){
                $errors[] = __('Delay must be an integer.', $this->plugin_slug);
            }else{
                if($args['delay'] < 100){
                    $errors[] = __('Delay is milliseconds value, your input is too small.', $this->plugin_slug);
                }
            }
        }

        return array('args' => $args, 'errors' => $errors);
    }

    public function radioType() {
        $current_type = $this->model->getType();
        $enabled = $this->model->getEnabled();
        $elements = array(
            array(
                'label' => __('Transitions', $this->plugin_slug),
                'value' => 'type_transition',
                'checked' => $current_type == 'type_transition' ? true : false
            ),
            array(
                'label' => __('Animations', $this->plugin_slug),
                'value' => 'type_animation',
                'checked' => $current_type == 'type_animation' ? true : false
            )
        );
        $args = array(
            'id' => 'type',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Choose between animation or transition for slide change montion.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderRadio($args);
        return;
    }

    public function formAnimation() {
        $current_type = $this->model->getType();

        if($this->model->getEnabled()){
            $enabled = $current_type == 'type_animation' ? true : false;
        }else{
            $enabled = false;
        }

        $current = $this->model->getAnimation();
        $elements = array();
        foreach($this->model->getAnimations() as $animation){
            $elements[] = array(
                'label' => $animation,
                'value' => $animation,
                'current' => $current == $animation ? true : false
            );
        }

        $args = array(
            'id' => 'animation',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'label' => __('Choose animation',$this->plugin_slug),
            'enabled' => $enabled,
            'message' => __('Choose the animation for the slides.',$this->plugin_slug)
        );

        $this->view->renderSelect($args);

        $current = $this->model->getAnimationDuration();
        $args2 = array(
            'id' => 'animation_duration',
            'name' => $this->model->getOptionName(),
            'value' => $current,
            'label' => __('Animation duration',$this->plugin_slug),
            'enabled' => $enabled,
            'message' => __('Choose the animation duration in milliseconds. Default 5000',$this->plugin_slug)
        );
        $this->view->renderInput($args2);

        return;
    }

    public function formTransition() {
        $current_type = $this->model->getType();

        if($this->model->getEnabled()){
            $enabled = $current_type == 'type_transition' ? true : false;
        }else{
            $enabled = false;
        }

        $current = $this->model->getTransition();
        $elements = array();
        foreach($this->model->getTransitions() as $transition){
            $elements[] = array(
                'label' => $transition,
                'value' => $transition,
                'current' => $current == $transition ? true : false
            );
        }
        $args = array(
            'id' => 'transition',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'label' => __('Choose transition',$this->plugin_slug),
            'enabled' => $enabled,
            'message' => __('Choose the transition for the slides.',$this->plugin_slug)
        );
        $this->view->renderSelect($args);

        $current = $this->model->getTransitionDuration();
        $args2 = array(
            'id' => 'transition_duration',
            'name' => $this->model->getOptionName(),
            'value' => $current,
            'label' => __('Transition duration',$this->plugin_slug),
            'enabled' => $enabled,
            'message' => __('Choose the transition duration in milliseconds. Default 1000',$this->plugin_slug)
        );
        $this->view->renderInput($args2);
        return;
    }

    public function inputDelay() {
        $current = $this->model->getDelay();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id' => 'delay',
            'name' => $this->model->getOptionName(),
            'value' => $current,
            'message' => __('Choose the delay between animations or transitions in milliseconds. Default 5000',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderInput($args);
        return;
    }

    public function checkboxShuffle() {
        $current = $this->model->getShuffle();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id' => 'shuffle',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to activate the shuffle mode.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function checkboxShowControls() {
        $current = $this->model->getShowControls();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id' => 'show_controls',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to show controls arrow.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function checkboxCover() {
        $current = $this->model->getCover();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id' => 'cover',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to activate the CSS3 background option cover for the background images.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function checkboxAutoplay() {
        $current = $this->model->getAutoplay();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id' => 'autoplay',
            'name' => $this->model->getOptionName(),
            'checked' => $current,
            'message' => __('Check to activate the autoplay mode.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function selectAlign() {
        $current = $this->model->getHorizontalAlign();
        $enabled = $this->model->getEnabled();
        $elements = array();
        foreach($this->model->getAligns() as $align){
            $elements[] = array(
                'label' => $align,
                'value' => $align,
                'current' => $current == $align ? true : false
            );
        }
        $args = array(
            'id' => 'horizontal_align',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Background horizontal alignment.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderSelect($args);
        return;
    }

    public function selectValign() {
        $current = $this->model->getVerticalAlign();
        $enabled = $this->model->getEnabled();
        $elements = array();
        foreach($this->model->getAligns() as $align){
            $elements[] = array(
                'label' => $align,
                'value' => $align,
                'current' => $current == $align ? true : false
            );
        }
        $args = array(
            'id' => 'vertical_align',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Background vertical alignment.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderSelect($args);
        return;
    }

    public function checkboxTimer() {
        $current = $this->model->getTimer();
        $enabled = $this->model->getEnabled();
        $args = array(
            'id'=>'timer',
            'name'=>$this->model->getOptionName(),
            'checked'=>$current,
            'message' => __('Check to show the timer bar.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderCheckbox($args);
        return;
    }

    public function selectSlider() {
        if(!$this->model->getSliders())
            return;
        $current = $this->model->getSlider();
        $enabled = $this->model->getEnabled();
        $elements = array();
        foreach($this->model->getSliders() as $slider){
            $elements[] = array(
                'label' => $slider['title'],
                'value' => $slider['ID'],
                'current' => $current == $slider['ID'] ? true : false
            );
        }
        $args = array(
            'id' => 'slider',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Images slider selection.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderSelect($args);
        return;
    }

    public function selectPosition() {
        $current = $this->model->getPosition();
        $enabled = $this->model->getEnabled();
        $elements = array();
        foreach($this->model->getPositions() as $position){
            $elements[] = array(
                'label' => $position,
                'value' => $position,
                'current' => $current == $position ? true : false
            );
        }
        $args = array(
            'id' => 'position',
            'name' => $this->model->getOptionName(),
            'elements' => $elements,
            'message' => __('Slideshow position.',$this->plugin_slug),
            'enabled' => $enabled
        );
        $this->view->renderSelect($args);
        return;
    }

    public function inputContainerSelector() {
        $current = $this->model->getContainerSelector();
        if($this->model->getEnabled()){
            $enabled = $this->model->getPosition() == 'slideshow' ? true : false;
        }else{
            $enabled = false;
        }

        $elements = array();
        $args = array(
            'id' => 'container_selector',
            'name' => $this->model->getOptionName(),
            'value' => $current,
            'message' => __('Choose the slideshow container\'s selector.',$this->plugin_slug),
            'enabled' => $enabled,
            'extra_classes' => array('container')
        );
        $this->view->renderInput($args);
        return;
    }

    public function enqueueCss(){
        wp_enqueue_style("halo8-options-page-css", $this->plugin_url."css/halo8-options-page.css");
        return;
    }

    public function enqueueJs(){
        wp_enqueue_script("halo8-options-page-javascript", $this->plugin_url."js/halo8-options-page.js", array("jquery"), $this->version);
        return;
    }

    public abstract function addMenuPage();
    public abstract function setupSettingSection();
}
