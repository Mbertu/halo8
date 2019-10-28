<?php
class Halo8UsersModel extends Halo8AbstractOptionsModel{

	private $slider;

    private $sliders;

	private $single_options_default = array(
		'slider' => null
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

	/**
     * Carica i valori passati come argomento all'interno dell'istanza corrente del model
     * assumo che i valori in$args siano già validati dal controller
     * @param  array $args valori da caricare nel model
     * @return null
     * @since  1.0.0
     */
    public function import($args){
    	if(isset($args) && !empty($args))
            $filtered = $this->arrayMerge( $args, $this->single_options_default );
        else
            $filtered = $this->single_options_default;
    	foreach ($filtered as $key => $value) {
    		switch($key){
    			case 'slider':
    				$this->setSlider($value);
    			break;
    		}
    	}
    }

    /**
     * Recupera la lista di slider
     * @since 1.0.0
     */
    public function getSliders(){
        return $this->sliders;
    }

    /**
     * Salva nel modello la lista di slider
     * @since 1.0.0
     */
    public function setSliders($sliders){
        $this->sliders = $sliders;
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
			'slider' => $this->getSlider()
		);
    	return $this->arrayMerge($state, $parent_state);
    }

}