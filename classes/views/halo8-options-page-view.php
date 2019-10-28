<?php
class Halo8OptionsPageView extends Halo8AbstractAdminView {

    public function render($args=null){
        if(empty($args)){
            return;
        }

        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( $args['permission_error_message'] );
        }
        echo sprintf("<div class=\"wrap\"><h2>%s</h2><form id='%s' method=\"post\" action=\"options.php\" enctype=\"multipart/form-data\">",
                    $args['title'],
                    $args['form_id']);
        settings_errors();
        settings_fields( $args['fields'] );
        do_settings_sections( $args['sections'] );
        submit_button();
        echo    "</form></div>";
    }
}