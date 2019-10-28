<?php
class Halo8SliderUsersView extends Halo8AbstractAdminView {

	public function render($args = NULL){
		if(!$args){
			return;
		}
		if(!isset($args['elements']) || empty($args['elements']))
			return;

		foreach ($args['elements'] as $element) {
			switch($element['type']){
				case 'select':
					$this->renderSelect($element);
				break;
				case 'nonce':
					wp_nonce_field( $element['nonce_phrase'], $element['prefix'].'nonce' );
				break;
				case 'header':
					$this->renderHeader($element);
				break;
				case 'footer':
				 	$this->renderFooter();
				break;
			}
		}
	}

	public function renderHeader($args){
        $output = '<h2>'.$args['title'].'</h2>';
		$output .= '<table class="form-table"><tbody>';
		echo $output;
	}

	public function renderFooter(){
		echo '</tbody></table>';
	}

    public function renderSelect($args) {
        $id = $args['id'];
        $name = $args['name'];
        $elements = $args['elements'];
        $label = (isset($args['label']) && !empty($args['label'])) ? $args['label'] : '';
        $enabled = isset($args['enabled']) ? $args['enabled'] : true;
        $message = isset($args['message']) ? $args['message'] : false;
        $extra_classes = isset($args['extra_classes']) ? $args['extra_classes'] : array();

        $output = "<tr class='".$id."_container halo8_input_container form-field term-parent-wrap";
        foreach ($extra_classes as $class) {
            $output .= ' '.$class;
        }
        $output.= "'>";

        $output .= '<th scope="row">';

        if(!empty($label)){
            $output .= "<label for='".$name."[".$id."]'>".$label."</label>";
        }

        $output .= '</th>';

        $output .= "<td><select id='".$id."' name='".$name."[".$id."]' ";
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

        if($message)
            $output .= "<p class='description'>".$message."</p>";

        $output .= "</td></tr>";
        echo $output;
    }
}