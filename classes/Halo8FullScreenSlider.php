<?php
/**
 * Halo8 Full Screen Slider.
 *
 * @author    Michele Bertuccioli <michele@bertuccioli.me>
 * @license   GPL-2.0+
 *
 * @link      michele.bertuccioli.me
 *
 * @copyright 4-16-2015 Michele Bertuccioli
 */
class Halo8FullScreenSlider
{
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    1.0.0
     *
     * @var string
     */
    protected $plugin_slug = 'halo8-full-screen-slider';

    /**
     * Variabile in cui sono salvate le impostazioni correnti del plugin.
     *
     * @since  1.0.0
     */
    protected $settings = array();

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Array contente i riferimenti ai controller istanziati.
     *
     * @since 1.0.0
     */
    private $controllers = array();

    /**
     * Array contente i nomi dele options inserite per il plugin.
     *
     * @since 1.0.0
     */
    private $options_name = array();

    /**
     * Lista di post di tipo slider.
     *
     * @since  1.0.0
     */
    protected $sliders = array();

    /**
     * Nome/i per i tipi di dato custom.
     *
     * @since 1.0.0
     */
    private $custom_post_type;

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     *
     * @since     1.0.0
     */
    private function __construct()
    {
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return object A single instance of this class
     */
    public static function getInstance($factory)
    {

        // If the single instance hasn"t been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self();
            self::$instance->init($factory);
        }

        return self::$instance;
    }

