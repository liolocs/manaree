<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_Slider extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
     

	public function get_name() {
		return 'jws_slider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Jws slider', 'meathouse' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'jws-elements' ];
	}
 
    public function get_tabs_list() { 
        
        global $jws_option;
        
        
        if(isset($jws_option['slider_category']) && !empty($jws_option['slider_category'])) {
          
    
      
            $tabsok = array();
            foreach (  $jws_option['slider_category'] as $index => $item_tabs ) { 
              $tabsok[ preg_replace('/[^a-zA-Z]+/', '', $item_tabs) ] = $item_tabs;     
           
            };  
            return $tabsok;
        }
        
    
    }
    /**
     * Load style
     */
    public function get_style_depends()
    {
        return [''];
    }

    /**
     * Retrieve the list of scripts the image carousel widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [''];
    }
 
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

 
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'slider List', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();
        
        
        $repeater->add_control(
				'select_template',
				[
					'label'     => esc_html__( 'Select Template', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'multiple'  => true,
					'default'   => '',
					'options'   => $this->get_saved_data(),
				]
		);
 
         $repeater->add_control(
			'change_special',
			[
				'label'        => esc_html__( 'Change logo,color for this section', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        
   
        $repeater->add_control(
			'menu_color',
			[
				'label' => esc_html__( 'Menu Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body{{CURRENT_ITEM}} .elementor_jws_menu_layout_menu_horizontal .jws_main_menu .jws_main_menu_inner>ul>li>a , body{{CURRENT_ITEM}} .jws_search.popup > button , body{{CURRENT_ITEM}} .jws-offcanvas-trigger .elementor-button-icon i' => 'color: {{VALUE}} !important',
				],
                'condition' => [
					'change_special'  => 'yes',
				],
			]
		);
        $repeater->add_control(
			'menu_color_2',
			[
				'label' => esc_html__( 'Menu Color Active (Hover)', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body{{CURRENT_ITEM}} .elementor_jws_menu_layout_menu_horizontal .jws_main_menu .jws_main_menu_inner>ul>li.current-menu-parent>a' => 'color: {{VALUE}} !important',
                    'body{{CURRENT_ITEM}} .elementor_jws_menu_layout_menu_horizontal .jws_main_menu .jws_main_menu_inner>ul>li>a:hover' => 'color: {{VALUE}} !important',
				],
                'condition' => [
					'change_special'  => 'yes',
				],
			]
		);
        $repeater->add_control(
			'main_color',
			[
				'label' => esc_html__( 'Main Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body{{CURRENT_ITEM}} .elementor_jws_menu_layout_menu_horizontal.elementor-before-menu-skin-background_animation > .elementor-widget-container > .jws_main_menu > .jws_main_menu_inner > .nav > li > a:before' => 'background: {{VALUE}} !important',
				],
                'condition' => [
					'change_special'  => 'yes',
				],
			]
		);
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Menu List', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'setting_navigation',
			[
				'label' => esc_html__( 'Setting Navigation', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
    				'enable_nav',
    				[
    					'label'        => esc_html__( 'Enable Nav', 'meathouse' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'meathouse' ),
    					'label_off'    => esc_html__( 'No', 'meathouse' ),
    					'return_value' => 'yes',
    					'default'      => 'yes',
    					'description'  => esc_html__( 'Enable nav arrow.', 'meathouse' ),
    				]
    	);
        
   
        $this->add_control(
    				'enable_dots',
    				[
    					'label'        => esc_html__( 'Enable Dots', 'meathouse' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'meathouse' ),
    					'label_off'    => esc_html__( 'No', 'meathouse' ),
    					'return_value' => 'yes',
    					'default'      => 'yes',
    					'description'  => esc_html__( 'Enable dot.', 'meathouse' ),
    				]
    	);

        $this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws_slider .flickity-page-dots li.is-selected' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .jws_slider_element .jws_slider .flickity-page-dots li:before' => 'background: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();  
        
      $this->start_controls_section(
			'setting_arrow',
			[
				'label' => esc_html__( 'Arrow Navigation', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
					'enable_nav'             => 'yes',
				],
			]
		);
        
        $this->add_control(
			'nav_arrow_layout',
			[
				'label' => esc_html__( 'Arrow Layout', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'meathouse' ),
                    'layout2'  => esc_html__( 'Layout 2', 'meathouse' ),
				],
			]
		);
        
        $this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws-nav-carousel > div' => '--arrow_color: {{VALUE}}',
				],
			]
		);
        
        $this->add_control(
			'arrow_bd_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws-nav-carousel > div' => '--arrow_border_color: {{VALUE}}',
				],
			]
		);
        
           
         $this->add_responsive_control(
			'arrow_vertical',
			[
				'label' => esc_html__( 'Vertical Position', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws-nav-carousel > div' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
       $this->end_controls_section(); 
        
       $this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'meathouse' ),
				'type'      => Controls_Manager::SECTION,
			]
		);


		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'posts to Show', 'meathouse' ),
				'type'           => Controls_Manager::NUMBER,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'posts to Scroll', 'meathouse' ),
				'type'           => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'meathouse' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'selectors' => [
					'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
				],
				'condition' => [
					'autoplay'             => 'yes',
				],
			]
		);
		$this->add_control(
			'pause_on_hover',
			[
				'label'        => esc_html__( 'Pause on Hover', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'autoplay'             => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'        => esc_html__( 'Infinite Loop', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
         $this->add_control(
			'direction',
			[
				'label'     => esc_html__( 'Direction', 'meathouse' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
                    'horizontal' => esc_html__( 'Horizontal', 'meathouse' ),
					'vertical' => esc_html__( 'Vertical', 'meathouse' ),
				],
                'default'        => 'horizontal',
			]
		);
        $this->add_control(
			'fade',
			[
				'label'        => esc_html__( 'Fade Mode', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        $this->add_control(
			'center',
			[
				'label'        => esc_html__( 'Cener Mode', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        
        $this->add_control(
			'mousewheel',
			[
				'label'        => esc_html__( 'Mousewheel Mode', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
       
        $this->add_control(
			'variablewidth',
			[
				'label'        => esc_html__( 'variable Width', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label'     => esc_html__( 'Transition Speed (ms)', 'meathouse' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
			]
		);
		$this->end_controls_section();
        
        $this->start_controls_section(
			'slides_style',
			[
				'label' => esc_html__( 'Slides', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
       
        
         $this->add_responsive_control(
			'slider_height',
			[
				'label' => esc_html__( 'Slider Height', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws_slider .slider-item' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
  
        $this->end_controls_section();


	}
    
    public function get_saved_data(  ) {
        
      
      
        global $post;
        $posts = get_posts( array( 'post_type' => 'hf_template' , 'orderby'=> 'title' , 'order' => 'ASC' , 'posts_per_page' => -1  ) );
        if( $posts ){
           foreach( $posts as $post ) :   
           
           $options[$post->ID] = $post->post_title;
           
        endforeach; 
        wp_reset_postdata(); 
        }else {
           $options['no_template'] = esc_html__( 'It seems that, you have not saved any template yet.', 'meathouse' ); 
        }
  
		return $options;
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
	   
		$settings = $this->get_settings_for_display();
    
         $class_column = '';
    
         $class_row = 'jws_slider'; 


        $class_column .= ' slider-item slick-slide'; 
        $dots = ($settings['enable_dots'] == 'yes') ? 'true' : 'false';
        $arrows = ($settings['enable_nav'] == 'yes') ? 'true' : 'false';
        $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
        $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
        $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
        $variableWidth = ($settings['variablewidth'] == 'yes') ? 'true' : 'false';
        $fade = ($settings['fade'] == 'yes') ? 'true' : 'false';
        
        $center = ($settings['center'] == 'yes') ? 'true' : 'false';
        
        $vertical = ($settings['direction'] == 'vertical') ? 'true' : 'false';
        
        if($settings['mousewheel'] == 'yes') {
           $class_row .= ' slick_wheel'; 
        }
       
        
        $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '1';
        $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
        $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
        $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
        $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
        $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
        
        
        $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';

        $data_slick = 'data-slick=\'{
        "slidesToShow":'.$settings['slides_to_show'].' ,
        "slidesToScroll": '.$settings['slides_to_scroll'].', 
        "autoplay": '.$autoplay.',
        "arrows": '.$arrows.', 
        "dots":'.$dots.', 
        "autoplaySpeed": '.$autoplay_speed.',
        "variableWidth":'.$variableWidth.',
        "pauseOnHover":'.$pause_on_hover.',
        "centerMode":'.$center.', 
        "infinite":'.$infinite.',
        "fade":'.$fade.',
        "vertical":'.$vertical.',
        "speed": '.$settings['transition_speed'].', 
        "responsive":[
            {"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
            {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}
        ]}\''; 


         ?>
         <div class="jws_slider_element jws-carousel">

            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?>>
                
                    <?php foreach (  $settings['list'] as $index => $item ) {
        
                        if($item['change_special'] == 'yes') {
                           $special = ' special_section';  
                        }else {
                           $special = ''; 
                        }
                        
                        ?>
                        <div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?><?php echo esc_attr($class_column.$special); ?>" data-slider="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                            <?php if(!empty($item['select_template']))  echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['select_template'] , true );   ?>
                        </div>
                     <?php } ?>
                   
             <?php 
                if($settings['enable_dots'] == 'yes') {echo '<div class="slider-dots-box"></div> ';}
                if($settings['enable_nav'] == 'yes') {
                    if($settings['nav_arrow_layout'] == 'layout1') {
                        echo '<div class="jws-nav-carousel layout1"><div class="nav_left jws-icon-glyph"></div><div class="nav_right jws-icon-glyph-1"></div></div>';
                    }else {
                        echo '<div class="jws-nav-carousel layout2"><div class="nav_left"><span>'.esc_html__('PREV','meathouse').'</span></div><div class="nav_right"><span>'.esc_html__('NEXT','meathouse').'</span></div></div>';
                    }
                }
             ?>        
                  
            </div>
          
         </div>   
        <?php

	}
    


	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
}