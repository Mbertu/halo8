<?php

class Halo8SliderPostTypeController extends Halo8AbstractPostTypeController{

    /**
     * Nonce per il salvataggio dei meta dat extra
     * @since  1.0.0
     */
    protected $nonce_phrase;

    /**
     * Input field prefix
     * @since  1.0.0
     */
    protected $prefix;

    public function init($args){
        parent::init($args);

        $this->nonce_phrase = 'halo8_slider_input';
        $this->prefix = 'halo8_slider_input_';
        $this->option_name = $this->prefix.'images';

        register_post_type( $this->model->getPostType(),
            array(
                'labels' => array(
                    'name'               => __( 'Sliders', 'post type general name', $this->plugin_slug ),
                    'singular_name'      => __( 'Slider', 'post type singular name', $this->plugin_slug ),
                    'menu_name'          => __( 'Sliders', 'admin menu', $this->plugin_slug ),
                    'name_admin_bar'     => __( 'Slider', 'add new on admin bar', $this->plugin_slug ),
                    'add_new'            => __( 'Add new', 'instructor', $this->plugin_slug ),
                    'add_new_item'       => __( 'Add new slider', $this->plugin_slug ),
                    'new_item'           => __( 'New slider', $this->plugin_slug ),
                    'edit_item'          => __( 'Modify slider', $this->plugin_slug ),
                    'view_item'          => __( 'View slider', $this->plugin_slug ),
                    'all_items'          => __( 'All sliders', $this->plugin_slug ),
                    'search_items'       => __( 'Search slider', $this->plugin_slug ),
                    'not_found'          => __( 'No slider found', $this->plugin_slug ),
                    'not_found_in_trash' => __( 'No slider in the trash', $this->plugin_slug ),
                ),
                'hierarchical' => false,
                'public' => false,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'has_archive' => false,
                'rewrite' => false,
                'supports' => array('title','revisions'),
            )
        );
    }

    /**
     * Render della view pagine edit per post_type slider.
     * @return 1.0.0
     */
    public function renderView(){
        add_meta_box(
            $this->model->getPostType(),
            __('Images for this slider', $this->plugin_slug),
            array( $this, 'renderImageLoader' ),
            $this->model->getPostType(),
            'normal'
        );
    }

    /**
     * Metabox per la pagina di editing degli slider
     * @return 1.0.0
     */
    public function renderImageLoader($current_post){
        $this->model->initFromPostId($current_post->ID);
        $currents = $this->model->getImages();
        if($currents){
            foreach($currents as &$current){
                $mime = get_post_mime_type($current['ID']);
                if(in_array($mime,$this->image_mime)){
                    $current['image'] = wp_get_attachment_image($current['ID']);
                } elseif(in_array($mime,$this->video_mime)){
                    $current['image'] = '<img src="'.$this->plugin_url.'assets/images/video.png"/>';
                } else {
                    continue;
                }
            }
        }else{
            $currents = array();
        }
        $args = array(
            'prefix' => $this->prefix,
            'option_name' => $this->option_name,
            'nonce' => $this->nonce_phrase,
            'currents' => $currents,
            'no_images' => __('No images for this slideshow.', $this->plugin_slug));
        $this->view->render($args);
    }

    /**
     * Metodo che generala la risposta alla chiamata ajax per il render della form di input
     * @return 1.0.0
     */
    public function getSliderMetadataFormForImages($args){
        echo $this->view->inputFormForImage($this->getImagesByIds($args['ids']), $this->option_name, $args['start']);
    }

    public function saveSliderMetadata($post_id, $_post){
        // Controllo che ci sia un nonce settato
        if ( ! isset( $_post[$this->prefix.'nonce'] ) ) {
            return;
        }
        // Controllo che il nonce sia quello che mi aspetto
        if ( ! wp_verify_nonce( $_post[$this->prefix.'nonce'], $this->nonce_phrase ) ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize and save
        $this->model->setImages($_post[$this->option_name]);

        // Update the meta field in the database.
        update_post_meta( $post_id, $this->model->getMetaBoxName(), $this->model->getImages() );
    }

    /**
     * Recupera le immagini utilizzando gli id forniti dal frontend ajax
     * @param  [array] $ids array contentente gli id delle immagini selezionate
     * @return [array]      array contentente le anteprime html per le immagini
     *
     * @since  1.0.0
     */
    private function getImagesByIds($ids) {
        $images = array();
        foreach ( $ids as $id ) {
            $images[] = array(
                'image' => wp_get_attachment_image($id),
                'ID' => $id);
        }
        return $images;
    }

    public function enqueueCss(){
        wp_enqueue_style("halo8-slider-post-type-css", $this->plugin_url."css/halo8-slider-post-type.css");
        return;
    }

    public function enqueueJs(){
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script("halo8-slider-post-type-javascript", $this->plugin_url."js/halo8-slider-post-type.js", array("jquery"), $this->version);
    }
}
