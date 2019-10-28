<?php
class Halo8SliderPostTypeView extends Halo8AbstractAdminView {

    public function render($args=null){
    	$button_args = array('id' => 'openMedia', 'name' => 'Add Slide', 'button_classes' => 'button media-button button-primary button-large');
    	$this->renderButton($button_args);
    	$this->renderSlidesContainer($args);
    }

    private function renderSlidesContainer($args){
    	$output = '<div id="halo8SlidesContainer">';
        $output .= wp_nonce_field( $args['nonce'], $args['prefix'].'nonce', true, false);
    	if(!isset($args['currents']) || empty($args['currents'])){
    		$output .= '<p class="description">'.$args['no_images'].'</p>';
    	}else{
    		$output .= $this->inputFormForImage($args['currents'], $args['option_name']);
    	}
    	$output .= '</div>';
    	echo $output;
    }

    public function inputFormForImage($images, $option_name, $start = 0){
        $output = '';
        foreach($images as $image){
            $description = (isset($image['description']) && !empty($image['description'])) ? $image['description'] : '';

            $link = (isset($image['link']) && !empty($image['link'])) ? $image['link'] : '';
            $blank = (isset($image['blank']) && !empty($image['blank'])) && $image['blank'] ? 'checked' : '';
            $output .= '<div id="image_'.$start.'" class="slider_image_container">';
            $output .= '<div class="image_number">'.($start+1).'</div>';
            $output .= '<div class="image">'.$image['image'].'</div>';
            $output .= '<div class="input_fields">';
            $output .= '<input type="hidden" class="media-id" name="'.$option_name.'['.$start.'][ID]" value="'.$image['ID'].'">';
            $output .= '<div class="input_container"><input type="text" class="image_description" name="'.$option_name.'['.$start.'][description]" value="'.$description.'" /><label>Description</label></div>';
            $output .= '<div class="input_container"><input type="text" class="image_link" name="'.$option_name.'['.$start.'][link]" value="'.$link.'" /><label>Link</label></div>';
            $output .= '<div class="input_container"><input type="checkbox" class="blank_link" name="'.$option_name.'['.$start.'][blank]" value="true" '.$blank.' /><label>Open in new window</label></div>';
            $output .= '</div>';
            $output .= '<div class="delete_button"><a class="deleteMedia button media-button button-primary button-large" number="'.($start).'" href="#">Delete</a></div>';
            $output .='</div>';
            $start++;
        }
        return $output;
    }
}