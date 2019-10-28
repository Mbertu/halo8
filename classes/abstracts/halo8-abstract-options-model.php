<?php
/**
 * Classe astratta model per le pagine di configurazione
 * @since  1.0.0
 */
abstract class Halo8AbstractOptionsModel extends Halo8AbstractModel {

	/**
     * Tipo di movimento animazione / transizione
     * @since  1.0.0
     */
    private $type;

    /**
     * Tipo di transizione
     * @since  1.0.0
     */
    private $transition;

    /**
     * Durata transizione
     * @since  1.0.0
     */
    private $transition_duration;

    /**
     * Tipo di animazione
     * @since  1.0.0
     */
    private $animation;

    /**
     * Durata transizione
     * @since  1.0.0
     */
    private $animation_duration;

    /**
     * Tempo di attesa tra 2 movimenti
     * @since  1.0.0
     */
    private $delay;

    /**
     * Autoplay
     * @since  1.0.0
     */
    private $autoplay;

    /**
     * Shuffle
     * @since  1.0.0
     */
    private $shuffle;

    /**
     * Cover
     * @var  boolean
     * @since  1.0.0
     */
    private $cover;

    /**
     * Allineamento orizzontale dell'immagine di background
     * @since  1.0.0
     */
    private $horizontal_align;

    /**
     * Allineamento verticale dell'immagine di background
     * @since  1.0.0
     */
    private $vertical_align;

    /**
     * Mostra o nasconde la timebar
     * @since  1.0.0
     */
    private $timer;

    /**
     * Lista di sliders
     * @since  1.0.0
     */
    private $sliders;

    /**
     * Posizioni dello slider nella pagina, background o slideshow
     * @since  1.0.0
     */
    private $position;

    /**
     * Slideshow container selector
     * @since  1.0.0
     */
    private $container_selector;

    /**
     * Show or hide the slideshow controllers
     * @since  1.0.0
     */
    protected $show_controls;

    /**
     * Opzioni per possibili posizioni
     * @since  1.0.0
     */
    private $positions = array('background','slideshow');

    /**
     * Lista di transitions
     * @since 1.0.0
     */
    private $transitions = array('random',
                                'fade',
                                'fade2',
                                'slideLeft',
                                'slideLeft2',
                                'slideRight',
                                'slideLeft2',
                                'slideUp',
                                'slideUp2',
                                'slideDown',
                                'slideDown2',
                                'zoomIn',
                                'zoomIn2',
                                'zoomOut',
                                'zoomOut2',
                                'swirlLeft',
                                'swirlLeft2',
                                'swirlRight',
                                'swirlRight2',
                                'burn',
                                'burn2',
                                'blur',
                                'blur2',
                                'flash',
                                'flash2');

    /**
     * Lista di overlay
     * @since 1.0.0
     */
    private $animations = array('random',
                                'kenburns',
                                'kenburnsUp',
                                'kenburnsDown',
                                'kenburnsLeft',
                                'kenburnsRight',
                                'kenburnsUpLeft',
                                'kenburnsUpRight',
                                'kenburnsDownLeft',
                                'kenburnsDownRight');

    /**
     * Lista allineamenti
     * @since  1.0.0
     */
    private $aligns = array('center','left','right','top','bottom');

    /**
     * Nome della pagina corrente
     * @since  1.0.0
     */
    private $page_name;

    /**
     * Default options
     * @since  1.0.0
     */
    private $defaults = array(
    	'type' => 'type_transition',
    	'transition' => 'random',
    	'transition_duration' => '1000',
    	'animation' => 'random',
    	'animation_duration' => '5000',
    	'delay' => '5000',
    	'autoplay' => false,
    	'shuffle' => false,
    	'cover' => false,
    	'horizontal_align' => 'center',
    	'vertical_align' => 'center',
    	'timer' => false,
        'position' => 'background',
        'container_selector' => '#halo8_container',
        'show_controls' => false,
    );

