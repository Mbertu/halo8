<?php
/**
 * Classe astratta necessaria per la registrazione e gestione dei metabox per post, tassonomie e autori
 *
 * @since  1.0.0
 */
abstract class Halo8AbstractMetaboxController extends Halo8AbstractController{

    /**
     * Input field prefix
     * @since  1.0.0
     */
    protected $prefix;

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected function init($args){
    	parent::init($args);
    	return;
    }

    public function enqueueCss(){
        wp_enqueue_style("halo8-slider-metabox-css", $this->plugin_url."css/halo8-slider-metabox.css");
        return;
    }

    public function enqueueJs(){
        wp_enqueue_script("halo8-slider-metabox-javascript", $this->plugin_url."js/halo8-slider-metabox.js", array("jquery"), $this->version);
    }

    /**
     * Genera la lista di elements per il metodo view->renderSelect
     * @since  1.0.0
     */
    protected function generateSlidersSelectElements($args){
        $elements = array();
        foreach($args['sliders'] as $slider){
            $elements[] = array(
                'label' => $slider['title'],
                'value' => $slider['ID'],
                'current' => $args['current'] == $slider['ID'] ? true : false
            );
        }
        return $elements;
    }

    /**
     * Genera la lista di elements per il metodo view->renderSelect
     * @since  1.0.0
     */
    protected function generateSelectElements($args){
        $elements = array();
        foreach($args['options'] as $option){
            $elements[] = array(
                'label' => $option,
                'value' => $option,
                'current' => $args['current'] == $option ? true : false
            );
        }
        return $elements;
    }

    protected function generatePositionsSelectElements($args){
        $elements = array();
        foreach($args['positions'] as $position){
            $elements[] = array(
                'label' => $position,
                'value' => $position,
                'current' => $args['current'] == $position ? true : false
            );
        }
        return $elements;
    }

    protected function isPositionSlideshow($enabled, $position){
        if($enabled){
            if($position == 'slideshow'){
                return true;
            }
        }
        return false;
    }

    /**
     * Check se sono attive le animazioni o le transizioni
     * @since  1.0.0
     */
    protected function isAnimationOrTransition($enabled_default_section, $type, $typeToBe){
        if($enabled_default_section){
            if($type == $typeToBe){
                return true;
            }
        }
        return false;
    }

    public abstract function saveMetadata($args);
}
