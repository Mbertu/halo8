<?php
class Halo8SliderTaxonomiesController extends Halo8AbstractMetaboxController{

    /**
     * Nonce phrase
     * @since  1.0.0
     */
    private $nonce_phrase;

    /**
     * ID del term corrente
     * @since 1.0.0
     */
    private $term;

    public function init($args){
        parent::init($args);
        $this->prefix = 'halo8_taxonomies_slider_input_';
        $this->meta_box_name = 'halo8_taxonomies_slider';
        $this->nonce_phrase = 'halo8_taxonomy_slider_metabox';
        return;
    }

    /**
     * Render della view pagine edit per post_type slider.
     * @return 1.0.0
     */
    public function renderView(){
        if(isset($this->term) && !empty($this->term))
            $this->getTermMeta( $this->term->term_id, $this->term->taxonomy );
        else
            return;

        $options = get_option($this->model->getOptionName());

        if(isset($options[$this->term->term_id]))
            $this->model->import($options[$this->term->term_id]);
        else
            $this->model->import(null);

        $enabled = $this->model->getEnabled();
        $use_default = $this->model->getUseDefaultConfig();
        $enabled_default_section = $enabled && !$use_default;
        $args = array(
            'elements' => array(
                array(
                    'type' => 'header',
                    'title' => __('Halo8 Full Screen Slider Options', $this->plugin_slug)
                ),
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
                    'checked' => $this->model->getUseDefaultConfig(),
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
                            'checked' => $this->model->getType() == 'type_transition' ? true : false
                        ),
                        array(
                            'label' => __('Animations', $this->plugin_slug),
                            'value' => 'type_animation',
                            'checked' => $this->model->getType() == 'type_animation' ? true : false
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
                ),
                array(
                    'type' => 'footer'
                )
            )
        );
        $this->view->render($args);
    }

    /**
     * Get le meta informazioni per il term corrente
     * @since  1.0.0
     */
    public static function getTermMeta( $term, $taxonomy ) {
        /* Figure out the term id */
        if ( is_int( $term ) ) {
            $term = get_term_by( 'id', $term, $taxonomy );
        }
        elseif ( is_string( $term ) ) {
            $term = get_term_by( 'slug', $term, $taxonomy );
        }
        if ( is_object( $term ) && isset( $term->term_id ) ) {
            $term_id = $term->term_id;
        }
        else {
            return false;
        }
        $option = get_option( $this->model->getOptionName() );
        /* If we have data for the term, merge with defaults for complete array, otherwise set defaults */
        if ( isset( $option[ $term_id ] ) ) {
            $this->model->import($option[ $term_id ]);
        }
    }

    /**
     * Salvataggio dei tari inseriti nella metabox
     * @since 1.0.0
     */
    public function saveMetadata($args){
        $term = $this->term;
        $taxonomy = $args['taxonomy'];
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
        if ( isset( $_post['action'] ) && 'editedtag' == $_post['action'] ) {
            if ( !current_user_can( $taxonomy->cap->edit_terms) ) {
                return;
            }
        }

        /* OK, its safe for us to save the data now. */
        if(!isset($_post[$this->model->getOptionName()]))
            return;

        $this->model->import($_post[$this->model->getOptionName()]);

        $option = get_option($this->model->getOptionName());

        $option[$term->term_id] = $this->model->toArray();

        update_option( $this->model->getOptionName(), $option);
    }

    /**
     * Inserimento o modifica del tipo di post a cui fare riferimento
     * @since 1.0.0
     */
    public function setCurrentTerm($term){
        $this->term = $term;
    }

}
