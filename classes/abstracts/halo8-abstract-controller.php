<?php
abstract class Halo8AbstractController {
	/**
	 * Variabile statica in cui vengono salvate le istanze delle classi figlie di Halo7AddMetaBox
	 * @since  1.0.0
	 */
	private static $instance = array();

    /**
     * Plugin version
     * @since  1.0.0
     */
    protected $version;

	/**
	 * Modello dentro cui si trovano le informazioni relative allo stato attuale del plugin
	 * @since  1.0.0
	 */
	protected $model;

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_slug;

    /**
     * Plugin path
     * @since 1.0.0
     */
    protected $plugin_url;

    /**
     * Oggetto view che visualizza la form di input delle opzioni
     * @since 1.0.0
     */
    protected $view;

    /**
     * Regex per validare colori esadecimali
     * @since  1.0.0
     */
    private $hexcolor_regex = "/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/";

	protected $image_mime = array('image/jpeg','image/png','image/gif');

	protected $video_mime = array('video/mpeg','video/mp4','video/quicktime');


    /**
     * Costruttore dichiarato protected per impedire la diretta invocazione
     * @since  1.0.0
     * */
    public function __construct() {}

    /**
     * Metodo per instanziare l'oggetto figlio, implementazione del pattern singleton
     * @since  1.0.0
     * */
    public static function getInstance($calssName, $args) {
        if (!function_exists('get_called_class')) {
            $c = $calssName;
        }else{
            $c = get_called_class();
        }
        if ( !isset( self::$instance[$c] ) ) {
            self::$instance[$c] = new $c();
            self::$instance[$c]->init($args);
        }
        return self::$instance[$c];
    }

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected function init($args){
        $this->version = isset($args['version']) ? $args['version'] : null;
        $this->model = isset($args['model']) ? $args['model'] : null;
        $this->view = isset($args['view']) ? $args['view'] : null;
        $this->plugin_slug = isset($args['plugin_slug']) ? $args['plugin_slug'] : null;
        $this->plugin_url = isset($args['plugin_url']) ? $args['plugin_url'] : null;
    	return;
    }

    protected function stringToBool($string) {
        return $string == 'true' ? true : false;
    }

    protected function validateInputFromArray($value, $array) {
        return in_array($value, $array);
    }

    protected function validateIneger($number){
        return is_int($number);
    }

    protected function validateColorInput($color){
        if(!preg_match($this->hexcolor_regex, $color)){
            return false;
        }
        return true;
    }

    public abstract function renderView();
    public abstract function enqueueCss();
    public abstract function enqueueJs();
}
