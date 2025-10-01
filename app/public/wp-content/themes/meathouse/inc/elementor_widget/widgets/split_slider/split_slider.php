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
class Jws_Split_Slider extends Widget_Base {

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
		return 'jws_split_slider';
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
		return esc_html__( 'Jws Split Slider', 'meathouse' );
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
		return 'eicon-split_slider-grid';
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
        
        
        if(isset($jws_option['split_slider_category']) && !empty($jws_option['split_slider_category'])) {
          
    
      
            $tabsok = array();
            foreach (  $jws_option['split_slider_category'] as $index => $item_tabs ) { 
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
        return ['jquery-multiscroll'];
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
        return ['jquery-multiscroll','jquery-easing'];
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
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        

        $this->add_control(
			'split_slider_layout',
			[
				'label' => esc_html__( 'Layout', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'meathouse' ),
                    'layout2'  => esc_html__( 'Layout Demo', 'meathouse' ),
				],
			]
		);

        $this->end_controls_section(); 
 
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'split_slider List', 'meathouse' ),
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
				'select_template2',
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
			'reverse_mobile',
			[
				'label'        => esc_html__( 'Reverse Columns (Mobile)', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
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
					'{{WRAPPER}} .jws_split_slider_element .jws_split_slider .flickity-page-dots li.is-selected' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .jws_split_slider_element .jws_split_slider .flickity-page-dots li:before' => 'background: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();  

        	$this->start_controls_section(
			'section_split_slider_options',
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
			'center',
			[
				'label'        => esc_html__( 'Cener Mode', 'meathouse' ),
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
        
        $this->add_control(
			'split_slider_content_position',
			[
				'label' => esc_html__( 'Position', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'top', 'meathouse' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'meathouse' ),
						'icon' => 'eicon-h-align-center',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'meathouse' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'toggle' => true,
			]
		);
 
        
         $this->add_responsive_control(
			'split_slider_height',
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
					'{{WRAPPER}} .jws_split_slider_element .jws_split_slider' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
         $this->add_responsive_control(
			'split_slider_content_padding',
			[
				'label' 		=> esc_html__( 'Slider Content Padding', 'meathouse' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .split_slider-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
			'split_slider_content_margin',
			[
				'label' 		=> esc_html__( 'Slider Content Margin', 'meathouse' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .split_slider-content-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .split_slider-title' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .split_slider-title',
			]
		);
        
        $this->add_responsive_control(
			'title-width',
			[
				'label' => esc_html__( 'Width', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-title' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'title_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'meathouse' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .split_slider-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
			'description_style',
			[
				'label' => esc_html__( 'Description', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .split_slider-description' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .split_slider-description',
			]
		);
        
        $this->add_responsive_control(
			'description-width',
			[
				'label' => esc_html__( 'Width', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-description' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'description_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'meathouse' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .split_slider-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
			'button_style',
			[
				'label' => esc_html__( 'Button', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .split_slider-button' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .split_slider-button',
			]
		);

        $this->add_responsive_control(
			'button_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'meathouse' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .split_slider-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        
        $this->start_controls_section(
			'mix_content_style',
			[
				'label' => esc_html__( 'Mix Content', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs(
        	'min_content_tabs'
        );
        
        $this->start_controls_tab(
        	'style_image1_tab',
        	[
        		'label' => esc_html__( 'Image 1', 'meathouse' ),
        	]
        );
         $this->add_responsive_control(
			'mix-image-width',
			[
				'label' => esc_html__( 'Width', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-1 img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
             
        $this->add_responsive_control(
			'mix-image-position-left',
			[
				'label' => esc_html__( 'Left', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-1' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-right',
			[
				'label' => esc_html__( 'Right', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-1' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-top',
			[
				'label' => esc_html__( 'Top', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-1' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-bottom',
			[
				'label' => esc_html__( 'Bottom', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-1' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
        $this->start_controls_tab(
        	'style_image2_tab',
        	[
        		'label' => esc_html__( 'Image 2', 'meathouse' ),
        	]
        );
         $this->add_responsive_control(
			'mix-image2-width',
			[
				'label' => esc_html__( 'Width', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-2 img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        
        $this->add_responsive_control(
			'mix-image2-position-left',
			[
				'label' => esc_html__( 'Left', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-2' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-right',
			[
				'label' => esc_html__( 'Right', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-2' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-top',
			[
				'label' => esc_html__( 'Top', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-2' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-bottom',
			[
				'label' => esc_html__( 'Bottom', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-mix-2' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
           $this->start_controls_tab(
        	'style_split_slider_dots_tab',
        	[
        		'label' => esc_html__( 'Dots Slider', 'meathouse' ),
        	]
        );
         $this->add_responsive_control(
			'split_slider-dots-position',
			[
				'label' => esc_html__( 'Position', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .split_slider-dots-box' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
     

        $this->end_controls_section();
        

	}
    
    public function get_saved_data(  ) {
        
      
      
        global $post;
        $posts = get_posts(
             array( 
                'post_type' => 'hf_template',
                'posts_per_page' => -1,    
                'tax_query' => array(
            		array(
            			'taxonomy' => 'hf_template_cat',
            			'field'    => 'slug',
            			'terms'    => 'slider'
            		)
            	)
             )
        );
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
    
        $class_column = ' jws_split_slider_item';
    
        $class_row = 'jws_split_slider split_slider '.$settings['split_slider_layout']; 
        $class_row .= ' split_slider_content_position-'.$settings['split_slider_content_position'];  

         
        $class_row .= ' jws-split_slider';
        $class_column .= ' split_slider-item'; 
   

         ?>
         <div class="jws_split_slider_element">

            <div id="myContainer" class="<?php echo esc_attr($class_row); ?>">
                    <div class="ms-left">
                        <?php foreach (  $settings['list'] as $index => $item ) { 
                            if($item['change_special'] == 'yes') {
                               $special = ' special_section';  
                            }else {
                               $special = ''; 
                            }
                            
                            $reverse_mobile = $item['reverse_mobile'] == 'yes' ? ' reverse_mobile' : '';
                            ?>
                            <div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?><?php echo esc_attr($class_column.$special.$reverse_mobile); ?> ms-section">
                                <?php  if(!empty($item['select_template']))  echo Plugin::$instance->frontend->get_builder_content($item['select_template'], true);    ?>
                                <div class="split-mobile"><?php if(!empty($item['select_template2']))  echo Plugin::$instance->frontend->get_builder_content($item['select_template2'], true);    ?></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="ms-right">
                        <?php foreach (  $settings['list'] as $index => $item ) { ?>
                            <div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?><?php echo esc_attr($class_column); ?> ms-section">
                                <?php if(!empty($item['select_template2']))  echo Plugin::$instance->frontend->get_builder_content($item['select_template2'], true);    ?>
                            </div>
                        <?php } ?>
                    </div>
                
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