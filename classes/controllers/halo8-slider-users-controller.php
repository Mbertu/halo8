<?php
class Halo8SliderUsersController extends Halo8AbstractMetaboxController{

    /**
     * Nonce phrase
     * @since  1.0.0
     */
    private $nonce_phrase;

    /**
     * ID del term corrente
     * @since 1.0.0
     */
    private $user;

    public function init($args){
        parent::init($args);
        $this->prefix = 'halo8_users_slider_input_';
        $this->meta_box_name = 'halo8_users_slider';
        $this->nonce_phrase = 'halo8_users_slider_metabox';
        return;
    }

    /**
     * Render della view pagine edit per post_type slider.
     * @return 1.0.0
     */
    public function renderView(){
        $current_slider = get_the_author_meta( $this->model->getOptionName(), $this->user->data->ID );
        if($current_slider)
            $this->model->import($current_slider);
        else
            $this->model->import(null);

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
                    'type' => 'select',
                    'id' => 'slider',
                    'prefix' => $this->prefix,
                    'name' => $this->model->getOptionName(),
                    'label' => __('Slider', $this->plugin_slug),
                    'elements' => $this->generateSlidersSelectElements(array('sliders' => $this->model->getSliders(), 'current' => $this->model->getSlider())),
                    'message' => __('Select the slider for this user.', $this->plugin_slug),
                    'enabled' => true
                ),
                array(
                    'type' => 'footer'
                )
            )
        );
        $this->view->render($args);
    }

    /**
     * Salvataggio dei tari inseriti nella metabox
     * @since 1.0.0
     */
    public function saveMetadata($args){
        $_post = $args['_post'];

        // Controllo che ci sia un nonce settato
        if ( ! isset( $_post[$this->prefix.'nonce'] ) ) {
            return;
        }

        // Controllo che il nonce sia quello che mi aspetto
        if ( ! wp_verify_nonce( $_post[$this->prefix.'nonce'], $this->nonce_phrase ) ) {
            return;
        }

        if(!current_user_can('edit_user', $args['user_id'])){
            return;
        }

        /* OK, its safe for us to save the data now. */
        if(!isset($_post[$this->model->getOptionName()]))
            return;

        $this->model->import($_post[$this->model->getOptionName()]);

        update_user_meta( $args['user_id'], $this->model->getOptionName(), $this->model->toArray() );
    }

    /**
     * Inserimento o modifica del tipo di post a cui fare riferimento
     * @since 1.0.0
     */
    public function setCurrentUser($user){
        $this->user = $user;
    }
}