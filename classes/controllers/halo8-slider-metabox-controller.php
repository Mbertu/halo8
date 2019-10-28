<?php
class Halo8SliderMetaboxController extends Halo8AbstractMetaboxController{

    /**
     * Nonce per il salvataggio dei meta dat extra
     * @since  1.0.0
     */
    protected $nonce_phrase;

    /**
     * Propietà privata che tiene traccia del tipo di post per cui generare la metabox
     * @since  1.0.0
     */
    private $post_type;

    public function init($args){
        parent::init($args);
        $this->nonce_phrase = 'halo8_post_slider_metabox';
        $this->prefix = 'halo8_post_slider_input_';
        return;
    }

    /**
     * Render della view pagine edit per post_type slider.
     * @since 1.0.0
     */
    public function renderView(){
        if(!isset($this->post_type) || empty($this->post_type)){
            return;
        }
        add_meta_box(
            'halo8_'.$this->post_type.'_metabox',
            __('Slider configuration', $this->plugin_slug),
            array( $this, 'renderSliderConfig' ),
            $this->post_type,
            'advanced'
        );
    }

    /**
     * Metabox per la pagina di editing degli slider
     * @since 1.0.0
     */
    public function renderSliderConfig($current_post){
        $this->model->initFromPostId($current_post->ID);

        $enabled = $this->model->getEnabled();
        $use_default = $this->model->getUseDefaultConfig();
        $enabled_default_section = $enabled && !$use_default;
        $args = array(
            'elements' => array(
                array(
                    'type' => 'nonce',
                    'nonce_phrase' => $this->nonce_phrase,
                    'prefix' => $this->prefix
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'enabled',
                    'label' => __('Enabled', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $enabled,
                    'message' => __('Check to activate the plugin for this page.',$this->plugin_slug)
                ),
                array(
                    'type' => 'select',
                    'id' => 'position',
                    'prefix' => $this->prefix,
                    'label' => __('Slideshow position', $this->plugin_slug),
                    'name' => $this->model->getOptionName(),
                    'elements' => $this->generatePositionsSelectElements(array('positions' => $this->model->getPositions(), 'current' => $this->model->getPosition())),
                    'message' => __('Select the position for the slideshow.', $this->plugin_slug),
                    'enabled' => $enabled
                ),
                array(
                    'type' => 'input',
                    'id' => 'container_selector',
                    'label' => __('Slideshow container selector', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'value' => $this->model->getContainerSelector(),
                    'message' => __('Input the css selector for the slideshow container.',$this->plugin_slug),
                    'enabled' => $this->isPositionSlideshow($enabled, $this->model->getPosition()),
                    'extra_classes' => array('container')
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'show_controls',
                    'label' => __('Show controls', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $this->model->getShowControls(),
                    'message' => __('Check to show controls arrow.',$this->plugin_slug),
                    'enabled' => $enabled
                ),
                array(
                    'type' => 'select',
                    'id' => 'slider',
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'label' => __('Slider', $this->plugin_slug),
                    'elements' => $this->generateSlidersSelectElements(array('sliders' => $this->model->getSliders(), 'current' => $this->model->getSlider())),
                    'message' => __('Select the slider for this page.', $this->plugin_slug),
                    'enabled' => $enabled
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'use_default_config',
                    'label' => __('Default', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $use_default,
                    'message' => __('Check to use the default configurations.',$this->plugin_slug),
                    'enabled' => $enabled
                ),
                array(
                    'type' => 'radio',
                    'id' => 'type',
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'elements' => array(
                        array(
                            'label' => __('Transitions', $this->plugin_slug),
                            'value' => 'type_transition',
                            'checked' => 'type_transition' == $this->model->getType()
                        ),
                        array(
                            'label' => __('Animations', $this->plugin_slug),
                            'value' => 'type_animation',
                            'checked' => 'type_animation' == $this->model->getType()
                        )
                    ),
                    'message' => __('Choose between animation or transition for slide change montion.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'select',
                    'id' => 'transition',
                    'label' => __('Transition', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'elements' => $this->generateSelectElements(array('options' => $this->model->getTransitions(), 'current' => $this->model->getTransition())),
                    'message' => __('Select the transition effect.',$this->plugin_slug),
                    'enabled' => $this->isAnimationOrTransition($enabled_default_section, $this->model->getType(), 'type_transition'),
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'input',
                    'id' => 'transition_duration',
                    'label' => __('Transition duraiton', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'value' => $this->model->getTransitionDuration(),
                    'message' => __('Input the transition duration.',$this->plugin_slug),
                    'enabled' => $this->isAnimationOrTransition($enabled_default_section, $this->model->getType(), 'type_transition'),
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'select',
                    'id' => 'animation',
                    'label' => __('Animation', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'elements' => $this->generateSelectElements(array('options' => $this->model->getAnimations(), 'current' => $this->model->getAnimation())),
                    'message' => __('Select the animation effect.',$this->plugin_slug),
                    'enabled' => $this->isAnimationOrTransition($enabled_default_section, $this->model->getType(), 'type_animation'),
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'input',
                    'id' => 'animation_duration',
                    'label' => __('Animation duration', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'value' => $this->model->getAnimationDuration(),
                    'message' => __('Input the animation duration.',$this->plugin_slug),
                    'enabled' => $this->isAnimationOrTransition($enabled_default_section, $this->model->getType(), 'type_animation'),
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'input',
                    'id' => 'delay',
                    'label' => __('Delay', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'value' => $this->model->getDelay(),
                    'message' => __('Delay between animation/transitions.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'autoplay',
                    'label' => __('Autoplay', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $this->model->getAutoplay(),
                    'message' => __('Check to activate autoplay.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'shuffle',
                    'label' => __('Shuffle', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $this->model->getShuffle(),
                    'message' => __('Check to activate random order.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'cover',
                    'label' => __('Cover', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $this->model->getCover(),
                    'message' => __('Check to use cover background css3 property.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'select',
                    'id' => 'horizontal_align',
                    'label' => __('Horizontal Align', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'elements' => $this->generateSelectElements(array('options' => $this->model->getAligns(), 'current' => $this->model->getHorizontalAlign())),
                    'message' => __('Select the value for horizontal align.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'select',
                    'id' => 'vertical_align',
                    'label' => __('Vertical Align', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'elements' => $this->generateSelectElements(array('options' => $this->model->getAligns(), 'current' => $this->model->getVerticalAlign())),
                    'message' => __('Select the value for vertical align.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                ),
                array(
                    'type' => 'checkbox',
                    'id' => 'timer',
                    'label' => __('Timer', $this->plugin_slug),
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'checked' => $this->model->getTimer(),
                    'message' => __('Check to use cover background css3 property.',$this->plugin_slug),
                    'enabled' => $enabled_default_section,
                    'extra_classes' => array('default')
                )
            )
        );
        $this->view->render($args);
    }



    /**
     * Check se le transizioni sono attive
     * @since  1.0.0
     */
    private function isMontionActive($type){
        $enabled = false;
        $current_type = $this->model->getType();
        if($this->model->getEnabled() && !$this->model->getUseDefaultConfig()){
            $enabled = $current_type == $type ? true : false;
        }else{
            $enabled = false;
        }
        return $enabled;
    }

    /**
     * Salvataggio dei tari inseriti nella metabox
     * @since 1.0.0
     */
    public function saveMetadata($args){
        $post_id = $args['post_id'];
        $_post = $args['_post'];
        // Controllo che ci sia un nonce settato
        if ( ! isset( $_post[$this->prefix.'nonce'] ) ) {
            return;
        }
        // Controllo che il nonce sia quello che mi aspetto
        if ( ! wp_verify_nonce( $_post[$this->prefix.'nonce'], $this->nonce_phrase ) ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_post['post_type'] ) && ('page' == $_post['post_type'] || 'post' == $_post['post_type'])) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        }

        /* OK, its safe for us to save the data now. */
        if(isset($_post['container_selector']))
            $_post['container_selector'] = sanitize_text_field($_post['container_selector']);

        if(isset($_post[$this->model->getOptionName()])){
            $this->model->import($_post[$this->model->getOptionName()]);
        }
        // Update the meta field in the database.
        update_post_meta( $post_id, $this->model->getOptionName(), $this->model->toArray() );
    }

    /**
     * Inserimento o modifica del tipo di post a cui fare riferimento
     * @since 1.0.0
     */
    public function setPostType($type){
        $this->post_type = $type;
    }

}
