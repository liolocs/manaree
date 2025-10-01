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
class Info_Box extends Widget_Base {

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
		return 'jws_info_box';
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
		return esc_html__( 'Jws Info Box', 'meathouse' );
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
		return 'eicon-info-box';
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
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
			'info_layout',
			[
				'label' => esc_html__( 'Layout', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'meathouse' ),
                    'layout2'  => esc_html__( 'Layout 2', 'meathouse' ),
				],
			]
		);
        $this->add_control(
			'box_url',
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
        $this->end_controls_section();
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
			'info_title',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'meathouse' ),
				'placeholder' => esc_html__( 'Type your title here', 'meathouse' ),
			]
		);

        $this->add_control(
			'info_content',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Default description', 'meathouse' ),
				'placeholder' => esc_html__( 'Type your description here', 'meathouse' ),
			]
		);
        $this->add_control(
			'info_readmore',
			[
				'label' => esc_html__( 'Readmore', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'READ MORE', 'meathouse' ),
			]
		);
        $this->add_control(
				'align',
				[
					'label' 		=> esc_html__( 'Align', 'meathouse' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'left',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> esc_html__( 'Left', 'meathouse' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> esc_html__( 'Center', 'meathouse' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> esc_html__( 'Right', 'meathouse' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
                    'selectors' => [
							'{{WRAPPER}} .jws-info-box' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon', 'meathouse' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
 
		$this->end_controls_section();
        
        $this->start_controls_section(
			'image_section',
			[
				'label' => esc_html__( 'Images', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        
        $this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
			]
		);
 
		$this->end_controls_section();
        
        $this->start_controls_section(
			'box_style',
			[
				'label' => esc_html__( 'Box', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'box_background',
			[
				'label' => esc_html__( 'Background', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_responsive_control(
					'box_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
					'box_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'placeholder' => '1px',
				'selector' => '{{WRAPPER}} .jws-info-box',
				'separator' => 'before',
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box',
			]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'label' => esc_html__( 'Box Shadow Hover', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box:hover',
			]
		);
		$this->end_controls_section();
        $this->start_controls_section(
			'box_number_style',
			[
				'label' => esc_html__( 'Number', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
						 'info_layout' => ['layout5','layout6'],
				],
			]
		);
        
        $this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Number Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .jws-info-box_inner .number-text' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box .jws-info-box_inner .number-text',
			]
		);


        $this->end_controls_section();
        $this->start_controls_section(
			'box-content_title_style',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'box-title_style',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .box-title' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box .box-title',
			]
		);
        $this->add_control(
				'title_spacing',
				[
					'label' 		=> esc_html__( 'Spacing', 'meathouse' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-info-box .box-title' => 'margin-bottom: {{SIZE}}px;',
					],
				]
		);
        $this->add_control(
			'box-content_style',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',

			]
		);
        
        $this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Content Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .box-content' => 'color: {{VALUE}}',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box .box-content',
			]
		);
        $this->add_control(
				'content_spacing',
				[
					'label' 		=> esc_html__( 'Spacing', 'meathouse' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-info-box .jws-info-box-inner .box-content' => 'margin-bottom: {{SIZE}}px;',
					],
				]
		);
        $this->end_controls_section();
        
        $this->start_controls_section(
			'box-icon_style',
			[
				'label' => esc_html__( 'Icon', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .box-icon' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'icon_bgcolor',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .jws-info-box .box-icon',
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
						'{{WRAPPER}} .jws-info-box .box-icon' => 'font-size: {{SIZE}}px;',
					],
				]
		);
 
  
        $this->add_responsive_control(
					'image_icon_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box .jws-info-box_inner .box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
					'image_icon_marign',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box .box-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->end_controls_section();
        
        $this->start_controls_section(
			'box-image_style',
			[
				'label' => esc_html__( 'Images', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

  
        $this->add_responsive_control(
					'image_img_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box .jws-info-box_inner .box-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
					'image_img_marign',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box .box-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->end_controls_section();
        
        $this->start_controls_section(
			'box_readmore_style',
			[
				'label' => esc_html__( 'Read More', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'readmore_color',
			[
				'label' => esc_html__( 'Readmore Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .jws-info-box-inner .box-more' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'readmore_color_hover',
			[
				'label' => esc_html__( 'Readmore Color Hover', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-info-box .jws-info-box-inner .box-more:hover' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws-info-box .jws-info-box-inner .box-more',
			]
		);
        $this->add_responsive_control(
					'readmore_marign',
					[
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-info-box .jws-info-box-inner .box-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
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
	   
		$settings = $this->get_settings_for_display();
        $url = $settings['box_url']['url'];
        $target = $settings['box_url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['box_url']['nofollow'] ? ' rel="nofollow"' : '';  
        
         ?>
            <div class="jws-info-box <?php echo esc_attr($settings['info_layout']); ?>">
                <div class="jws-info-box-inner">
                    <?php include( 'layout/'.$settings['info_layout'].'.php' ); ?>
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