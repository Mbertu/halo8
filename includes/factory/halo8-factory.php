<?php
/**
 * La classe Factory che si occuperà di istanziare il giusto
 * controller per la pagina che è stata richiesta
 *
 * @since  1.0.0
 */
class Halo8Factory implements IHalo8Factory {

    /**
     * Propietà statica necessaria per l'implementazione del pattern singleton
     * @var Object
     *
     * @since  1.0.0
     */
    private static $instance;

    /**
     * Oggetto contenente le configurazioni impostate per il plugin
     * @var Object
     *
     * @since  1.0.0
     */
    private $settings;

    public function __construct() {}

    public static function getInstance($plugin_path, $plugin_url) {
        if ( !isset( self::$instance ) ) {
            self::$instance = new self;
            self::$instance->init($plugin_path, $plugin_url);
        }
        return self::$instance;
    }

    public function init($plugin_path, $plugin_url){
        $this->plugin_path = $plugin_path;
        $this->plugin_url = $plugin_url;
        $this->classes_path = $this->plugin_path.'classes/';
        $this->controllers_path = $this->classes_path.'controllers/';
        $this->models_path = $this->classes_path.'models/';
        $this->views_path = $this->classes_path.'views/';
        $this->abstract_classes_path = $this->classes_path.'abstracts/';
        return;
    }

