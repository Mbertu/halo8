<?php
class Halo8GeneralOptionsPageView extends Halo8OptionsPageView {

	public function renderOverlayPreview($overlay) {
        if($overlay != ''){
            $overlay_url = $this->plugin_url."images/overlays/".$overlay.".png";
            echo "<div class='overlay_preview_container'><div class='overlay_preview' style='background:url(".$overlay_url.") 0 0 repeat;'></div></div>";
        }else{
            echo "<div class='empty_overlay_preview_container'></div>";
        }
    }

}