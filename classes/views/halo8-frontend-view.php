<?php
class Halo8FrontendView extends Halo8AbstractFrontendView {

    public function render($args=null){
    	echo '<script>';
        echo 'halo8_config_object = '.json_encode($args);
        echo '</script>';
    }
}
