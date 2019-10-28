<?php

class Halo8FrontendController extends Halo8AbstractFrontendController
{
    private $current_page;

    public function renderView($args = null)
    {
        if (!isset($this->plugin_page_model) || $this->plugin_page_model->getEnabled() === false || $this->plugin_page_model->getEnabled() === 'false') {
            return;
        }
        switch ($this->current_page) {
            case 'post':
                $this->plugin_page_model->initFromPostId(get_option( 'page_for_posts' ));

                if (in_array($args['pageData']->post_type, $this->plugin_global_model->getExcludePostTypes()) || !$this->plugin_page_model->getEnabled()) {
                    return;
                }

                if ($this->plugin_page_model->getSlider()) {
                    $this->slider_post_type_model->initFromPostId($this->plugin_page_model->getSlider());
                } else {
                    $this->slider_post_type_model->initFromPostId($this->plugin_global_model->getSlider());
                }
            break;
            case 'single':
                $this->plugin_page_model->initFromPostId($args['pageData']->ID);

                if (in_array($args['pageData']->post_type, $this->plugin_global_model->getExcludePostTypes()) || !$this->plugin_page_model->getEnabled()) {
                    return;
                }

                if ($this->plugin_page_model->getSlider()) {
                    $this->slider_post_type_model->initFromPostId($this->plugin_page_model->getSlider());
                } else {
                    $this->slider_post_type_model->initFromPostId($this->plugin_global_model->getSlider());
                }
            break;
            case 'taxonomy':
                $option = get_option($this->plugin_page_model->getOptionName());

                if (isset($option[$args['pageData']->term_id])) {
                    $this->plugin_page_model->import($option[$args['pageData']->term_id]);
                }

                if (in_array($args['pageData']->taxonomy, $this->plugin_global_model->getExcludeTaxonomies()) || !$this->plugin_page_model->getEnabled()) {
                    return;
                }

                if ($this->plugin_page_model->getSlider()) {
                    $this->slider_post_type_model->initFromPostId($this->plugin_page_model->getSlider());
                } else {
                    $this->slider_post_type_model->initFromPostId($this->plugin_global_model->getSlider());
                }
            break;
            case 'author':
                $user_id = $args['pageData']->data->ID;
                $current_slider = get_the_author_meta($this->author_model->getOptionName(), $args['pageData']->data->ID);
                if ($current_slider) {
                    $this->slider_post_type_model->initFromPostId($current_slider['slider']);
                }
            break;
            case 'search':
                $this->slider_post_type_model->initFromPostId($this->plugin_page_model->getSlider());
            break;
            case 'archive':
                if(is_post_type_archive(get_post_types(array('_builtin'=>false,'publicly_queryable'=>true)))){

                    global $wp_query;
                    $current = $wp_query->query['post_type'];
                    if(!empty($this->plugin_page_model->getSlider()[$current])){
                        $slider = $this->plugin_page_model->getSlider()[$current];
                    } else {
                        $slider = $this->plugin_page_model->getSlider()['general'];
                    }

                }

                $this->slider_post_type_model->initFromPostId($slider);
            break;
            case '404':

                $this->slider_post_type_model->initFromPostId($this->plugin_page_model->getSlider());
            break;
        }

        $images = $this->slider_post_type_model->getImages();
        if (is_null($images)) {
            return;
        }


        $video_length = 0;

        foreach ($images as &$image) {
            $mime = get_post_mime_type($image['ID']);
            if(in_array($mime,$this->image_mime)){
                $img = wp_get_attachment_image_src($image['ID'], 'full');
                $image['src'] = $img[0];
            } elseif(in_array($mime,$this->video_mime)){
                $image['video']['src'] = wp_get_attachment_url($image['ID']);
                $length = get_post_meta( $image['ID'], '_wp_attachment_metadata', true )['length']*1000;
                if($length>$video_length) $video_length = $length;
            } else {
                continue;
            }

        }
        $options = array();
        $options['preload'] = $this->plugin_global_model->getPreload();
        if ($this->plugin_global_model->getOverlay()) {
            $options['overlay'] = $this->plugin_url.'assets/vegas/overlays/'.$this->plugin_global_model->getOverlay().'.png';
        }
        $options['main_color'] = $this->plugin_global_model->getMainColor();

        // considerare il caso in cui l'impostazione use default non esiste
        $current_model = $this->plugin_page_model;

        $options['position'] = $current_model->getPosition();

        $options['container_selector'] = $current_model->getContainerSelector();

        $options['show_controls'] = $current_model->getShowControls();

        $options['slides'] = $images;

        if ($this->plugin_page_model->getUseDefaultConfig()) {
            $current_model = $this->plugin_global_model;
        }

        if ($current_model->getType() == 'type_transition') {
            $options['transition'] = $current_model->getTransition();
        } else {
            $options['animation'] = $current_model->getAnimation();
        }

        if ($current_model->getType() == 'type_transition') {
            $options['transition­Duration'] = $current_model->getTransitionDuration();
        } else {
            $options['animation­Duration'] = $current_model->getAnimationDuration();
        }

        $delay = $current_model->getDelay();
        $options['delay'] = $delay < $video_length ? $video_length : $delay;

        $options['autoplay'] = $current_model->getAutoplay();

        $options['shuffle'] = $current_model->getShuffle();

        $options['cover'] = $current_model->getCover();

        $options['halign'] = $current_model->getHorizontalAlign();

        $options['valign'] = $current_model->getVerticalAlign();

        $options['timer'] = $current_model->getTimer();

        $this->view->render($options);
    }

    public function enqueueCss()
    {
        wp_enqueue_style('vegas-css', $this->plugin_url.'bower_components/vegas/dist/vegas.min.css');
        wp_enqueue_style('halo8-frontend-css', $this->plugin_url.'css/halo8-frontend.css');
    }

    public function enqueueJs()
    {
        wp_enqueue_script('vegas-js', $this->plugin_url.'bower_components/vegas/dist/vegas.min.js', array('jquery'), '2.4.0');
        wp_enqueue_script('halo8-frontend-js', $this->plugin_url.'js/halo8-frontend.js', array('vegas-js'), '1.0.0');
    }

    public function setCurrentPage($page)
    {
        $this->current_page = $page;
    }

    public function getCurrentPage()
    {
        return $this->current_page;
    }
}
