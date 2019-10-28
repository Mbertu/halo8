<?php
/**
 * Classe astratta necessaria per la registrazione e gestione dei custom post type
 *
 * @since  1.0.0
 */
abstract class Halo8AbstractPostTypeController extends Halo8AbstractController {

	/**
     * Nome pagina
     * @since 1.0.0
     */
    protected $post_type;

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected function init($args){
    	parent::init($args);
    	return;
    }

}