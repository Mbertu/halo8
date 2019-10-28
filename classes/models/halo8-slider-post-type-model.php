<?php
/**
 * Classe astratta model per le pagine di configurazione
 * @since  1.0.0
 */
class Halo8SliderPostTypeModel extends Halo8AbstractPostTypeModel {

	/**
     * Metabox property name
     * @since  1.0.0
     */
    protected $meta_box_name;

    protected $post_type;

    protected $images;

    protected function init($args){
    	$this->setMetaBoxName($args['meta_box_name']);
    	$this->setVersion($args['version']);
    	$this->setPostType($args['post_type']);
    }

    public function import($args){
        parent::import($args);
        foreach ($args as $key => $value) {
            switch($key){
                case 'images':
                    $this->setImages($value);
                break;
            }
        }
    }

    /**
     * Carica i valori passati come argomento all'interno dell'istanza corrente del model
     * assumo che i valori in$args siano già validati dal controller
     * @param  array $args valori da caricare nel model
     * @return null
     * @since  1.0.0
     */
    public function initFromPostId($post_id){
    	if(!isset($post_id) || empty($post_id))
    		return;
    	$post_id = intval($post_id);
    	$images = get_post_meta( $post_id, $this->getMetaBoxName(), true );
    	$this->setImages($images);
    }

    public function setMetaBoxName($meta_box_name){
    	$this->meta_box_name = $meta_box_name;
    }

    public function getMetaBoxName(){
    	return $this->meta_box_name;
    }

    public function setPostType($post_type){
    	$this->post_type = $post_type;
    }

    public function getPostType(){
    	return $this->post_type;
    }

    public function setImages($images){
    	$this->images = $this->sanitizeImagesArray($images);
    }

    public function getImages(){
    	return $this->images;
    }

    private function sanitizeImagesArray($images){
        if($images){
        	foreach($images as &$image){
        		$image['ID'] = intval($image['ID']);
        		$image['link'] = esc_url_raw($image['link'], array('http','https','mailto'));
        		$iamge['description'] = sanitize_text_field($image['description']);
        		$image['blank'] = isset($image['blank']) ? $this->sanitizeBoolean($image['blank']) : false;
        	}
        }else{
            $images = array();
        }
    	return $images;
    }

    private function sanitizeBoolean($string){
    	if(is_bool($string))
    		return $string;
    	if($string == 'true')
    		return true;
    	return false;
    }
}
