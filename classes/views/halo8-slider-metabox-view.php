<?php
class Halo8SliderMetaboxView extends Halo8AbstractAdminView {

	public function render($args = NULL){
		if(!isset($args['elements']) || empty($args['elements']))
			return;

		foreach ($args['elements'] as $element) {
			switch($element['type']){
				case 'checkbox':
					$this->renderCheckbox($element);
				break;
				case 'input':
					$this->renderInput($element);
				break;
				case 'select':
					$this->renderSelect($element);
				break;
				case 'radio':
					$this->renderRadio($element);
				break;
				case 'nonce':
					wp_nonce_field( $element['nonce_phrase'], $element['prefix'].'nonce' );
				break;
			}
		}
	}
}
