<?php

abstract class Halo8AbstractFrontendView {

    /**
     * Plugin path
     * @since  1.0.0
     */
    protected $plugin_url;

    /**
     * Costruttore dichiarato protected per impedire la diretta invocazione
     * @since  1.0.0
     * */
    public function __construct() {}

    /**
     * Metodo per instanziare l'oggetto figlio, implementazione del pattern singleton
     * @since  1.0.0
     * */
    public static function getInstance($calssName, $plugin_url) {
        if (!function_exists('get_called_class')) {
            $c = $calssName;
        }else{
            $c = get_called_class();
        }

        $instance = new $c();
        $instance->init($plugin_url);

        return $instance;
    }

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected function init($plugin_url){
        $this->plugin_url = $plugin_url;
    	return;
    }

    public abstract function render($args=null);
}