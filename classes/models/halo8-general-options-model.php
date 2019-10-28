<?php
/**
 * Classe model per le pagina General Options
 * @since  1.0.0
 */
class halo8GeneralOptionsModel extends Halo8AbstractOptionsModel{

	private $overlay;
	private $preload;
	private $main_color;
	private $slider;
	private $exclude_taxonomies;
	private $exclude_post_types;

	/**
     * Lista di overlay
     * @since 1.0.0
     */
    private $overlays = array('01','02','03','04','05','06','07','08','09');

	private $general_options_default = array(
		'overlay' => null,
		'preload' => false,
		'main_color' => '#ffffff',
		'slider' => null,
		'exclude_post_types' => array(),
		'exclude_taxonomies' => array()
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
    			case 'overlay':
    				$this->setOverlay($value);
    			break;
    			case 'preload':
    				$this->setPreload($value);
    			break;
    			case 'main_color':
    				$this->setMainColor($value);
    			break;
    			case 'slider':
    				$this->setSlider($value);
    			break;
    			case 'exclude_post_types':
                    if(is_null($value))
                        $this->setExcludePostTypes($this->general_options_default[$key]);
                    else
    				    $this->setExcludePostTypes($value);
    			break;
    			case 'exclude_taxonomies':
    				if(is_null($value))
                        $this->setExcludetaxonomies($this->general_options_default[$key]);
                    else
                        $this->setExcludetaxonomies($value);
    			break;
    		}
    	}
    }

	public function getOverlay(){
		return $this->overlay;
	}
	public function setOverlay($overlay){
		$this->overlay = $overlay;
	}
	public function getPreload(){
		return $this->preload;
	}
	public function setPreload($preload){
		if(!is_bool($preload))
			$preload = $preload == 'true' ? true : false;
		$this->preload = $preload;
	}
	public function getMainColor(){
		return $this->main_color;
	}
	public function setMainColor($color){
		$this->main_color = $color;
	}
	public function getSlider(){
		return $this->slider;
	}
	public function setSlider($slider){
		$this->slider = $slider;
	}
	public function getExcludePostTypes(){
		return $this->exclude_post_types;
	}
	public function setExcludePostTypes($posts){
		$this->exclude_post_types = $posts;
	}
	public function getExcludeTaxonomies(){
		return $this->exclude_taxonomies;
	}
	public function setExcludetaxonomies($taxonomies){
		$this->exclude_taxonomies = $taxonomies;
	}

	/**
     * GET dei possibili valori relativi agli overlays
     * @since  1.0.0
     */
    public function getOverlays(){
    	return $this->overlays;
    }

    public function getEnabled(){
    	return true;
    }

    public function toArray(){
    	$parent_state = parent::toArray();
    	$state = array(
	    	'overlay' => $this->getOverlay(),
			'preload' => $this->getPreload(),
			'main_color' => $this->getMainColor(),
			'slider' => $this->getSlider(),
			'exclude_post_types' => $this->getExcludePostTypes(),
			'exclude_taxonomies' => $this->getExcludeTaxonomies()
		);
    	return $this->arrayMerge($state, $parent_state);
    }
}
