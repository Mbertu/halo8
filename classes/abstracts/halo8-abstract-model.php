<?php
/**
 * Classe astratta per il model del plugin
 * @since  1.0.0
 */
abstract class Halo8AbstractModel {
    /**
     * Plugin version
     * @since  1.0.0
     */
    private $version;

    /**
     * Nome relativo all'opzione del db in cui sono salvati i dati
     * @since  1.0.0
     */
    private $option_name;

    /**
     * Costruttore dichiarato protected per impedire la diretta invocazione
     * @since  1.0.0
     * */
    protected function __construct() {}

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
        $instance = new $c();
        $instance->init($args);
        return $instance;
    }

    /**
     * Metodo che inizializza la classe corrente registrando gli hook necessari per il funzionamento del plugin
     * @since  1.0.0
     * */
    protected abstract function init($args);
    protected abstract function import($args);

    /**
     * Metodo GET per la propietà version
     * @since  1.0.0
     */
    public function getVersion(){
    	return $this->version;
    }

    /**
     * Metodo SET per la propietà version
     * @since  1.0.0
     */
    public function setVersion($version){
    	$this->version = $version;
    }

    /**
     * GET option_name
     */
    public function getOptionName(){
    	return $this->option_name;
    }

    /**
     * SET option_name
     * @since  1.0.0
     */
    public function setOptionName($name){
    	$this->option_name = $name;
    }

    /**
     * Unisce all'array 1 l'array 2 dando priorità ai contenuti dell'array 1 ma sostituendo i valori nulli ed aggiungendo i valori nuovi
     * @since  1.0.0
     */
    public function arrayMerge($array1, $array2){
    	$new_array = array();
    	foreach($array1 as $key => $value){
    		if(!empty($value) || is_bool($value)){
    			$new_array[$key] = $value;
    			continue;
    		}
			if(isset($array2[$key]) && !empty($array2[$key])){
				$new_array[$key] = $array2[$key];
				continue;
			}
			$new_array[$key] = null;
    	}
    	foreach($array2 as $key => $value){
    		if(isset($new_array[$key])){
    			continue;
    		}
    		if(!empty($value) || is_bool($value)){
    			$new_array[$key] = $value;
    			continue;
    		}
			$new_array[$key] = null;
    	}
    	return $new_array;
    }
}