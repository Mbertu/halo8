<?php

abstract class Halo8AbstractAdminView {

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

    public function renderInput($args){
        $id = $args['id'];
        $name = $args['name'];
        $value = $args['value'];
        $label = (isset($args['label']) && !empty($args['label'])) ? $args['label'] : '';
        $enabled = isset($args['enabled']) ? $args['enabled'] : true;
        $message = isset($args['message']) ? $args['message'] : false;
        $extra_classes = isset($args['extra_classes']) ? $args['extra_classes'] : array();

        $output = "<div class='".$id."_container halo8_input_container";
        foreach ($extra_classes as $class) {
            $output .= ' '.$class;
        }
        $output.= "'>";

        $output .= "<input id='".$id."' type='text' name='".$name."[".$id."]' value='".$value."' ";

        if(!$enabled)
            $output .= "disabled ";

        $output .= "/>";

        if(!empty($label)){
            $output .= "<label for='".$name."[".$id."]'>".$label."</label>";
        }

        if($message)
            $output .= "<p class='description'>".$message."</p>";

        $output .= "</div>";
        echo $output;
    }

    public function renderHidden($args){
        $id = $args['id'];
        $name = $args['name'];
        $value = $args['value'];

        $output = "<input id='".$id."' type='hidden' name='".$name."[".$id."]' value='".$value."' />";
        echo $output;
    }

    public function renderCheckbox($args) {
        $id = $args['id'];
        $name = $args['name'];
        $checked = $args['checked'];
        $label = (isset($args['label']) && !empty($args['label'])) ? $args['label'] : '';
        $enabled = isset($args['enabled']) ? $args['enabled'] : true;
        $message = isset($args['message']) ? $args['message'] : false;
        $extra_classes = isset($args['extra_classes']) ? $args['extra_classes'] : array();

        $output = "<div class='".$id."_container halo8_input_container";
        foreach ($extra_classes as $class) {
            $output .= ' '.$class;
        }
        $output.= "'>";

        $output .= "<input id='".$id."' name='".$name."[".$id."]' type='checkbox' value='true' ";
        if($checked)
            $output .= "checked='checked' ";
        if(!$enabled)
            $output .= "disabled ";
        $output .= "/>";
        if(!empty($label)){
            $output .= "<label for='".$name."[".$id."]'>".$label."</label>";
        }
        if($message)
            $output .= "<p class='description'>".$message."</p>";

        $output .= "</div>";
        echo $output;
    }

    public function renderRadio($args) {
        $id = $args['id'];
        $name = $args['name'];
        $elements = $args['elements'];
        $enabled = isset($args['enabled']) ? $args['enabled'] : true;
        $extra_classes = isset($args['extra_classes']) ? $args['extra_classes'] : array();

        $output = "<div class='".$id."_container halo8_input_container";
        foreach ($extra_classes as $class) {
            $output .= ' '.$class;
        }
        $output.= "'>";

        foreach($elements as $element){
            $output .= "<p><label><input type='radio' name='".$name."[".$id."]' value='".$element['value']."' class='tog ".$id."' ";
            if($element['checked'])
                $output .= "checked='checked' ";
            if(!$enabled)
                $output .= "disabled ";
            $output .= "/>".$element['label']."</label></p>";
        }
        $output .= "</div>";
        echo $output;
    }

    public function renderSelect($args) {
        $id = $args['id'];
        $name = $args['name'];
        $elements = $args['elements'];
        $label = (isset($args['label']) && !empty($args['label'])) ? $args['label'] : '';
        $enabled = isset($args['enabled']) ? $args['enabled'] : true;
        $message = isset($args['message']) ? $args['message'] : false;
        $extra_classes = isset($args['extra_classes']) ? $args['extra_classes'] : array();

        $output = "<div class='".$id."_container halo8_input_container";
        foreach ($extra_classes as $class) {
            $output .= ' '.$class;
        }
        $output.= "'>";

        $output .= "<select id='".$id."' name='".$name."[".$id."]' ";
        if(!$enabled)
            $output .= "disabled ";
        $output .= ">";
        foreach($elements as $element){
            $output .= "<option value='".$element['value']."'";
            if($element['current'])
                $output .= "selected";
            $output .= ">".$element['label']."</option>";
        }
        $output .= "</select>";

        if(!empty($label)){
            $output .= "<label for='".$name."[".$id."]'>".$label."</label>";
        }

        if($message)
            $output .= "<p class='description'>".$message."</p>";

        $output .= "</div>";
        echo $output;
    }

    public function renderButton($args) {
        $id = $args['id'];
        $name = $args['name'];
        $message = isset($args['message']) ? $args['message'] : false;

        $output = "<a id='".$id."'class='".$args['button_classes']."' href='#'>".$name."</a>";
        if($message)
            $output .= "<p class='description'>".$message."</p>";
        echo $output;
    }


    public abstract function render($args=null);
}