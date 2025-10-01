<?php
    /**
     * ReduxFramework Theme Config File
     * For full documentation, please visit: https://docs.reduxframework.com
     * */

    if ( ! class_exists( 'Redux_Framework_theme_config' ) ) {

        class Redux_Framework_theme_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }
		

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
         

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }


                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'meathouse' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'meathouse' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'meathouse' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'meathouse' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'meathouse' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo esc_attr($this->theme->display( 'Name' )); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'meathouse' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'meathouse' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'meathouse' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo esc_attr($this->theme->display( 'Description' )); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'meathouse' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'meathouse' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
           
				
				$of_options_fontsize = array("8px" => "8px", "9px" => "9px", "10px" => "10px", "11px" => "11px", "12px" => "12px", "13px" => "13px", "14px" => "14px", "15px" => "15px", "16px" => "16px", "17px" => "17px", "18px" => "18px", "19px" => "19px", "20px" => "20px", "21px" => "21px", "22px" => "22px", "23px" => "23px", "24px" => "24px", "25px" => "25px", "26px" => "26px", "27px" => "27px", "28px" => "28px", "29px" => "29px", "30px" => "30px", "31px" => "31px", "32px" => "32px", "33px" => "33px", "34px" => "34px", "35px" => "35px", "36px" => "36px", "37px" => "37px", "38px" => "38px", "39px" => "39px", "40px" => "40px");
				$of_options_fontweight = array("100" => "100", "200" => "200", "300" => "300", "400" => "400", "500" => "500", "600" => "600", "700" => "700");
				$of_options_font = array("1" => "Google Font", "2" => "Standard Font", "3" => "Custom Font");

				//Standard Fonts
				$of_options_standard_fonts = array(
					'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
					"'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
					"'Bookman Old Style', serif" => "'Bookman Old Style', serif",
					"'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
					"Courier, monospace" => "Courier, monospace",
					"Garamond, serif" => "Garamond, serif",
					"Georgia, serif" => "Georgia, serif",
					"Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
					"'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
					"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
					"'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
					"'MS Serif', 'New York', sans-serif" => "'MS Serif', 'New York', sans-serif",
					"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
					"Tahoma, Geneva, sans-serif" => "Tahoma, Geneva, sans-serif",
					"'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
					"'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
					"Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif"
				);
				
                //lists page
                $lists_page = array();
                $args_page = array(
                    'sort_order' => 'asc',
                    'sort_column' => 'post_title',
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'authors' => '',
                    'child_of' => 0,
                    'parent' => -1,
                    'exclude_tree' => '',
                    'number' => '',
                    'offset' => 0,
                    'post_type' => 'page',
                    'post_status' => 'publish'
                );
                $pages = get_pages( $args_page );

                foreach( $pages as $p ){
                    $lists_page[ $p->ID ] = esc_attr( $p->post_title );
                }