    public function init($factory)
    {
        $this->factory = $factory;
        // Load plugin text domain
        add_action('init', array($this, 'pluginInit'));

        // enqueue js e css per admin o per frontend
        // registrazione delle rispettive chiamate ajax
        if (is_admin()) {
            // Halo8 options page
            add_action('admin_menu', array($this, 'addPluginAdminMenu'));
            add_action('admin_init', array($this, 'addPluginAdminSettingSection'));

            add_action('admin_enqueue_scripts', array($this, 'enqueueAdminStyles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));

            // aggiunta metabox e salvataggio dei dati relativi nel caso dei tipi di post
            add_action('add_meta_boxes', array($this, 'addMetabox'));
            add_action('save_post', array($this, 'savePost'));

            // metabox per tag, categorie e altre taxonomies
            if ((isset($_GET['taxonomy']) && $_GET['taxonomy'] !== '')) {
                add_action(sanitize_text_field($_GET['taxonomy']).'_edit_form', array(
                    $this,
                    'addTaxonomyForm',
                ), 90, 1);
            }

            // salvataggio dei metabox per taxonomies
            add_action('edit_term', array($this, 'saveTermMetadata'));

            add_action('show_user_profile', array($this, 'addUserForm'));
            add_action('edit_user_profile', array($this, 'addUserForm'));
            add_action('personal_options_update', array($this, 'saveUserMetadata'));
            add_action('edit_user_profile_update', array($this, 'saveUserMetadata'));

            add_action('wp_ajax_refresh_overlay_preview', array($this, 'refreshOverlayPreview'));
            add_action('wp_ajax_get_form_for_images', array($this, 'inputFormForSliderImages'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
            // output codice frontend
            add_action('wp_footer', array($this, 'wpFooterHook'), 30);
        }
        $this->options_name = array(
            'general' => 'halo8_general_options',
            'slider_404' => 'halo8_slider_404',
            'slider_archives' => 'halo8_slider_archives',
            'slider_search' => 'halo8_slider_search',
            'slider_authors' => 'halo8_slider_authors',
            'authors_configuration' => 'halo8_slider_users_configuration',
            'single_configuration' => 'halo8_slider_configuration',
            'taxonomies_configuration' => 'halo8_slider_taxonomies_configuration',

        );

        $this->settings[$this->options_name['general']] = get_option($this->options_name['general']);
        $this->settings[$this->options_name['slider_404']] = get_option($this->options_name['slider_404']);
        $this->settings[$this->options_name['slider_archives']] = get_option($this->options_name['slider_archives']);
        $this->settings[$this->options_name['slider_search']] = get_option($this->options_name['slider_search']);
        $this->settings[$this->options_name['slider_authors']] = get_option($this->options_name['slider_authors']);

        $this->custom_post_type = 'sliders';
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param bool $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public static function activate($network_wide)
    {
        // TODO: Define activation functionality here
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param bool $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog
     */
    public static function deactivate($network_wide)
    {
        // TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function pluginInit()
    {
        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo');
        load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)).'/lang/');

        // Da inizializzare prima della lista di OptionsPageControllers per poter recuperare gli slider e passarli ai controller
        $this->initCustomPostsTypeControllers();

        if (is_admin()) {
            $this->getAllActiveSliders();
            $this->initSliderMetaboxControllers();
            $this->initOptionsPagesControllers();
        } else {
            $this->initFrontendControllers();
        }

        return;
    }

    /**
     * Init dei controller relativi alle pagine opzioni.
     *
     * @since 1.0.0
     */
    private function initOptionsPagesControllers()
    {
        if (!isset($this->controllers['options_page_controller']) || empty($this->controllers['options_page_controller'])) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['general'],
                'page_name' => 'general',
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('general_options_page', $model_args);
            $model->import($this->settings[$this->options_name['general']]);
            $view = $this->factory->createView('options_page_view');
            $this->controllers['options_page_controller'] = $this->factory->createController('options_page_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_404_page_controller']) || empty($this->controllers['slider_404_page_controller'])) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_404'],
                'page_name' => '404',
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('404_options_page', $model_args);
            $model->import($this->settings[$this->options_name['slider_404']]);
            $view = $this->factory->createView('options_page_view');
            $this->controllers['slider_404_page_controller'] = $this->factory->createController('slider_404_page_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_archives_page_controller']) || empty($this->controllers['slider_archives_page_controller'])) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_archives'],
                'page_name' => 'archives',
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('archives_options_page', $model_args);
            $model->import($this->settings[$this->options_name['slider_archives']]);
            $view = $this->factory->createView('options_page_view');
            $this->controllers['slider_archives_page_controller'] = $this->factory->createController('slider_archives_page_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_search_page_controller']) || empty($this->controllers['slider_search_page_controller'])) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_search'],
                'page_name' => 'search',
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('search_options_page', $model_args);
            $model->import($this->settings[$this->options_name['slider_search']]);
            $view = $this->factory->createView('options_page_view');
            $this->controllers['slider_search_page_controller'] = $this->factory->createController('slider_search_page_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_authors_page_controller']) || empty($this->controllers['slider_authors_page_controller'])) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_authors'],
                'page_name' => 'authors',
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('authors_options_page', $model_args);
            $model->import($this->settings[$this->options_name['slider_authors']]);
            $view = $this->factory->createView('options_page_view');
            $this->controllers['slider_authors_page_controller'] = $this->factory->createController('slider_authors_page_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
    }

    /**
     * Inizializza i controller relativi ai tipo di post custom.
     *
     * @since 1.0.0
     */
    private function initCustomPostsTypeControllers()
    {
        if (!isset($this->controllers['type_slider_controller']) || empty($this->controllers['type_slider_controller'])) {
            $args = array(
                'version' => $this->version,
                'post_type' => $this->custom_post_type,
                'meta_box_name' => 'halo8_slider_images',
            );
            $model = $this->factory->createModel('type_slider_model', $args);
            $view = $this->factory->createView('type_slider_view');
            $this->controllers['type_slider_controller'] = $this->factory->createController('type_slider_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
    }

    private function initSliderMetaboxControllers()
    {
        if (!isset($this->controllers['slider_metabox_controller']) || empty($this->controllers['slider_metabox_controller'])) {
            $args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['single_configuration'],
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('single_options_model', $args);
            $view = $this->factory->createView('slider_metabox_view');
            $this->controllers['slider_metabox_controller'] = $this->factory->createController('slider_metabox_controller', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_taxonomies_metabox']) || empty($this->controllers['slider_taxonomies_metabox'])) {
            $args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['taxonomies_configuration'],
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('single_options_model', $args);
            $view = $this->factory->createView('slider_taxonomies_view');
            $this->controllers['slider_taxonomies_metabox'] = $this->factory->createController('slider_taxonomies_metabox', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
        if (!isset($this->controllers['slider_users_metabox']) || empty($this->controllers['slider_users_metabox'])) {
            $args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['authors_configuration'],
                'sliders' => $this->sliders,
            );
            $model = $this->factory->createModel('users_model', $args);
            $view = $this->factory->createView('slider_users_view');
            $this->controllers['slider_users_metabox'] = $this->factory->createController('slider_users_metabox', array('view' => $view, 'model' => $model, 'slug' => $this->plugin_slug));
        }
    }

    /**
     * Inizializza i controller relativi ai tipo di post custom.
     *
     * @since 1.0.0
     */
    private function initFrontendControllers()
    {
        if (!isset($this->controllers['frontend_controller']) || empty($this->controllers['frontend_controller'])) {
            $view = $this->factory->createView('frontend_view');
            $this->controllers['frontend_controller'] = $this->factory->createController('frontend_controller', array('view' => $view, 'slug' => $this->plugin_slug));
        }
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     */
    public function enqueueAdminStyles()
    {
        if (isset($_GET['page'])) {
            if (sanitize_text_field($_GET['page']) == 'halo8_general_options') {
                $this->controllers['options_page_controller']->enqueueCss();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_search_options') {
                $this->controllers['slider_search_page_controller']->enqueueCss();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_404_options') {
                $this->controllers['slider_404_page_controller']->enqueueCss();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_authors_options') {
                $this->controllers['slider_authors_page_controller']->enqueueCss();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_archives_options') {
                $this->controllers['slider_archives_page_controller']->enqueueCss();
            }
        } else {
            if (!$screen = get_current_screen()) {
                return;
            }

            if ($screen->id == $this->custom_post_type && $screen->post_type == $this->custom_post_type) {
                $this->controllers['type_slider_controller']->enqueueCss();
            } elseif (!isset($this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) || !$this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) {
                $this->controllers['slider_metabox_controller']->enqueueCss();
            }
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     */
    public function enqueueAdminScripts()
    {
        if (isset($_GET['page'])) {
            if (sanitize_text_field($_GET['page']) == 'halo8_general_options') {
                $this->controllers['options_page_controller']->enqueueJs();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_search_options') {
                $this->controllers['slider_search_page_controller']->enqueueJs();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_404_options') {
                $this->controllers['slider_404_page_controller']->enqueueJs();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_authors_options') {
                $this->controllers['slider_authors_page_controller']->enqueueJs();
            }
            if (sanitize_text_field($_GET['page']) == 'halo8_archives_options') {
                $this->controllers['slider_archives_page_controller']->enqueueJs();
            }
        } else {
            if (!$screen = get_current_screen()) {
                return;
            }

            if ($screen->id == $this->custom_post_type && $screen->post_type == $this->custom_post_type) {
                $this->controllers['type_slider_controller']->enqueueJs();
            } elseif (!isset($this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) || !$this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) {
                $this->controllers['slider_metabox_controller']->enqueueJs();
            }
        }
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        $this->controllers['frontend_controller']->enqueueCss();
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        $this->controllers['frontend_controller']->enqueueJs();
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function addPluginAdminMenu()
    {
        $this->controllers['options_page_controller']->addMenuPage();
        $this->controllers['slider_404_page_controller']->addMenuPage();
        $this->controllers['slider_archives_page_controller']->addMenuPage();
        $this->controllers['slider_search_page_controller']->addMenuPage();
        $this->controllers['slider_authors_page_controller']->addMenuPage();
    }

    /**
     * Registra la setting sections per la pagina di configurazione del plugin.
     *
     * @since  1.0.0
     */
    public function addPluginAdminSettingSection()
    {
        $this->controllers['options_page_controller']->setupSettingSection();
        $this->controllers['slider_404_page_controller']->setupSettingSection();
        $this->controllers['slider_archives_page_controller']->setupSettingSection();
        $this->controllers['slider_search_page_controller']->setupSettingSection();
        $this->controllers['slider_authors_page_controller']->setupSettingSection();
    }

    /**
     * Metodo richiamato tramite ajax per il refresh live della preview overlay.
     *
     * @since 1.0.0
     */
    public function refreshOverlayPreview()
    {
        $selectedOverlay = sanitize_text_field($_POST['selectedOverlay']);
        $this->controllers['options_page_controller']->refreshOverlayPreview($selectedOverlay);
        die();
    }

    /**
     * Metodo richiamato in Ajax per avere la formrelativa alle nuove immagini inserite nel post.
     *
     * @since  1.0.0
     */
    public function inputFormForSliderImages()
    {
        // to snytize
        $ids = $_POST['ids'];
        // to snytize
        $start = $_POST['start'];
        $args = array('ids' => $ids, 'start' => $start);
        $this->controllers['type_slider_controller']->getSliderMetadataFormForImages($args);
        die();
    }

    /**
     * Metodo richiamato nel front end per generare l'output del plugin.
     *
     * @since    1.0.0
     */
    public function wpFooterHook()
    {
        //recupero i dati relativi alla pagina corrente
        $pageData = get_queried_object();

        //istanzio il modello e lo inizializzo con i dati di configurazione globali
        $model_args = array(
            'version' => $this->version,
            'option_name' => $this->options_name['general'],
            'page_name' => 'general',
            'sliders' => $this->sliders,
        );
        $plugin_global_model = $this->factory->createModel('general_options_page', $model_args);
        $plugin_global_model->import($this->settings[$this->options_name['general']]);

        //passo il riferimento al modello appena creato al controller
        $this->controllers['frontend_controller']->setPluginGlobalModel($plugin_global_model);

        //istanzio il modello relativo allo slider e non carico nulla al suo interno,
        //sarà poi il controller ad occuparsene
        $model_args = array(
            'version' => $this->version,
            'post_type' => $this->custom_post_type,
            'meta_box_name' => 'halo8_slider_images',
        );
        $slider_post_type_model = $this->factory->createModel('type_slider_model', $model_args);
        //passo il riferimento al modello appena creato al controller
        $this->controllers['frontend_controller']->setSliderPostTypeModel($slider_post_type_model);

        $post_type = get_post_type();

        if(isset($plugin_global_model->getExcludePostTypes()[$post_type]) && $plugin_global_model->getExcludePostTypes()[$post_type] === 'true'){
            return;
        }

        $custom_post_types = get_post_types( array( 'public' => true,'_builtin' => false ) );
        $custom_post_types[] = 'post';
        if (is_single() && in_array($post_type,$custom_post_types)) {
            $args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['single_configuration'],
                'sliders' => $this->sliders,
            );
            $plugin_page_model = $this->factory->createModel('single_options_model', $args);

              $plugin_page_model->initFromPostId($pageData->ID);

              $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

              $this->controllers['frontend_controller']->setCurrentPage('single');

              if(is_null($plugin_page_model->getSlider())){
                  $args = array(
                      'version' => $this->version,
                      'option_name' => $this->options_name['single_configuration'],
                      'sliders' => $this->sliders,
                  );

                  $plugin_page_model = $this->factory->createModel('single_options_model', $args);

                  $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

                  $this->controllers['frontend_controller']->setCurrentPage('post');
                  //richiamo il metodo render view del controller ormai completamente configurato
                  $this->controllers['frontend_controller']->renderView(array('position' => 'footer', 'pageData' => $pageData));

                  return;
              }
          }


        if(function_exists('is_shop')){

            if (is_single() && $post_type === 'product') {

                //$pageData = get_post(get_option( 'woocommerce_shop_page_id' ));
                $args = array(
    				'version' => $this->version,
    				'option_name' => $this->options_name['single_configuration'],
    				'sliders' => $this->sliders
    			);
    			$plugin_page_model = $this->factory->createModel('single_options_model', $args);

                $plugin_page_model->initFromPostId($pageData->ID);

    			$this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

    			$this->controllers['frontend_controller']->setCurrentPage('single');

                /*if(is_null($plugin_page_model->getSlider())){ die;
                    $model_args = array(
                        'version' => $this->version,
                        'option_name' => $this->options_name['slider_archives'],
                        'page_name' => 'archives',
                        'sliders' => $this->sliders,
                    );
                    $plugin_page_model = $this->factory->createModel('archives_options_page', $model_args);
                    $plugin_page_model->import($this->settings[$this->options_name['slider_archives']]);

                    $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);
                    //setto il nome della pagina
                    $this->controllers['frontend_controller']->setCurrentPage('archive');

                    //richiamo il metodo render view del controller ormai completamente configurato
                    $this->controllers['frontend_controller']->renderView(array('position' => 'footer', 'pageData' => $pageData));

                    return;
                }*/
            }

            if(is_shop()){
                $args = array(
                    'version' => $this->version,
                    'option_name' => $this->options_name['single_configuration'],
                    'sliders' => $this->sliders,
                );
                $plugin_page_model = $this->factory->createModel('single_options_model', $args);

                $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

                $this->controllers['frontend_controller']->setCurrentPage('single');

                $pageData = get_post(get_option( 'woocommerce_shop_page_id' ));
            }
        }

        if (is_page() || is_home()) {
            $args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['single_configuration'],
                'sliders' => $this->sliders,
            );
            $plugin_page_model = $this->factory->createModel('single_options_model', $args);

            $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

            $this->controllers['frontend_controller']->setCurrentPage('single');
        }

        if(is_tax() || is_category() || is_tag()){
        	$args = array(
        		'version' => $this->version,
        		'option_name' => $this->options_name['taxonomies_configuration'],
        		'sliders' => $this->sliders
        	);
        	$plugin_page_model = $this->factory->createModel('single_options_model', $args);
        	$this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);

        	$this->controllers['frontend_controller']->setCurrentPage('taxonomy');
        }

        if(is_archive() && !is_tax() && !is_author() && !is_category() && !is_tag()){
        	$model_args = array(
        		'version' => $this->version,
        		'option_name' => $this->options_name['slider_archives'],
        		'page_name'	=> 'archives',
        		'sliders' => $this->sliders
        	);
        	$plugin_page_model = $this->factory->createModel('archives_options_page', $model_args);
        	$plugin_page_model->import($this->settings[$this->options_name['slider_archives']]);

        	$this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);
        	//setto il nome della pagina
        	$this->controllers['frontend_controller']->setCurrentPage('archive');
        }

        if (is_search()) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_search'],
                'page_name' => 'search',
                'sliders' => $this->sliders,
            );
            $plugin_page_model = $this->factory->createModel('search_options_page', $model_args);
            $plugin_page_model->import($this->settings[$this->options_name['slider_search']]);

            $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);
            //setto il nome della pagina
            $this->controllers['frontend_controller']->setCurrentPage('search');
        }

        if (is_404()) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_404'],
                'page_name' => '404',
                'sliders' => $this->sliders,
            );
            $plugin_page_model = $this->factory->createModel('404_options_page', $model_args);
            $plugin_page_model->import($this->settings[$this->options_name['slider_404']]);

            $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);
            //setto il nome della pagina
            $this->controllers['frontend_controller']->setCurrentPage('404');
        }

        if (is_author()) {
            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['authors_configuration'],
                'sliders' => $this->sliders,
            );
            $author_model = $this->factory->createModel('users_model', $model_args);
            $this->controllers['frontend_controller']->setAuthorModel($author_model);

            $model_args = array(
                'version' => $this->version,
                'option_name' => $this->options_name['slider_authors'],
                'page_name' => 'authors',
                'sliders' => $this->sliders,
            );
            $plugin_page_model = $this->factory->createModel('authors_options_page', $model_args);
            $plugin_page_model->import($this->settings[$this->options_name['slider_authors']]);

            $this->controllers['frontend_controller']->setPluginPageModel($plugin_page_model);
            //setto il nome della pagina
            $this->controllers['frontend_controller']->setCurrentPage('author');
        }
        //richiamo il metodo render view del controller ormai completamente configurato
        $this->controllers['frontend_controller']->renderView(array('position' => 'footer', 'pageData' => $pageData));
    }

    /**
     * Generazione form di configurazione del plugin per le singole risorse.
     *
     * @since    1.0.0
     */
    public function addMetabox()
    {
        if (!$screen = get_current_screen()) {
            return;
        }

        if ($screen->id == $this->custom_post_type && $screen->post_type == $this->custom_post_type) {
            $this->controllers['type_slider_controller']->renderView();
        } elseif (!isset($this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) || !$this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) {
            $this->controllers['slider_metabox_controller']->setPostType($screen->id);
            $this->controllers['slider_metabox_controller']->renderView();
        }
    }

    /**
     * Salvo i metadati aggiuntivi per il post corrente.
     *
     * @since  1.0.0
     */
    public function savePost($post_id)
    {
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!$screen = get_current_screen()) {
            return;
        }

        if ($screen->id == $this->custom_post_type && $screen->post_type == $this->custom_post_type) {
            $this->controllers['type_slider_controller']->saveSliderMetadata($post_id, $_POST);
        } elseif (!isset($this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) || !$this->settings[$this->options_name['general']]['exclude_post_type'][$screen->id]) {
            $this->controllers['slider_metabox_controller']->saveMetadata(array('post_id' => $post_id, '_post' => $_POST));
        }
    }

    /**
     * Generazione form di configurazione del plugin per i singoli terms.
     *
     * @since  1.0.0
     */
    public function addTaxonomyForm($term)
    {
        if (isset($this->settings[$this->options_name['general']]['exclude_taxonomy'][$term->slug]) && $this->settings[$this->options_name['general']]['exclude_taxonomy'][$term->slug]) {
            return;
        }
        $this->controllers['slider_taxonomies_metabox']->setCurrentTerm($term);
        $this->controllers['slider_taxonomies_metabox']->renderView();
    }

    /**
     * Salva i metadati per il termine corrente.
     *
     * @param int    $term_id  ID of the term to save data for
     * @param int    $tt_id    The taxonomy_term_id for the term
     * @param string $taxonomy The taxonomy the term belongs to
     *
     * @since  1.0.0
     */
    public function saveTermMetadata($term_id)
    {
        // TODO non richiamare se metadata o term esclusi
        if (!isset($_POST['taxonomy'])) {
            return;
        }
        $taxonomy = get_taxonomy($_POST['taxonomy']);
        if (!$taxonomy) {
            return;
        }
        $term = get_term_by('id', $term_id, $taxonomy->name);
        if (!$term) {
            return;
        }
        if (isset($this->settings[$this->options_name['general']]['exclude_taxonomy'][$term->slug]) && $this->settings[$this->options_name['general']]['exclude_taxonomy'][$term->slug]) {
            return;
        }
        $this->controllers['slider_taxonomies_metabox']->setCurrentTerm($term);
        $this->controllers['slider_taxonomies_metabox']->saveMetadata(array('taxonomy' => $taxonomy, '_post' => $_POST));
    }

    public function addUserForm($user)
    {
        $this->controllers['slider_users_metabox']->setCurrentUser($user);
        $this->controllers['slider_users_metabox']->renderView();
    }

    public function saveUserMetadata($user_id)
    {
        $this->controllers['slider_users_metabox']->saveMetadata(array('user_id' => $user_id, '_post' => $_POST));
    }

    /**
     * Recupera tutti i post attivi di tipo slider.
     *
     * @since  1.0.0
     */
    private function getAllActiveSliders()
    {
        $args = array(
            'post_type' => $this->custom_post_type,
            'post_status' => 'publish',
            'order' => 'ASC',
            'orderby' => 'ID',
            'posts_per_page' => -1,
        );
        $the_query = new WP_Query($args);
        $this->sliders[] = array(
            'ID' => null,
            'title' => '---',
        );
        while ($the_query->have_posts()) :
            $the_query->the_post();
        $this->sliders[] = array(
                'ID' => get_the_ID(),
                'title' => get_the_title(),
            );
        endwhile;

        wp_reset_query();
        wp_reset_postdata();
    }
}
