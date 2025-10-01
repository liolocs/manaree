<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Testimonial_Slider extends Widget_Base {

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
		return 'jws_testimonial_slider';
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
		return esc_html__( 'Jws Testimonial Slider', 'meathouse' );
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
		return 'eicon-testimonial';
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
			'content_section',
			[
				'label' => esc_html__( 'Menu List', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
				'slider_layouts',
				[
					'label'     => esc_html__( 'Layout', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'layout1',
					'options'   => [
						'layout1'   => esc_html__( 'layout 1', 'meathouse' ),
						'layout2'   => esc_html__( 'layout 2', 'meathouse' ),
                        'layout3'   => esc_html__( 'layout 3', 'meathouse' ), 
					],
				]
		);
 
		$repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Avatar', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        $repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
			]
		);
        $repeater->add_control(
			'list_url',
			[
				'label' => esc_html__( 'Link', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'meathouse' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
				],
			]
		);
		$repeater->add_control(
			'list_name', [
				'label' => esc_html__( 'Name', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Name' , 'meathouse' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_job', [
				'label' => esc_html__( 'Job', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Job' , 'meathouse' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_description', [
				'label' => esc_html__( 'Description', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => esc_html__( 'Default description', 'meathouse' ),
				'placeholder' => esc_html__( 'Type your description here', 'meathouse' ),
			]
		);
		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Menu List', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_name' => esc_html__( 'Name #1', 'meathouse' ),
					],
				],
				'title_field' => '{{{ list_name }}}',
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

		$this->add_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'meathouse' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'both',
				'options'   => [
                    'both' => esc_html__( 'Arrows And Dots', 'meathouse' ),
					'arrows' => esc_html__( 'Arrows', 'meathouse' ),
                    'dots' => esc_html__( 'Dots', 'meathouse' ),
					'none'   => esc_html__( 'None', 'meathouse' ),
				],
			]
		);
        
        $this->add_control(
			'navigation_style',
			[
				'label'     => esc_html__( 'Navigation Style', 'meathouse' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
                    'default' => esc_html__( 'Default', 'meathouse' ),
					'style2' => esc_html__( 'Style 2', 'meathouse' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'posts to Show', 'meathouse' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 1,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'posts to Scroll', 'meathouse' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 1,
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
			'center_mode',
			[
				'label'        => esc_html__( 'Center Mode', 'meathouse' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        	$this->add_responsive_control(
			'center_padding',
			[
				'label'     => esc_html__( 'Center Padding', 'meathouse' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'center_mode'             => 'yes',
				],
                'selectors' => [
					'{{WRAPPER}} .slider_layout_layout5 + .custom_navs button.nav_left' => 'left: {{VALUE}};',
                    '{{WRAPPER}} .slider_layout_layout5 + .custom_navs button.nav_right' => 'right: {{VALUE}};',
				],
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
			'transition_speed',
			[
				'label'     => esc_html__( 'Transition Speed (ms)', 'meathouse' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
			]
		);
		$this->end_controls_section();
        $this->start_controls_section(
			'testimonials_slider_style',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
       $this->add_responsive_control(
			'column_gap',
			[
				'label'     => esc_html__( 'Columns Gap', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .slick-slide' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .slider_layout_layout5 + .custom_navs button.nav_left' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 + 25px );',
                    '{{WRAPPER}} .slider_layout_layout5 + .custom_navs button.nav_right' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 + 25px );',
				],
			]
		);
        $this->add_responsive_control(
			'width_slider',
			[
				'label'     => esc_html__( 'Width Slider', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 300,
						'max' => 1920,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .testimonials_slider .slick-list' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
					'box_bgcolor',
					[
						'label' 	=> esc_html__( 'Box Background Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .slider_content' => 'background: {{VALUE}} !important;',
						],
					]
		);
        $this->add_responsive_control(
					'testimonials_slider_margin',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .testimonials_slider .slider_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        
        $this->add_responsive_control(
					'testimonials_slider_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .testimonials_slider .slider_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);

        $this->add_control(
			'testimonials_slider_des',
			[
				'label' => esc_html__( 'Description', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'testimonials_slider_description_color',
					[
						'label' 	=> esc_html__( 'Description Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_description' => 'color: {{VALUE}} !important;',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonials_slider_description_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_description',
			]
		);
         $this->add_responsive_control(
					'description_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_control(
			'testimonials_slider_name',
			[
				'label' => esc_html__( 'Name', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'testimonials_slider_name_color',
					[
						'label' 	=> esc_html__( 'Name Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '#333333',
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_title' => 'color: {{VALUE}} !important;',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonials_slider_name_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_title',
			]
		);
        $this->add_responsive_control(
					'name_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_control(
			'testimonials_slider_job',
			[
				'label' => esc_html__( 'job', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'testimonials_slider_job_color',
					[
						'label' 	=> esc_html__( 'job Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '#333333',
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_job' => 'color: {{VALUE}} !important;',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonials_slider_job_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_job',
			]
		);
        $this->add_responsive_control(
					'job_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider .testimonials_job' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
         $this->add_control(
			'testimonials_slider_icon',
			[
				'label' => esc_html__( 'Icon', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'icon_color',
					[
						'label' 	=> esc_html__( 'Icon Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .testimonials_slider .testimonials_icon' => 'color: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'icon_bgcolor',
					[
						'label' 	=> esc_html__( 'Icon Background Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .testimonials_slider .testimonials_icon' => 'background: {{VALUE}} !important;',
						],
					]
		);
        $this->add_control(
				'icon_size',
				[
					'label' 		=> esc_html__( 'Icon Size', 'meathouse' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .testimonials_slider .testimonials_icon' => 'font-size: {{SIZE}}px;',
					],
				]
		);
        $this->add_responsive_control(
					'icon_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .testimonials_slider .testimonials_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_control(
			'testimonials_slider_avatar',
			[
				'label' => esc_html__( 'Avatar', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
					'testimonials_slider_avatar_box_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'testimonials_slider_avatar_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws_testimonials_slider_wrap .testimonials_slider img',
			]
		);

        $this->end_controls_section();
        $this->start_controls_section(
			'testimonials_slider_dot_style',
			[
				'label' => esc_html__( 'Dots', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
         $this->add_control(
					'dot_color',
					[
						'label' 	=> esc_html__( 'Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .custom_dots .slick-dots li:before' => 'background: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'dot_color_active',
					[
						'label' 	=> esc_html__( 'Color Active', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .custom_dots .slick-dots li.slick-active:before' => 'background: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'dot_brcolor_active',
					[
						'label' 	=> esc_html__( 'Border Color Active', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .custom_dots .slick-dots li.slick-active' => 'border-color: {{VALUE}};',
						],
					]
		);
        $this->end_controls_section();
                $this->start_controls_section(
			'testimonials_slider_nav_style',
			[
				'label' => esc_html__( 'Navs', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
					'nav_color',
					[
						'label' 	=> esc_html__( 'Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .custom_navs .slick-arrow' => 'color: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'nav_color_active',
					[
						'label' 	=> esc_html__( 'Color Active', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_testimonials_slider_wrap .custom_navs .slick-arrow:hover' => 'color: {{VALUE}};',
						],
					]
		);
         $this->add_responsive_control(
			'nav_size',
			[
				'label'     => esc_html__( 'Nav Size', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_testimonials_slider_wrap .custom_navs .slick-arrow' => 'font-size:{{SIZE}}px;',
				],
			]
		);
        $this->end_controls_section();
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
		$settings = $this->get_settings();
          
            $classes = $settings['slider_layouts'];
            $classes .= isset($settings['thumbnail_slider_position']) ? ' thumbnail_position_'.$settings['thumbnail_slider_position'] : '';
            
            $dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $center = ($settings['center_mode'] == 'yes') ? 'true' : 'false';
            $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
            $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
            $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
            $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
            $vertical = ($settings['direction'] == 'vertical') ? 'true' : 'false';    
                $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';
                $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
                $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
                $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
                $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
                $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
 
            $center_padding = $center == 'true' ? $settings['center_padding'] : '0px';
    
            $settings['center_padding_tablet'] = isset($settings['center_padding_tablet']) ? $settings['center_padding_tablet'] : $center_padding;
            $settings['center_padding_mobile'] = isset($settings['center_padding_mobile']) ? $settings['center_padding_mobile'] : $center_padding;
            
                     
            $data_slick = 'data-slick=\'{
            "slidesToShow":'.$settings['slides_to_show'].' ,
            "slidesToScroll": '.$settings['slides_to_scroll'].', 
            "autoplay": '.$autoplay.',
            "arrows": '.$arrows.', 
            "dots":'.$dots.', 
            "autoplaySpeed": '.$autoplay_speed.',
            "pauseOnHover":'.$pause_on_hover.',
            "centerMode":'.$center.', 
            "infinite":'.$infinite.',
            "vertical":'.$vertical.',
            "verticalSwiping":'.$vertical.',
            "speed": '.$settings['transition_speed'].', 
            "responsive":[
                {"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
                {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}
            ]}\''; 
            
		if ( $settings['list'] ) {
		     ?>
		      	<div class="jws_testimonials_slider_wrap jws-carousel <?php echo esc_attr($classes); ?>">

                  <div class="testimonials_slider carousel<?php echo ' slider_layout_'.$settings['slider_layouts'] .''; ?>" <?php echo ''.$data_slick; ?>>  
                
                		  <?php foreach (  $settings['list'] as $item ) {
                		      $url = $item['list_url']['url'];
                              $target = $item['list_url']['is_external'] ? ' target="_blank"' : '';
                              $nofollow = $item['list_url']['nofollow'] ? ' rel="nofollow"' : ''; 
                              ?>
                				<div class="slick-slide">
                                        <?php  include( 'layout/'.$settings['slider_layouts'].'.php' ); ?>   
                                </div>
                		  <?php } ?>
                      
                  </div>

                   <?php if($arrows == 'true') :  ?>
                    <div class="jws-nav-carousel"><div class="jws-button-prev"></div><div class="jws-button-next"></div></div>
                    <?php endif; ?>  
                  <?php if($dots == 'true') : ?>
                    <div class="slider-dots-box"></div>
                    <?php endif; ?>
                </div>
		    <?php }  
		
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