    /*
     * Metodo che istanzia il controller corretto
     * */
    public function createController($name, $args = null){
        if($args && is_array($args)){
            $args['plugin_url'] = $this->plugin_url;
        }
        switch($name){
            case "plugin_controller":
                if(!class_exists('Halo8FullScreenSlider'))
                    require_once($this->classes_path . "Halo8FullScreenSlider.php");
                return Halo8FullScreenSlider::getInstance($this);
            break;
            case "options_page_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractOptionsPageController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-page-controller.php");
                if(!class_exists('Halo8GeneralOptionsPageController'))
                    require_once($this->controllers_path . "halo8-general-options-page-controller.php");
                return Halo8GeneralOptionsPageController::getInstance('Halo8GeneralOptionsPageController', $args);
            break;
            case "slider_404_page_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractOptionsPageController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-page-controller.php");
                if(!class_exists('Halo8404OptionsPageController'))
                    require_once($this->controllers_path . "halo8-404-options-page-controller.php");
                return Halo8404OptionsPageController::getInstance('Halo8404OptionsPageController', $args);
            break;
            case "slider_archives_page_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractOptionsPageController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-page-controller.php");
                if(!class_exists('Halo8ArchivesOptionsPageController'))
                    require_once($this->controllers_path . "halo8-archives-options-page-controller.php");
                return Halo8ArchivesOptionsPageController::getInstance('Halo8ArchivesOptionsPageController', $args);
            break;
            case "slider_search_page_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractOptionsPageController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-page-controller.php");
                if(!class_exists('Halo8SearchOptionsPageController'))
                    require_once($this->controllers_path . "halo8-search-options-page-controller.php");
                return Halo8SearchOptionsPageController::getInstance('Halo8SearchOptionsPageController', $args);
            break;
            case "slider_authors_page_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractOptionsPageController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-page-controller.php");
                if(!class_exists('Halo8AuthorsOptionsPageController'))
                    require_once($this->controllers_path . "halo8-authors-options-page-controller.php");
                return Halo8AuthorsOptionsPageController::getInstance('Halo8AuthorsOptionsPageController', $args);
            break;
            case "type_slider_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractPostTypeController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-post-type-controller.php");
                if(!class_exists('Halo8SliderPostTypeController'))
                    require_once($this->controllers_path . "halo8-slider-post-type-controller.php");
                return Halo8SliderPostTypeController::getInstance('Halo8SliderPostTypeController', $args);
            break;
            case "slider_metabox_controller":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractMetaboxController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-metabox-controller.php");
                if(!class_exists('Halo8SliderMetaboxController'))
                    require_once($this->controllers_path . "halo8-slider-metabox-controller.php");
                return Halo8SliderMetaboxController::getInstance('Halo8SliderMetaboxController', $args);
            break;
            case "slider_taxonomies_metabox":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractMetaboxController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-metabox-controller.php");
                if(!class_exists('Halo8SliderTaxonomiesController'))
                    require_once($this->controllers_path . "halo8-slider-taxonomies-controller.php");
                return Halo8SliderTaxonomiesController::getInstance('Halo8SliderTaxonomiesController', $args);
            break;
            case "slider_users_metabox":
                if(!class_exists('Halo8AbstractController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-controller.php");
                if(!class_exists('Halo8AbstractMetaboxController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-metabox-controller.php");
                if(!class_exists('Halo8SliderUsersController'))
                    require_once($this->controllers_path . "halo8-slider-users-controller.php");
                return Halo8SliderUsersController::getInstance('Halo8SliderUsersController', $args);
            break;
            case "frontend_controller":
                if(!class_exists('Halo8AbstractFrontendController'))
                    require_once($this->abstract_classes_path . "halo8-abstract-frontend-controller.php");
                if(!class_exists('Halo8FrontendController'))
                    require_once($this->controllers_path . "halo8-frontend-controller.php");
                return Halo8FrontendController::getInstance('Halo8FrontendController', $args);
            break;
        }
    }

    /**
     * Metodo che istanzia un oggetto di tipo model
     */
    public function createModel($name, $args = null){
        if($args && is_array($args)){
            $args['plugin_url'] = $this->plugin_url;
        }
        switch($name){
            case "general_options_page":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8GeneralOptionsModel'))
                    require_once($this->models_path . "halo8-general-options-model.php");
                return Halo8GeneralOptionsModel::getInstance('Halo8GeneralOptionsModel', $args);
            break;
            case "404_options_page":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8404OptionsModel'))
                    require_once($this->models_path . "halo8-404-options-model.php");
                return Halo8404OptionsModel::getInstance('Halo8404OptionsModel', $args);
            break;
            case "authors_options_page":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8AuthorsOptionsModel'))
                    require_once($this->models_path . "halo8-authors-options-model.php");
                return Halo8AuthorsOptionsModel::getInstance('Halo8AuthorsOptionsModel', $args);
            break;
            case "archives_options_page":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8ArchivesOptionsModel'))
                    require_once($this->models_path . "halo8-archives-options-model.php");
                return Halo8ArchivesOptionsModel::getInstance('Halo8ArchivesOptionsModel', $args);
            break;
            case "search_options_page":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8SearchOptionsModel'))
                    require_once($this->models_path . "halo8-search-options-model.php");
                return Halo8SearchOptionsModel::getInstance('Halo8SearchOptionsModel', $args);
            break;
            case "type_slider_model":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractPostTypeModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-post-type-model.php");
                if(!class_exists('Halo8SliderPostTypeModel'))
                    require_once($this->models_path . "halo8-slider-post-type-model.php");
                return Halo8SliderPostTypeModel::getInstance('Halo8SliderPostTypeModel', $args);
            break;
            case "single_options_model":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8AbstractOptionsModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-options-model.php");
                if(!class_exists('Halo8SingleOptionsModel'))
                    require_once($this->models_path . "halo8-single-options-model.php");
                return Halo8SingleOptionsModel::getInstance('Halo8SingleOptionsModel', $args);
            break;
            case "users_model":
                if(!class_exists('Halo8AbstractModel'))
                    require_once($this->abstract_classes_path . "halo8-abstract-model.php");
                if(!class_exists('Halo8UsersModel'))
                    require_once($this->models_path . "halo8-users-model.php");
                return Halo8UsersModel::getInstance('Halo8UsersModel', $args);
            break;
        }
    }

    /*
     * Metodo che istanzia la view richiesta
     * */
    public function createView($name){
        switch($name){
            case "options_page_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8OptionsPageView'))
                    require_once($this->views_path . "halo8-options-page-view.php");
                if(!class_exists('Halo8GeneralOptionsPageView'))
                    require_once($this->views_path . "halo8-general-options-page-view.php");
                return Halo8GeneralOptionsPageView::getInstance('Halo8GeneralOptionsPageView', $this->plugin_url);
            break;
            case "slider_page_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8OptionsPageView'))
                    require_once($this->views_path . "halo8-options-page-view.php");
                return Halo8OptionsPageView::getInstance('Halo8OptionsPageView', $this->plugin_url);
            break;
            case "type_slider_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8SliderPostTypeView'))
                    require_once($this->views_path . "halo8-slider-post-type-view.php");
                return Halo8SliderPostTypeView::getInstance('Halo8SliderPostTypeView', $this->plugin_url);
            break;
            case "slider_metabox_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8SliderMetaboxView'))
                    require_once($this->views_path . "halo8-slider-metabox-view.php");
                return Halo8SliderMetaboxView::getInstance('Halo8SliderMetaboxView', $this->plugin_url);
            break;
            case "slider_taxonomies_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8SliderTaxonomiesView'))
                    require_once($this->views_path . "halo8-slider-taxonomies-view.php");
                return Halo8SliderTaxonomiesView::getInstance('Halo8SliderTaxonomiesView', $this->plugin_url);
            break;
            case "slider_users_view":
                if(!class_exists('Halo8AbstractAdminView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-admin-view.php");
                if(!class_exists('Halo8SliderUsersView'))
                    require_once($this->views_path . "halo8-slider-users-view.php");
                return Halo8SliderUsersView::getInstance('Halo8SliderUsersView', $this->plugin_url);
            break;
            case "frontend_view":
                if(!class_exists('Halo8AbstractFrontendView'))
                    require_once($this->abstract_classes_path . "halo8-abstract-frontend-view.php");
                if(!class_exists('Halo8FrontendView'))
                    require_once($this->views_path . "halo8-frontend-view.php");
                return Halo8FrontendView::getInstance('Halo8FrontendView', $this->plugin_url);
            break;
        }
    }
}