    /**
     * Carica i valori passati come argomento all'interno dell'istanza corrente del model
     * assumo che i valori in$args siano già validati dal controller
     * @param  array $args valori da caricare nel model
     * @return null
     * @since  1.0.0
     */
    public function import($args){
        if(isset($args) && !empty($args))
            $filtered = $this->arrayMerge( $args, $this->defaults );
        else
            $filtered = $this->defaults;
    	foreach ($filtered as $key => $value) {
    		switch($key){
    			case 'type':
    				$this->setType($value);
                    break;
    			case 'transition':
    				$this->setTransition($value);
                    break;
    			case 'transition_duration':
    				$this->setTransitionDuration($value);
                    break;
    			case 'animation':
    				$this->setAnimation($value);
                    break;
    			case 'animation_duration':
    				$this->setAnimationDuration($value);
                    break;
    			case 'delay':
    				$this->setDelay($value);
                    break;
    			case 'autoplay':
    				$this->setAutoplay($value);
                    break;
    			case 'shuffle':
    				$this->setShuffle($value);
                    break;
    			case 'cover':
    				$this->setCover($value);
                    break;
    			case 'horizontal_align':
    				$this->sethorizontalAlign($value);
                    break;
    			case 'vertical_align':
    				$this->setVerticalAlign($value);
                    break;
    			case 'timer':
    				$this->setTimer($value);
                    break;
                case 'position':
                    $this->setPosition($value);
                    break;
                case 'container_selector':
                    $this->setContainerSelector($value);
                    break;
                case 'show_controls':
                    $this->setShowControls($value);
                    break;
    		}
    	}
    }

