<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_Meat_Box extends Widget_Base {

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
		return 'jws_meat_box';
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
		return esc_html__( 'Jws Meat Box', 'meathouse' );
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
		return 'eicon-meat_box';
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
				'meat_box_display',
				[
					'label'     => esc_html__( 'Display', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
						'grid'   => esc_html__( 'Grid', 'meathouse' ),
						'slider'   => esc_html__( 'Slider', 'meathouse' ),
					],
                    
				]
		);

        $this->add_responsive_control(
				'meat_box_columns',
				[
					'label'          => esc_html__( 'Columns', 'meathouse' ),
					'type'           => Controls_Manager::SELECT,
					'default'        => '12',
					'options'        => [
						'12' => '1',
						'6' => '2',
						'4' => '3',
						'3' => '4',
						'20' => '5',
						'2' => '6',
					],
				]
		);

        $this->end_controls_section();   
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'meat_box List', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();
        $repeater->start_controls_tabs(
        	'content_tabs'
        );
        $repeater->start_controls_tab(
        	'content_tab',
        	[
        		'label' => esc_html__( 'Content', 'meathouse' ),
        	]
        );
        $repeater->add_control(
				'choose_product_for_box',
				[
					'label'     => esc_html__( 'Select Product For Box', 'meathouse' ),
					'type'      => 'jws-query-posts',
					'post_type' => 'product',
					'multiple'  => false,
				]
		);
        $repeater->add_control(
			'item_active',
			[
				'label' => esc_html__( 'Active This Item', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Active', 'meathouse' ),
				'label_off' => esc_html__( 'Off', 'meathouse' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'meathouse' ),
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
				'default' => 'full',
				'separator' => 'none',
			]
		);
        $repeater->add_control(
				'name',
				[
					'label'     => esc_html__( 'Name', 'meathouse' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Box Name',
				]
		);
        $repeater->add_control(
				'lbs',
				[
					'label'     => esc_html__( 'Lbs text', 'meathouse' ),
					'type'      => Controls_Manager::TEXTAREA,
					'default'   => 'Ideal for 1-2 people 8-14lbs of meat',
				]
		);
        $repeater->add_control(
				'price',
				[
					'label'     => esc_html__( 'Price', 'meathouse' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Only $6.25 /meal',
				]
		);
        $repeater->add_control(
				'button_text',
				[
					'label'     => esc_html__( 'Button Text', 'meathouse' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'BUILD YOUR BOX',
				]
		);
        $repeater->add_control(
				'description',
				[
					'label'     => esc_html__( 'Description', 'meathouse' ),
					'type'      => Controls_Manager::TEXTAREA,
					'default'   => 'You can be confident in the meat and fish that you serve your family. You’ll support your local farmers who are focused on raising the highest quality products.',
				]
		);
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
        	'style_tab',
        	[
        		'label' => esc_html__( 'Style', 'meathouse' ),
        	]
        );
   
         $repeater->add_control(
			'image_margin',
			[
				'label'      => esc_html__( 'Image Margin', 'meathouse' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .jws-box-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
        
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Menu List', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'meat_box_title' => esc_html__( 'Box Name', 'meathouse' ),
					],
				],
				'title_field' => '{{{ name }}}',
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
					'{{WRAPPER}} .jws-meat_box .jws-meat_box-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws-meat_box.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => esc_html__( 'Rows Gap', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-meat_box .jws-meat_box-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
 
        
        $this->start_controls_section(
			'content_style',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->start_controls_tabs(
        	'tabs'
        );
        $this->start_controls_tab(
        	'normal_tab',
        	[
        		'label' => esc_html__( 'Normal', 'meathouse' ),
        	]
        );
        
        $this->add_control(
			'box_background',
			[
				'label'     => esc_html__( 'Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-box-inner' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button',
			[
				'label' => esc_html__( 'Button', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'box_button_color',
			[
				'label'     => esc_html__( 'Button Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main' => '--btn-color : {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_color',
			[
				'label'     => esc_html__( 'Button Hover Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main:hover' => '--btn-color2 : {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_bg',
			[
				'label'     => esc_html__( 'Button Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main' => '--btn-bgcolor: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_bg',
			[
				'label'     => esc_html__( 'Button Hover Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main:before' => '--btn-bgcolor2: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_border',
			[
				'label'     => esc_html__( 'Button Border Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main' => 'border-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_border',
			[
				'label'     => esc_html__( 'Button Hover Border Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-main:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
         $this->add_control(
			'box_button_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'meathouse' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .btn-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
        $this->end_controls_tab();
        
        $this->start_controls_tab(
        	'active_tab',
        	[
        		'label' => esc_html__( 'Active', 'meathouse' ),
        	]
        );
        
       $this->add_control(
			'box_active_background',
			[
				'label'     => esc_html__( 'Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .jws-box-inner' => 'background-color: {{VALUE}};',
				],
			]
		);
        
             $this->add_control(
			'box_button_yes',
			[
				'label' => esc_html__( 'Button', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'box_button_color_yes',
			[
				'label'     => esc_html__( 'Button Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main' => '--btn-color : {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_color_yes',
			[
				'label'     => esc_html__( 'Button Hover Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main:hover' => '--btn-color2 : {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_bg_yes',
			[
				'label'     => esc_html__( 'Button Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main' => '--btn-bgcolor: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_bg_yes',
			[
				'label'     => esc_html__( 'Button Hover Background Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main:before' => '--btn-bgcolor2: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_border_yes',
			[
				'label'     => esc_html__( 'Button Border Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main' => 'border-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'box_button_hover_border_yes',
			[
				'label'     => esc_html__( 'Button Hover Border Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .yes .btn-main:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

        
        
        $this->end_controls_tab();

   
        $this->end_controls_tabs();
        
        
        $this->end_controls_section();
        
    
        
        $this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'meathouse' ),
				'type'      => Controls_Manager::SECTION,
				'condition' => [
					'meat_box_display' => ['slider'],
				],
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
			'transition_speed',
			[
				'label'     => esc_html__( 'Transition Speed (ms)', 'meathouse' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
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
       
        $image_size = (!empty($settings['meat_box_dimension']['width']) || !empty($settings['meat_box_dimension']['height'])) ? $settings['meat_box_dimension']['width'].'x'.$settings['meat_box_dimension']['height'] : 'full';
        
          $class_column = 'jws-meat_box-item';
          $class_column .= ' col-xl-'.$settings['meat_box_columns'].'';
          $class_column .= (!empty($settings['meat_box_columns_tablet'])) ? ' col-lg-'.$settings['meat_box_columns_tablet'].'' : ' col-lg-'.$settings['meat_box_columns'].'' ;
          $class_column .= (!empty($settings['meat_box_columns_mobile'])) ? ' col-'.$settings['meat_box_columns_mobile'].'' :  ' col-'.$settings['meat_box_columns'].''; 
          $class_row = 'jws-meat_box meat_box row'; 
          $class_row .= ' '.$settings['meat_box_display'];

          if($settings['meat_box_display'] == 'slider') {
                $class_row .= ' jws-slider';
                $class_column .= ' slider-item slick-slide'; 
                $dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
                $arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
                $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
                $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
                $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
                $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
                
                $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';
                $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
                $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
                $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
                $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
                $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
                
                $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"pauseOnHover":'.$pause_on_hover.',"infinite":'.$infinite.',
                "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
                {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
           }else {
                $data_slick = '';   
           }
          

         ?>
         <div class="jws-meat_box-element">
            <?php if(isset($arrows) && $arrows == 'true') : ?>
              <nav class="jws-meat_box-nav">
                    <span class="prev-item jws-carousel-btn"><span class="jws-icon-arrow_carrot-left"></span></span>
                    <span class="next-item jws-carousel-btn"><span class="jws-icon-arrow_carrot-right"></span></span>
              </nav>
            <?php endif; ?>
            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?> data-meat_box="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
                <?php $i = 1; $n = 0; foreach (  $settings['list'] as $index => $item ) {
             
                    $active = $item['item_active'] == 'yes' ? $item['item_active'] : '';
       
                    ?>
                    <div class="elementor-repeater-item-<?php echo esc_attr( $item['_id'].' '.$active ); ?> <?php echo esc_attr($class_column); ?>">
                      
                      
                      
                      <div class="jws-box-inner">
                           
                                <div class="jws-box-image">
                                    <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?> 
                                </div>
                                <div class="jws-box-content">
                                <h4 class="name">
                                    <?php echo esc_html($item['name']); ?>
                                </h4>
                                <div class="lbs">
                                    <?php echo ''.$item['lbs']; ?>
                                </div>
                                <div class="price">
                                    <?php echo esc_html($item['price']); ?>
                                </div>
                                <a class="btn-main" data-product-id="<?php echo esc_attr($item['choose_product_for_box']); ?>" href="<?php echo get_permalink(jws_theme_get_option('choose_page_builder')); ?>">
                                    <span><?php echo esc_html($item['button_text']); ?></span>
                                </a>
                                <div class="description">
                                    <?php echo esc_textarea($item['description']); ?>
                                </div>
                                </div>
                          
                        </div>
                      
                   
                    </div>
                <?php $n++; } ?>
            </div>
            <div class="slider-dots-box"></div>
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