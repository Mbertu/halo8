<?php
/**
 * Classe model per le pagina General Options
 * @since  1.0.0
 */
class Halo8SingleOptionsModel extends Halo8AbstractOptionsModel{

	// Variabili contenenti le possibli opzioni
	private $positions = array('background', 'slideshow');

	private $enabled;
	private $slider;
	private $use_default_config;

	private $single_options_default = array(
		'enabled' => false,
		'slider' => null,
		'use_default_config' => false
	);

	/**
	 * Metodo che inizializza il model con i parametri passati in input o con quelli di default
	 * @param  array $args valor vari con cui inizializzare l'oggetto
	 * @return null
	 * @since  1.0.0
	 */
	protected function init($args){
		$this->setVersion( isset($args['version']) ? $args['version'] : null );
		$this->setOptionName( isset($args['option_name']) ? $args['option_name'] : null );
        $this->setSliders( isset($args['sliders']) ? $args['sliders'] : null );
	}

	public function initFromPostId($post_id){
		$this->import(get_post_meta( $post_id, $this->getOptionName(), true ));
	}

	/**
     * Carica i valori passati come argomento all'interno dell'istanza corrente del model
     * assumo che i valori in$args siano già validati dal controller
     * @param  array $args valori da caricare nel model
     * @return null
     * @since  1.0.0
     */
    public function import($args){
    	parent::import($args);
    	if(isset($args) && !empty($args))
            $filtered = $this->arrayMerge( $args, $this->single_options_default );
        else
            $filtered = $this->single_options_default;
    	foreach ($filtered as $key => $value) {
    		switch($key){
    			case 'enabled':
    				$this->setEnabled($value);
    			break;
    			case 'slider':
    				$this->setSlider($value);
    			break;
    			case 'use_default_config':
    				$this->setUseDefaultConfig($value);
    			break;
    		}
    	}
    }

    public function getEnabled(){
		return $this->enabled;
	}

	public function setEnabled($enabled){
		$this->enabled = $enabled;
	}

	public function getUseDefaultConfig(){
		return $this->use_default_config;
	}

	public function setUseDefaultConfig($use_default){
		$this->use_default_config = $use_default;
	}

	public function getSlider(){
		return $this->slider;
	}

	public function setSlider($slider){
		$this->slider = $slider;
	}

    public function toArray(){
    	$parent_state = parent::toArray();
    	$state = array(
			'enabled' => $this->getEnabled(),
			'slider' => $this->getSlider(),
			'use_default_config' => $this->getUseDefaultConfig()
		);
    	return $this->arrayMerge($state, $parent_state);
    }

}