// -> START 
$this->sections[] = array(
    'title' => esc_html__('General', 'meathouse'),
    'id' => 'general',
    'customizer_width' => '300px',
    'icon' => 'el el-cogs',
    'fields' => array(
        array(
            'id'    => 'jws_color_info',
            'type'  => 'info',
            'title' => __('Note!', 'meathouse'),
            'style' => 'warning',
            'desc' => esc_html__('Some settings you need to change with ', 'meathouse') . '<a href="https://elementor.com/help/site-settings/?utm_source=editor-panel&utm_medium=wp-dash&utm_campaign=learn" target="_blank">Elenmentor Site Settings</a>',
        ),
        array(
            'id'        => 'favicon',
            'type'      => 'media',
            'url'       => true,
            'title'     => esc_html__('Favicon', 'meathouse' ),
            'compiler'  => 'false',
            'subtitle'  => esc_html__('Upload your favicon', 'meathouse' ),
        ),
        array(         
            'id'       => 'bg_body',
            'type'     => 'background',
            'title'    =>  esc_html__('Background', 'meathouse'),
            'subtitle' =>  esc_html__('background with image, color, etc.', 'meathouse'),
            'desc'     =>  esc_html__('Change background for body.', 'meathouse'),
            'default'  => array(
                'background-color' => '#ffffff',
            ),
            'output' => array('body'),
        ),
        array (
				'id'       => 'rtl',
				'type'     => 'switch',
				'title'    => esc_html__( 'RTL', 'meathouse' ),
				'default'  => false
		),

        array(
            'id'        => 'container-width',
            'type'      => 'slider',
            'title'     =>  esc_html__('Website Width', 'meathouse'),
            "default"   => 1200,
            "min"       => 700,
            "step"      => 10,
            "max"       => 1920,
            'display_value' => 'label'
        ),

    )
);
// -> START Header Fields
$this->sections[] = array(
    'title' => esc_html__('Header', 'meathouse'),
    'id' => 'header',
    'desc' => esc_html__('Custom Header', 'meathouse'),
    'customizer_width' => '400px',
    'icon' => 'el el-caret-up',
    'fields' => array(
          array(
            'id' => 'choose-header-absolute',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select header absolute.', 'meathouse'),
        ),
         array(
            'id' => 'select-header',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header', 'meathouse'),
            'desc' => esc_html__('Select layout for header from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);

// -> START Title Bar Fields
$this->sections[] = array(
    'title' => esc_html__('Title Bar', 'meathouse'),
    'id' => 'title_bar',
    'desc' => esc_html__('Custom title bar', 'meathouse'),
    'customizer_width' => '400px',
    'icon' => 'el el-text-height',
    'fields' => array(
        array(
            'id'       => 'title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'meathouse'),
            'default'  => true,
        ),
        array(
            'id' => 'select-titlebar',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(         
            'id'       => 'bg_titlebar',
            'type'     => 'background',
            'title'    =>  esc_html__('Background', 'meathouse'),
            'subtitle' =>  esc_html__('background with image, color, etc.', 'meathouse'),
            'desc'     =>  esc_html__('Change background for titler defaul (not woking with title elemetor template).', 'meathouse'),
            'default'  => array(
                'background-color' => '#333333',
            ),
            'output' => array('.jws-title-bar-wrap-inner'),
        ),
        array(
            'id'             => 'titlebar-spacing',
            'type'           => 'spacing',
            'output'         => array('.jws-title-bar-wrap-inner'),
            'mode'           => 'padding',
            'units'          => array('em', 'px'),
            'units_extended' => 'false',
            'desc'           =>  esc_html__('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'meathouse'),
            'default'            => array(
                'padding-top'     => '150px', 
                'padding-right'   => '15px', 
                'padding-bottom'  => '100px', 
                'padding-left'    => '15px',
                'units'          => 'px', 
         ),
        
    ),
));

// -> START footer Fields
$this->sections[] = array(
    'title' => esc_html__('Footer', 'meathouse'),
    'id' => 'footer',
    'desc' => esc_html__('Custom Footer', 'meathouse'),
    'customizer_width' => '400px',
    'icon' => 'el el-caret-down',
    'fields' => array(
        array(
            'id' => 'select-footer',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for footer', 'meathouse'),
            'desc' => esc_html__('Select layout for footer from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);

// -> START Color Fields
$this->sections[] = array(
    'title' => esc_html__('Color Styling', 'meathouse'),
    'id' => 'global-color',
    'desc' => esc_html__('These are really color fields!', 'meathouse'),
    'customizer_width' => '400px',
    'icon' => 'el el-brush',
);
$this->sections[] = array(
    'title' => esc_html__('Color', 'meathouse'),
    'id' => 'color-styling',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'main-color',
            'type' => 'color',
            'title' => esc_html__('Main Color', 'meathouse'),
            'default' => '#0d354f',
        ),
        array(
            'id' => 'secondary-color',
            'type' => 'color',
            'title' => esc_html__('Secondary Color', 'meathouse'),
            'default' => '#c89263',
        ),
        array(
            'id' => 'third-color',
            'type' => 'color',
            'title' => esc_html__('Third Color', 'meathouse'),
            'default' => '#6e8695',
        ),
        array(
            'id' => 'color_heading',
            'type' => 'color',
            'title' => esc_html__('Color Heading', 'meathouse'),
            'default' => '#0d354f',
        ),
        array(
            'id' => 'color_light',
            'type' => 'color',
            'title' => esc_html__('Color Light', 'meathouse'),
            'default' => '#ffffff',
        ),
        array(
            'id' => 'color_body',
            'type' => 'color',
            'title' => esc_html__('Color Body', 'meathouse'),
            'default' => '#727272',
        ),
    ),
);
$this->sections[] = array(
    'title' => esc_html__('Back To Top', 'meathouse'),
    'id' => 'to-top-styling',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'to-top-color',
            'type' => 'color',
            'title' => esc_html__('Color', 'meathouse'),
            'default' => '#333333',
            'output' => array('.backToTop'),
        ),
    ),
);
$this->sections[] = array(
    'title' => esc_html__('Button', 'meathouse'),
    'id' => 'button-styling',
    'subsection' => true,
    'fields' => array(
          array(
            'id'       => 'button-layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Button Global Skins', 'meathouse'), 
            'options'  => array(
                'default' => 'Default',
            ),
            'default'  => 'default',
        ),
        array(
            'id' => 'button-bgcolor',
            'type' => 'color',
            'title' => esc_html__('Background Color', 'meathouse'),
            'default' => '',
        ),
        array(
            'id' => 'button-bgcolor2',
            'type' => 'color',
            'title' => esc_html__('Background Color 2', 'meathouse'),
            'default' => '',
        ),
        array(
            'id' => 'button-color',
            'type' => 'color',
            'title' => esc_html__('Color', 'meathouse'),
            'default' => '',
        ),
        array(
            'id' => 'button-color2',
            'type' => 'color',
            'title' => esc_html__('Color 2', 'meathouse'),
            'default' => '',
        ),
         array(
            'id' => 'opt-typography-button',
            'type' => 'typography',
            'title' => esc_html__('Font', 'meathouse'),
            'google' => true,
            'color' => false,
            'text-align' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'subsets' => false ,
            'output' => array('body.button-default  .elementor-button, body.button-default .jws-cf7-style .wpcf7-submit, body.button-default .elementor-button.rev-btn'),
        ),
        array(
            'title' => esc_html__('Padding', 'meathouse'),
            'id'             => 'button-padding',
            'type'           => 'spacing',
            'mode'           => 'padding',
            'units'          => array('em', 'px'),
            'units_extended' => 'false',
            'output' => array('body.button-default  .elementor-button, body.button-default .jws-cf7-style .wpcf7-submit, body.button-default .elementor-button.rev-btn'),
        ),

    ),
);


// -> START Blogs Fields
$this->sections[] = array(
    'title' => esc_html__('Blogs', 'meathouse'),
    'id' => 'blogs',
    'customizer_width' => '300px',
    'icon' => 'el el-blogger',
    'fields' => array(
        array(
            'id' => 'select-titlebar-blog-archive',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'position_sidebar',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'meathouse'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'full' => 'No Sidebar',
            ),
            'default' => 'right',
        ),
        array(
            'id' => 'select-sidebar-post-columns',
            'type' => 'select',
            'title' => esc_html__('Select Columns Default', 'meathouse'),
            'options' => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
            'default' => '1',
        ),
        array(
            'id'       => 'blog_layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Blog Skin', 'meathouse'), 
            'options'  => array(
                'grid' => 'Grid',
                'list' => 'List',
            ),
            'default'  => 'grid',
        ),
        array(
            'id' => 'select-sidebar-post',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for sidebar', 'meathouse'),
            'desc' => esc_html__('Select layout from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('position_sidebar', '!=', 'no_sidebar'),
        ),
        array(
            'id'       => 'blog_imagesize',
            'type'     => 'text',
            'title'    =>  esc_html__('Image Size', 'meathouse'),
            'default'  => '866x505'
        ),
        array(
            'id' => 'exclude-blog',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('post'), 'posts_per_page' => -1),
            'title' => esc_html__('Select blog types not show in blog archive page.', 'meathouse'),
        ),
      
    )
);
$this->sections[] = array(
    'title' => esc_html__('Blog Single', 'meathouse'),
    'id' => 'blog-single',
    'subsection' => true,
    'fields' => array(
        array(
            'id'       => 'blog-title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'meathouse'),
            'default'  => true,
        ),
        array(
            'id' => 'select-titlebar-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('blog-title-bar-switch', '=', true),
        ),
        array(
            'id' => 'select-header-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header', 'meathouse'),
            'desc' => esc_html__('Select layout for header from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
         array(
            'id'       => 'blog_single_layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Single Blog Skin', 'meathouse'), 
            'subtitle' =>  esc_html__('No validation can be done on this field type', 'meathouse'),
            'desc'     =>  esc_html__('Choose layout for single blog (comment,meta, author box...)', 'meathouse'),
            'options'  => array(
                'layout1' => 'Layout 1',
            ),
            'default'  => 'layout1',
        ),
        array(
            'id' => 'select-related-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for Related Post', 'meathouse'),
            'desc' => esc_html__('Select layout from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'position_sidebar_blog_single',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'meathouse'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'full' => 'No Sidebar',
            ),
            'default' => 'right',
        ),
         array(
            'id' => 'select-sidebar-post-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for sidebar', 'meathouse'),
            'desc' => esc_html__('Select layout from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('position_sidebar_blog_single', '!=', 'no_sidebar'),
        ),
        array(
            'id'       => 'single_blog_imagesize',
            'type'     => 'text',
            'title'    =>  esc_html__('Single Blog Image Size', 'meathouse'),
            'default'  => '1170x550'
        ),
        array(
            'id'       => 'single_related_imagesize',
            'type'     => 'text',
            'title'    =>  esc_html__('Related Blog Image Size', 'meathouse'),
            'default'  => '420x240'
        ),
        array(
            'id' => 'select-content-before-footer-blog-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select content before footer for archive', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    ),
);

// -> START Blogs Fields

if ( ! function_exists( 'jws_product_rent_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_product_rent_available_fields() {
		$product_attributes = array();
        $fields = array();
		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}
    
		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields[ 'pa_'.$attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}

// -> START Login Register Fields
$this->sections[] = array(
    'title' => esc_html__('Login/Register', 'meathouse'),
    'id' => 'login_register',
    'customizer_width' => '300px',
    'icon' => 'el el-unlock',
    'fields' => array(
            array(
                'id'       => 'select-page-login-register-author',
                'type'     => 'switch', 
                'title'    =>  esc_html__('Switch On Login Register To User Page', 'meathouse'),
                'default'  => false,
            ),
            array(
                'id'       => 'verify_email',
                'type'     => 'switch', 
                'title'    =>  esc_html__('Turn on email verification when the user registers', 'meathouse'),
                'default'  => false,
            ),
            array(
                'id' => 'login_form_redirect',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Form Login Redirect', 'meathouse'),
                'desc' => esc_html__('Select Page Form Login Redirect From: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
            array(
                'id' => 'logout_form_redirect',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Form Logout Redirect', 'meathouse'),
                'desc' => esc_html__('Select Page Form Logout Redirect From: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
            array(
                'id' => 'page_mail',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Verify Email', 'meathouse'),
                'desc' => esc_html__('Select Page Verify Email From: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
    )
);




if ( ! function_exists( 'jws_user_featured_list' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_user_featured_list() {

        $blogusers = get_users( );
        // Array of WP_User objects.
        foreach ( $blogusers as $user ) {
            $fields[$user->ID] = $user->display_name;
        }

		return $fields;
	}
}


// -> START 404
$this->sections[] = array(
    'title' => esc_html__('404 Page', 'meathouse'),
    'id' => '404_page',
    'desc' => esc_html__('Select Layout For 404 Page.', 'meathouse'),
    'customizer_width' => '300px',
    'icon' => 'el el-error',
    'fields' => array(
         array(
            'id'       => '404-off-header',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off Header', 'meathouse'),
            'default'  => false,
        ),  
         array(
            'id'       => '404-off-footer',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off Footer', 'meathouse'),
            'default'  => false,
        ), 
         array(
            'id'       => '404-off-titlebar',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off TitleBar', 'meathouse'),
            'default'  => false,
        ),   
        array(
            'id' => 'select-footer-404',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for footer elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for footer elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
         array(
                'id' => 'select-content-404',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Content 404', 'meathouse'),
                'desc' => esc_html__('Select content 404 from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
         ),
    )
);
if ( ! function_exists( 'jws_product_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_product_available_fields() {
		$product_attributes = array();
        $fields = array();
		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}
    
		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}
// -> START WooCommerce
$this->sections[] = array(
    'title' => esc_html__('WooCommerce', 'meathouse'),
    'id' => 'woocommerce',
    'desc' => esc_html__('Option for WooCommerce', 'meathouse'),
    'customizer_width' => '300px',
    'icon' => 'el el-shopping-cart-sign',
    'fields' => array(
      
     array(
            'id' => 'choose-attr-display',
            'type' => 'select',
            'multi' => true,
            'options'  => jws_product_rent_available_fields(),
            'title' => esc_html__('Select Attributes Display In Grid Product.', 'meathouse'),
     ),
      
    )
);
// -> START Shop
$this->sections[] = array(
    'title' => esc_html__('Shop', 'meathouse'),
    'id' => 'shop',
    'customizer_width' => '300px',
    'icon' => 'el el-shopping-cart',
    'fields' => array( 
        array(
            'id' => 'select-header-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for header elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'select-footer-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for footer elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for footer elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'select-before-footer-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for before footer elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for before footer elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'exclude-product-in-shop',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('product'), 'posts_per_page' => -1),
            'title' => esc_html__('Select product and remove in shop page', 'meathouse'),
        ), 
        array(
            'id' => 'exclude-category-in-shop',
            'type' => 'select',
            'multi' => true,
            'data' => 'terms',
            'args' => array('taxonomy' => array('product_cat'), 'hide_empty' => false),
            'title' => esc_html__('Select category and remove in shop page', 'meathouse'),
        ),
    )
);
$this->sections[] = array(
    'title' => esc_html__('Shop Page', 'meathouse'),
    'id' => 'shop_page',
    'subsection' => true,
    'fields' => array(
         array(
            'id' => 'select-titlebar-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'shop_position_sidebar',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'meathouse'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'no_sidebar' => 'No Sidebar',
            ),
            'default' => 'no_sidebar',
        ),
          array(
            'id'       => 'shop-fullwidth-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Shop Full Width', 'meathouse'),
            'default'  => false,
        ),
        array(
            'id' => 'shop_layout',
            'type' => 'select',
            'title' => esc_html__('Archive Layout', 'meathouse'),
            'options' => array(
                'layout1' => 'Layout 1',
                'layout2' => 'Layout 2',
            ),
            'default' => 'layout1',
        ),
        
        array(
            'id' => 'select-banner-before-product',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for banner before product elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for banner before product elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
 

        array(
            'id'=>'product_per_page',
            'type' => 'text',
            'title' =>  esc_html__('Product Per Page', 'meathouse'),
            'subtitle' => __('Please enter the number', 'meathouse'),
            'validate' => 'no_html',
            'default' => '12',
        ),
        array(
            'id' => 'shop_columns',
            'type' => 'select',
            'title' => esc_html__('Select Columns Default', 'meathouse'),
            'options' => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
            'default' => '3',
        ),
        array(
            'id'       => 'columns_review',
            'type'     => 'checkbox',
            'title'    => __('Columns Review', 'meathouse'), 
            'options'  => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ),
            'default' => array(
                '1' => '1', 
                '2' => '0', 
                '3' => '1',
                '4' => '0'
            )
        ),
        array(
            'id' => 'shop_pagination_layout',
            'type' => 'select',
            'title' => esc_html__('Shop Pagination Layout', 'meathouse'),
            'options' => array(
                'number' => 'Number',
                'loadmore' => 'Load More',
                'infinity' => 'Infinity Loader',
            ),
            'default' => 'number',
        ),

    )
);
$this->sections[] = array(
    'title' => esc_html__('Shop Single', 'meathouse'),
    'id' => 'shop_single',
    'subsection' => true,
    'fields' => array(
         array(
            'id'       => 'product-single-title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'meathouse'),
            'default'  => true,
         ), 
         array(
            'id'       => 'product-single-breadcrumb',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Breadcrumb Content Left', 'meathouse'),
            'default'  => true,
         ),  
         array(
            'id' => 'select-titlebar-shop-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'shop_single_layout',
            'type' => 'select',
            'title' => esc_html__('Shop Layout', 'meathouse'),
            'options' => array(
                'default' => 'Default',
            ),
            'default' => 'default',
        ),
        array(
            'id' => 'shop_single_thumbnail_position',
            'type' => 'select',
            'title' => esc_html__('Thumbnail Position', 'meathouse'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'bottom' => 'Bottom',
                'bottom2' => 'Bottom 4 Item',
            ),
            'default' => 'left',
        ),
        array(
            'id' => 'shop_related_item',
            'type' => 'select',
            'title' => esc_html__('Select Related Item Number', 'meathouse'),
            'options' => array(
                '3' => '3 Item',
                '4' => '4 Item',
            ),
            'default' => '4',
        ),
        array(
            'id' => 'shop_related_layout',
            'type' => 'select',
            'title' => esc_html__('Related Layout', 'meathouse'),
            'options' => array(
                'layout1' => 'Layout 1',
                'layout2' => 'Layout 2',
            ),
            'default' => 'layout1',
        ),

    )
);

$this->sections[] = array(
    'title' => esc_html__('Questions & Answers', 'meathouse'),
    'id' => 'questions_answers',
    'subsection' => true,
    'fields' => array(
         array(
            'id'       => 'auestions-enble',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enble Questions & Answers', 'meathouse'),
            'default'  => true,
            'desc'      =>  esc_html__('Show Questions & Answers To Single Product.', 'meathouse'),
        ),
        array(
            'id'        => 'auestions-number',
            'type'      => 'slider',
            'title'     =>  esc_html__('Number Questions & Answers Display', 'meathouse'),
            "default"   => 3,
            "min"       => 1,
            "step"      => 1,
            "max"       => 99,
            'display_value' => 'label'
        ),
 
    )
);



$this->sections[] = array(
    'title' => esc_html__('My Account', 'meathouse'),
    'id' => 'shop_account',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'select-shop-form-login',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header elementor', 'meathouse'),
            'desc' => esc_html__('Select layout for header elementor from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);


if(!function_exists('jws_option_categories_for_jws')) {
function jws_option_categories_for_jws($taxonomy, $select = 1)
    {
        $data = array();
    
        $query = new \WP_Term_Query(array(
            'hide_empty' => true,
            'taxonomy'   => $taxonomy,
        ));
        if ($select == 1) {
            $data['all'] = 'All';
        }
    
        if (! empty($query->terms)) {
            foreach ($query->terms as $cat) {
                $data[ $cat->slug ] = $cat->name;
            }
        }
    
        return $data;
    }  
}




$this->sections[] = array(
    'title' => esc_html__('Meat Box Setting', 'meathouse'),
    'id' => 'box_meat',
    'customizer_width' => '300px',
    'icon' => 'el el-shopping-cart-sign',
    'fields' => array(
     array(
            'id'       => 'turn_tab_welcome',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enable Tab Welcome', 'meathouse'),
            'default'  => true,
     ),     
     array(
        'id'    => 'jws_box_meat_info',
        'type'  => 'info',
        'title' => __('[jws_box_meat]', 'meathouse'),
        'style' => 'warning',
        'desc'  => __('Use this shortcode to display builder meat box.', 'meathouse')
     ),
     array(
            'id' => 'choose_page_builder',
            'type' => 'select',
            'multi' => false,
            'data' => 'posts',
            'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
            'title' => esc_html__('Select Page Builder', 'meathouse'),
            'desc' => esc_html__('Select from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Page</a>',
    ),
     array(
            'id' => 'include_content_tab_box',
            'type' => 'select',
            'multi' => false,
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select Content For Tabs Pricing Choose Box', 'meathouse'),
            'desc' => esc_html__('Select from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
    ),  
    array(
            'id' => 'include_content_tab_welcome',
            'type' => 'select',
            'multi' => false,
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select Content For Tabs Welcome Choose Box', 'meathouse'),
            'desc' => esc_html__('Select from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('turn_tab_welcome', '=',true),
    ),  
     array(
            'id' => 'choose-attr-display-meat-name',
            'type' => 'select',
            'multi' => false,
            'options'  => jws_product_rent_available_fields(),
            'title' => esc_html__('Select Attributes Display With Product Name.', 'meathouse'),
     ),
     array(
            'id' => 'select-box-item-columns',
            'type' => 'select',
            'title' => esc_html__('Select Columns Product', 'meathouse'),
            'options' => array(
                '3' => '3 Columns',
                '4' => '4 Columns',
                '5' => '5 Columns',
                '6' => '6 Columns',
            ),
            'default' => '4',
      ),
      array(
            'id'       => 'pts_text',
            'type'     => 'text',
            'title'    =>  esc_html__('Pts Text', 'meathouse'),
            'default'  => 'pts'
      ),
  
    )
); 


$this->sections[] = array(
    'title' => esc_html__('Frequency Setting', 'meathouse'),
    'id' => 'frequency_meat',
    'customizer_width' => '300px',
    'subsection' => true,
    'fields' => array(
          
      array(
            'id'         => 'frequency_box',
            'type'       => 'repeater',
            'title'      => __( 'Title', 'meathouse' ),
            'subtitle'   => __( '', 'meathouse' ),
            'desc'       => __( '', 'meathouse' ),
            'group_values' => true, // Group all fields below within the repeater ID
            'item_name' => '', // Add a repeater block name to the Add and Delete buttons
            'fields'     => array(
                array(
                    'id'          => 'frequency_time',
                    'type'        => 'text',
                    'placeholder' => __( 'Frequency Time', 'meathouse' ),
                ),

            )
        )
  
    )
);


$this->sections[] = array(
    'title' => esc_html__('Box Meat Query', 'meathouse'),
    'id' => 'box_meat_query',
    'customizer_width' => '300px',
    'subsection' => true,
    'fields' => array(
     array(
            'id'       => 'query_box_type',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Query Type', 'meathouse'), 
            'options'  => array(
                'all' => 'All product',
                'custom_query' => 'Custom Query',
            ),
            'default'  => 'all',
    ),  
    array(
            'id' => 'include-category-in-box',
            'type' => 'select',
            'multi' => true,
            'options' => jws_option_categories_for_jws('product_cat',2),
            'title' => esc_html__('Choose category for box', 'meathouse'),
            'required' => array('query_box_type', '=', 'custom_query'),
    ),
    array(
            'id' => 'exclude-category-in-box',
            'type' => 'select',
            'multi' => true,
            'data' => 'terms',
            'args' => array('taxonomy' => array('product_cat'), 'hide_empty' => false),
            'title' => esc_html__('Remove category for box', 'meathouse'),
            'required' => array('query_box_type', '=', 'custom_query'),
    ),

    array(
            'id' => 'exclude-product-in-box',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('product'), 'posts_per_page' => -1),
            'title' => esc_html__('Remove product for box', 'meathouse'),
            'required' => array('query_box_type', '=', 'custom_query'),
    ),
    
    array(
            'id'       => 'box_orderby',
            'type'     => 'select',
            'title'    =>  esc_html__('Orderby', 'meathouse'), 
            'options'  => array(
                'date'       => esc_html__('Date', 'meathouse'),
                'menu_order' => esc_html__('Menu order', 'meathouse'),
                'title'      => esc_html__('Title', 'meathouse'),
                'rand'       => esc_html__('Random', 'meathouse'),
            ),
            'default'  => 'date',
    ),  
     array(
            'id'       => 'box_order',
            'type'     => 'select',
            'title'    =>  esc_html__('Order', 'meathouse'), 
            'options'  => array(
                'desc' => esc_html__('DESC', 'meathouse'),
                'asc'  => esc_html__('ASC', 'meathouse'),
            ),
            'default'  => 'desc',
    ),  
    )
);


$this->sections[] = array(
    'title' => esc_html__('Notification Setting', 'meathouse'),
    'id' => 'notification_meat',
    'customizer_width' => '300px',
    'subsection' => true,
    'fields' => array(
      array(
    		'id'     => 'notification_limit',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Notification limit', 'meathouse' ),
    		'indent' => true,
    	),  
      array(
            'id'       => 'notification_limit_title',
            'type'     => 'text',
            'title'    =>  esc_html__('Heading Limit.', 'meathouse'),
            'default'  => 'Max quanlity in box!'
      ),
       array(
            'id'       => 'notification_limit_text',
            'type'     => 'textarea',
            'title'    =>  esc_html__('Description Limit.', 'meathouse'),
            'default'  => 'To add more products to your box, you must remove a product'
      ),
      
      
      array(
    		'id'     => 'notification_success',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Notification success', 'meathouse' ),
    		'indent' => true,
      ),  
      array(
            'id'       => 'notification_success_title',
            'type'     => 'text',
            'title'    =>  esc_html__('Heading Success.', 'meathouse'),
            'default'  => 'Success'
      ),
       array(
            'id'       => 'notification_success_text',
            'type'     => 'textarea',
            'title'    =>  esc_html__('Description Success.', 'meathouse'),
            'default'  => 'The box is full.'
      ),
      
      
      array(
    		'id'     => 'notification_select_options',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Notification Select Options', 'meathouse' ),
    		'indent' => true,
      ),  
      array(
            'id'       => 'notification_select_options_title',
            'type'     => 'text',
            'title'    =>  esc_html__('Heading Select Options.', 'meathouse'),
            'default'  => 'Hi!'
      ),
       array(
            'id'       => 'notification_select_options_text',
            'type'     => 'textarea',
            'title'    =>  esc_html__('Description Select Options.', 'meathouse'),
            'default'  => 'Please Select options'
      ),
      

      array(
    		'id'     => 'remaining_section',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Remaining', 'meathouse' ),
    		'indent' => true,
      ),  
      array(
            'id'       => 'remaining_text_left',
            'type'     => 'text',
            'title'    =>  esc_html__('Remaining Text Left.', 'meathouse'),
            'default'  => 'Fill your box to continue -',
            'desc' => esc_html__('Text before points remaining.', 'meathouse'),
      ),
      array(
            'id'       => 'remaining_text_right',
            'type'     => 'text',
            'title'    =>  esc_html__('Remaining Text Right.', 'meathouse'),
            'default'  => 'points remaining',
            'desc' => esc_html__('Text after points remaining.', 'meathouse'),
      ),
            
    )
);


// -> START Typography
$this->sections[] = array(
    'title' => esc_html__('Typography', 'meathouse'),
    'id' => 'typography',
    'icon' => 'el el-text-width',
    'fields' => array(
        array(
            'id' => 'opt-typography-body',
            'type' => 'typography',
            'title' => esc_html__('Body Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the body font properties.', 'meathouse'),
            'google' => true,
            'color' => true,
            'subsets' => false,
            'output' => array('body'),
        ),
        
         array(
            'id' => 'opt-typography-font2',
            'type' => 'typography',
            'title' => esc_html__('Font 2', 'meathouse'),
            'google' => true,
            'color' => false,
            'text-align' => false,
            'letter-spacing' => false,
            'text-transform' => false,
            'font-size' => false,
            'subsets' => false,
            'font-weight' => false,
            'line-height' => false
        ),
        
        array(
            'id' => 'opt-typography-h1',
            'type' => 'typography',
            'title' => esc_html__('H1 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h1 font properties.', 'meathouse'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h1'),
        ),
        array(
            'id' => 'opt-typography-h2',
            'type' => 'typography',
            'title' => esc_html__('H2 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h2 font properties.', 'meathouse'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h2'),
        ),
        array(
            'id' => 'opt-typography-h3',
            'type' => 'typography',
            'title' => esc_html__('H3 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h3 font properties.', 'meathouse'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h3'),
        ),
        array(
            'id' => 'opt-typography-h4',
            'type' => 'typography',
            'title' => esc_html__('H4 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h4 font properties.', 'meathouse'),
            'google' => true,
            'color' => false,
            'subsets' => false,
            'output' => array('h4'),
        ),
        array(
            'id' => 'opt-typography-h5',
            'type' => 'typography',
            'title' => esc_html__('H5 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h5 font properties.', 'meathouse'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h5'),
        ),
        array(
            'id' => 'opt-typography-h6',
            'type' => 'typography',
            'title' => esc_html__('H6 Font', 'meathouse'),
            'subtitle' => esc_html__('Specify the h6 font properties.', 'meathouse'),
            'google' => true,
            'color' => false,
            'subsets' => false,
            'output' => array('h6'),
        ),
        
        
  
  
    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('API And Other Setting', 'meathouse'),
    'id' => 'api',
    'customizer_width' => '300px',
    'icon' => 'el el-network',
    'fields' => array(
        array(
    		'id'     => 'google-api-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Google Settings', 'meathouse' ),
    		'indent' => true,
    	),
        array(
            'id' => 'google_api',
            'type' => 'text',
            'title' => esc_html__('Google API Key', 'meathouse'),
            'default' => '',
        ),
        array(
    		'id'     => 'google-api-section-end',
    		'type'   => 'section',
    		'indent' => false,
    	),

        array(
    		'id'     => 'instagram-token-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Instagram Settings', 'meathouse' ),
    		'indent' => true,
    	),
        array(
            'id' => 'instagram_token',
            'type' => 'text',
            'title' => esc_html__('Instagram Token', 'meathouse'),
            'default' => '',
        ),
        array(
    		'id'     => 'addthis-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Add This Share Settings', 'meathouse' ),
    		'indent' => true,
    	),
        array(
            'id' => 'addthis_url',
            'type' => 'text',
            'title' => esc_html__('Addthis Url', 'meathouse'),
            'default' => '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-562f7aa6d38d8980',
        ),
        array(
    		'id'     => 'facebook-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Facebook Login Settings', 'meathouse' ),
    		'indent' => true,
    	),
        array(
            'id' => 'facebook_app_id',
            'type' => 'text',
            'title' => esc_html__('App Id', 'meathouse'),
        ),
        array(
            'id' => 'facebook_app_secret',
            'type' => 'text',
            'title' => esc_html__('App Secret', 'meathouse'),
        ),
        
        array(
    		'id'     => 'google-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Google Login Settings', 'meathouse' ),
    		'indent' => true,
    	),
        array(
            'id' => 'google_app_id',
            'type' => 'text',
            'title' => esc_html__('App Id', 'meathouse'),
        ),
        array(
            'id' => 'google_app_secret',
            'type' => 'text',
            'title' => esc_html__('App Secret', 'meathouse'),
        ),
   
    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('Toolbar Popup', 'meathouse'),
    'id' => 'toolbarfix',
    'customizer_width' => '300px',
    'icon' => 'el el-minus',
    'fields' => array(
        array (
				'id'       => 'toolbar_fix',
				'type'     => 'switch',
				'title'    => esc_html__( 'Toolbar Nav For Tablet And Mobile', 'meathouse' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_shop',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Shop', 'meathouse' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_search',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Search', 'meathouse' ),
				'default'  => true
		),

        array (
				'id'       => 'toolbar_account',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Account', 'meathouse' ),
				'default'  => true
		),
        array(
            'id'         => 'toolbar_custom',
            'type'       => 'repeater',
            'title'      => __( 'Tool Bar Custom', 'meathouse' ),
            'subtitle'   => __( '', 'meathouse' ),
            'desc'       => __( '', 'meathouse' ),
            'group_values' => true, // Group all fields below within the repeater ID
            'item_name' => '', // Add a repeater block name to the Add and Delete buttons
            'fields'     => array(
                array(
                    'id'          => 'toolbar_custom_name',
                    'type'        => 'text',
                    'placeholder' => __( 'Name', 'meathouse' ),
                ),
                array(
                    'id'          => 'toolbar_custom_icon',
                    'type'        => 'text',
                    'placeholder' => __( 'Icon Class', 'meathouse' ),
                ),
                array(
                    'id'          => 'toolbar_custom_link',
                    'type'        => 'text',
                    'placeholder' => __( 'Link', 'meathouse' ),
                ),

            )
        )

    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('Newsletter Popup', 'meathouse'),
    'id' => 'newsletter_popup',
    'customizer_width' => '300px',
    'icon' => 'el el-envelope',
    'fields' => array(
       array(
            'id'       => 'newsletter_enble',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enble Newsletter', 'meathouse'),
            'default'  => false,
            'desc'      =>  esc_html__('Enble Newsletter Popup For Site.', 'meathouse'),
       ), 
        array(
            'id' => 'newsletter_content',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select template for popup', 'meathouse'),
            'desc' => esc_html__('Select template for popup from: ', 'meathouse') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id'        => 'time_delay_show',
            'type'      => 'slider',
            'title'     =>  esc_html__('Popup Delay Show (millisecond)', 'meathouse'),
            "default"   => 3000,
            "min"       => 0,
            "step"      => 10,
            "max"       => 100000,
            'display_value' => 'label'
        ),
        array(
            'id'        => 'time_open_after_close',
            'type'      => 'slider',
            'title'     =>  esc_html__('Time Open After close. (hours )', 'meathouse'),
            "default"   => 24,
            "min"       => 0,
            "step"      => 1,
            "max"       => 1000,
            'display_value' => 'label'
        ),
    )
);

if (file_exists(dirname(__FILE__) . '/../README.md')) {
    $this->sections[] = array(
        'icon' => 'el el-list-alt',
        'title' => esc_html__('Documentation', 'meathouse'),
        'fields' => array(
            array(
                'id' => '17',
                'type' => 'raw',
                'markdown' => true,
                'content_path' => dirname(__FILE__) . '/../README.md', // FULL PATH, not relative please
                //'content' => 'Raw content here',
            ),
        ),
    );

}
				
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'meathouse' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'meathouse' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'meathouse' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'meathouse' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'meathouse' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'jws_option',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'submenu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'meathouse' ),
                    'page_title'           => __( 'Theme Options', 'meathouse' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => false,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'jws_settings.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => 'jws_option',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );
				
                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Visit us on GitHub',
                    'icon'  => 'el-icon-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Find us on LinkedIn',
                    'icon'  => 'el-icon-linkedin'
                );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_theme_config();
    } else {
        echo "The class named Redux_Framework_theme_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;


    if( ! function_exists('jws_theme_get_option') ){
        function jws_theme_get_option($name, $default=false){
            global $jws_option;
            return isset( $jws_option[ $name ] ) ? $jws_option[ $name ] : $default;
        }
    }

    if( ! function_exists('jws_theme_update_option') ){
        function jws_theme_update_option($name, $value){
            global $jws_option;
            $jws_option[ $name ] = $value;
        }
    }