    public function toArray(){
    	$state = array(
	    	'type' => $this->getType(),
	    	'transition' => $this->getTransition(),
	    	'transition_duration' => $this->getTransitionDuration(),
	    	'animation' => $this->getAnimation(),
	    	'animation_duration' => $this->getAnimationDuration(),
	    	'delay' => $this->getDelay(),
	    	'autoplay' => $this->getAutoplay(),
	    	'shuffle' => $this->getShuffle(),
	    	'cover' => $this->getCover(),
	    	'horizontal_align' => $this->getHorizontalAlign(),
	    	'vertical_align' => $this->getVerticalAlign(),
	    	'timer' => $this->getTimer(),
            'position' => $this->getPosition(),
            'container_selector' => $this->getContainerSelector(),
            'show_controls' => $this->getShowControls()
    	);
    	return $state;
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

    /**
     * Metodo GET per il tipo di movimento
     * @return String animation / transition
     * @since  1.0.0
     */
    public function getType(){
    	return $this->type;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di moviemento
     * @param string $type animation / transition
     */
    public function setType($type){
    	$this->type = $type;
    }

    /**
     * Metodo GET per il tipo di transizione selezionato
     * @return String nome transizione
     * @since  1.0.0
     */
    public function getTransition(){
    	return $this->transition;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di transizione
     * @param string $type nome transizione
     * @since  1.0.0
     */
    public function setTransition($transition){
    	$this->transition = $transition;
    }

    /**
     * Metodo GET per la durata transizione
     * @return int durata della transizione
     * @since  1.0.0
     */
    public function getTransitionDuration(){
    	return $this->transition_duration;
    }

    /**
     * Imposta la variabile d'istanza relativa alla durata della transizione
     * @param string $type animation / transition
     * @since  1.0.0
     */
    public function setTransitionDuration($duration){
    	$this->transition_duration = $duration;
    }

    /**
     * Metodo GET per il tipo di animazione
     * @return String nome animation
     * @since  1.0.0
     */
    public function getAnimation(){
    	return $this->animation;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di animazione
     * @param int durata dell'animazione
     */
    public function setAnimation($animation){
    	$this->animation = $animation;
    }

    /**
     * Metodo GET per il tipo di animazione
     * @return String nome animation
     * @since  1.0.0
     */
    public function getAnimationDuration(){
    	return $this->animation_duration;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di animazione
     * @param int durata dell'animazione
     */
    public function setAnimationDuration($duration){
    	$this->animation_duration = $duration;
    }

    /**
     * Metodo GET per il tipo di animazione
     * @return String nome animation
     * @since  1.0.0
     */
    public function getDelay(){
    	return $this->delay;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di animazione
     * @param int durata dell'animazione
     */
    public function setDelay($delay){
    	$this->delay = $delay;
    }

    /**
     * Metodo GET per il tipo di animazione
     * @return String nome animation
     * @since  1.0.0
     */
    public function getAutoplay(){
    	return $this->autoplay;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di animazione
     * @param int durata dell'animazione
     */
    public function setAutoplay($autoplay){
    	if(!is_bool($autoplay))
			$autoplay = ($autoplay == 'true' ? true : false);
    	$this->autoplay = $autoplay;
    }

    /**
     * Metodo GET per il tipo di animazione
     * @return String nome animation
     * @since  1.0.0
     */
    public function getShuffle(){
    	return $this->shuffle;
    }

    /**
     * Imposta la variabile d'istanza relativa al tipo di animazione
     * @param int durata dell'animazione
     */
    public function setShuffle($shuffle){
    	if(!is_bool($shuffle))
			$shuffle = ($shuffle == 'true' ? true : false);
    	$this->shuffle = $shuffle;
    }

    /**
     * Metodo GET per l'opzione cover
     * @return String nome animation
     * @since  1.0.0
     */
    public function getCover(){
    	return $this->cover;
    }

    /**
     * Imposta la variabile d'istanza relativa al opzione cover
     * @param int durata dell'animazione
     * @since  1.0.0
     */
    public function setCover($cover){
    	if(!is_bool($cover))
			$cover = $cover == 'true' ? true : false;
    	$this->cover = $cover;
    }

    /**
     * Metodo GET per l'opzione di allineamento orizzontale dello sfondo
     * @return String nome animation
     * @since  1.0.0
     */
    public function getHorizontalAlign(){
    	return $this->horizontal_align;
    }

    /**
     * SET dell'opzione di allineamento orizzontale dello sfondo
     * @param string $halign tipo di allineamento orizzontale
     * @since  1.0.0
     */
    public function setHorizontalAlign($halign){
    	$this->horizontal_align = $halign;
    }

    /**
     * Metodo GET per il tipo allineamento verticale
     * @return String nome animation
     * @since  1.0.0
     */
    public function getVerticalAlign(){
    	return $this->vertical_align;
    }

    /**
     * Imposta la variabile d'istanza relativa all'allineamento verticale dello sfondo
     * @param string $valign tipo di allineamento
     * @since  1.0.0
     */
    public function setVerticalAlign($valign){
    	$this->vertical_align = $valign;
    }

    /**
     * Metodo GET per lo stato corrente della timerbar
     * @return boolean timerbar attivata o meno
     * @since  1.0.0
     */
    public function getTimer(){
    	return $this->timer;
    }

    /**
     * Metodo SET stato attivazione della timerbar
     * @param boolean $timer timerbar attivata o meno
     * @since  1.0.0
     */
    public function setTimer($timer){
    	if(!is_bool($timer))
			$timer = $timer == 'true' ? true : false;
    	$this->timer = $timer;
    }

    /**
     * GET page_name
     * @since  1.0.0
     */
    public function getPageName(){
    	return $this->page_name;
    }

    /**
     * SET page_name
     * @since  1.0.0
     */
    public function setPageName($name){
    	$this->page_name = $name;
    }

    /**
     * GET dei possibili valori relativi alle transitions
     * @since  1.0.0
     */
    public function getTransitions(){
    	return $this->transitions;
    }

    /**
     * GET position
     * @since  1.0.0
     */
    public function getPosition(){
        return $this->position;
    }

    /**
     * SET position
     * @since  1.0.0
     */
    public function setPosition($position){
        $this->position = $position;
    }

    /**
     * GET container selector
     * @since  1.0.0
     */
    public function getContainerSelector(){
        return $this->container_selector;
    }

    /**
     * SET container selector
     * @since  1.0.0
     */
    public function setContainerSelector($selector){
        $this->container_selector = $selector;
    }

    public function getShowControls(){
        return $this->show_controls;
    }

    public function setShowControls($show_controls){
        $this->show_controls = $show_controls;
    }

    /**
     * GET dei possibili valori relativi alle animazioni
     * @since  1.0.0
     */
    public function getAnimations(){
    	return $this->animations;
    }

    /**
     * GET dei possibili valori relativi agli allineamenti
     * @since  1.0.0
     */
    public function getAligns(){
    	return $this->aligns;
    }

    /**
     * GET delle opzioni per la posizione dello slider
     * @since 1.0.0
     */
    public function getPositions(){
        return $this->positions;
    }

    /**
     * Metodo che deve essere reimplementato nelle classi figlie se si vuole utilizzare realmente la variabile
     * useDefaultConfig, qui serve solo a rendere il metodo disponibile per ogni classe model
     * @since 1.0.0
     */
    public function getUseDefaultConfig(){
        return false;
    }
}
