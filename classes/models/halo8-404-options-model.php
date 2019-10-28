<?php
/**
 * Classe model per le pagina General Options
 * @since  1.0.0
 */
class halo8404OptionsModel extends Halo8AbstractOptionsModel{

	private $slider;
	private $enabled;

	private $general_options_default = array(
		'enabled' => false,
		'slider' => null,
		'404_general' => 'general'
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
        $this->setPageName( isset($args['page_name']) ? $args['page_name'] : null );
        $this->setSliders( isset($args['sliders']) ? $args['sliders'] : null );
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
            $filtered = $this->arrayMerge( $args, $this->general_options_default );
        else
            $filtered = $this->general_options_default;
    	foreach ($filtered as $key => $value) {
    		switch($key){
    			case 'enabled':
    				$this->setEnabled($value);
    			break;
    			case 'slider':
    				$this->setSlider($value);
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
			'slider' => $this->getSlider()
		);
    	return $this->arrayMerge($state, $parent_state);
    }

}