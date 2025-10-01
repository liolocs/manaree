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
class Jws_Image_Carousel extends Widget_Base {

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
		return 'jws_image_carousel';
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
		return esc_html__( 'Jws image carousel', 'meathouse' );
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
		return 'eicon-image_carousel';
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
				'image_carousel_layout',
				[
					'label'     => esc_html__( 'Layout', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'layout1',
					'options'   => [
						'layout1'   => esc_html__( 'layout1', 'meathouse' ),
					],
                    
				]
		);

        $this->add_responsive_control(
				'image_carousel_columns',
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

        
        
        $this->add_control(
			'image_carousel_position',
			[
				'label'       => esc_html__( 'Position', 'meathouse' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'at-center',
				'options'     => [
                    'at-center'  => [
						'title' => esc_html__( 'Center', 'meathouse' ),
						'icon'  => 'eicon-h-align-center',
					],
                    'at-top' => [
						'title' => esc_html__( 'Top', 'meathouse' ),
						'icon'  => 'eicon-v-align-top',
					],
                    'at-bottom' => [
						'title' => esc_html__( 'Bottom', 'meathouse' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'label_block' => false,
				'toggle'      => false,
                'prefix_class' => 'jws-content-align-',

			]
		);
        
      $this->add_control(
				'image_carousel_align',
				[
					'label' 		=> esc_html__( 'Content Align', 'meathouse' ),
					'type' 			=> Controls_Manager::CHOOSE,
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
							'{{WRAPPER}} .jws-image_carousel .jws-image_carousel-image' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);

        $this->end_controls_section();   
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'image_carousel List', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();

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
				'default' => 'large',
				'separator' => 'none',
			]
		);

        
        $repeater->add_control(
			'link_url',
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
				'title',
				[
					'label'     => esc_html__( 'Title', 'meathouse' ),
					'type'      => Controls_Manager::TEXT,
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
						'image_carousel_title' => esc_html__( 'Name #1', 'meathouse' ),
					],
				],
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
					'{{WRAPPER}} .jws-image_carousel .jws-image_carousel-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws-image_carousel.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws-image_carousel .jws-image_carousel-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
         $this->add_responsive_control(
			'image_carousel_content_padding',
			[
				'label'      => esc_html__( 'Content Padding', 'meathouse' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jws-image_carousel .jws-image_carousel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
			'image_carousel_title',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'image_carousel_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'meathouse' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-image_carousel .jws-image_carousel-image .name' => 'color: {{VALUE}};',
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
       
        $image_size = (!empty($settings['image_carousel_dimension']['width']) || !empty($settings['image_carousel_dimension']['height'])) ? $settings['image_carousel_dimension']['width'].'x'.$settings['image_carousel_dimension']['height'] : 'full';
        
          $class_column = 'jws-image_carousel-item';
          $class_column .= ' col-xl-'.$settings['image_carousel_columns'].'';
          $class_column .= (!empty($settings['image_carousel_columns_tablet'])) ? ' col-lg-'.$settings['image_carousel_columns_tablet'].'' : ' col-lg-'.$settings['image_carousel_columns'].'' ;
          $class_column .= (!empty($settings['image_carousel_columns_mobile'])) ? ' col-'.$settings['image_carousel_columns_mobile'].'' :  ' col-'.$settings['image_carousel_columns'].''; 
          $class_row = 'jws-image_carousel image_carousel row '.$settings['image_carousel_layout']; 
        
          
          if($settings['image_carousel_layout'] == 'layout2' || $settings['image_carousel_layout'] == 'layout3') {
            $class_row .= ' content_inner';
          }   
 
        
                $class_row .= ' carousel';
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
                
                
                $data_slick = 'data-slick=\'{
                "slidesToShow":'.$settings['slides_to_show'].' ,
                "slidesToScroll": '.$settings['slides_to_scroll'].', 
                "autoplay": '.$autoplay.',
                "arrows": '.$arrows.', 
                "dots":'.$dots.', 
                "autoplaySpeed": '.$autoplay_speed.',
                "pauseOnHover":'.$pause_on_hover.',
                "infinite":'.$infinite.',
                "speed": '.$settings['transition_speed'].', 
                "responsive":[
                    {"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
                    {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}
                ]}\''; 

          

         ?>
         <div class="jws-image_carousel-element jws-carousel">
          
            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?> data-image_carousel="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
               
                    <?php  foreach (  $settings['list'] as $index => $item ) {
                       $link_key = 'link' . $index;   
                       if($item['link_url']['is_external']) $this->add_render_attribute( $link_key, 'rel',  'nofollow' );
                       if($item['link_url']['nofollow']) $this->add_render_attribute( $link_key, 'target',  '_blank' );  
                       $this->add_render_attribute( $link_key, 'href',  $item['link_url']['url'] ); 
    
                        ?>
                            <div class="<?php echo esc_attr($class_column); ?>">
                            <?php  include( ''.$settings['image_carousel_layout'].'.php' );  ?>
                       
                        </div>
                    <?php  } ?>
               
            </div>
            <?php if($dots) : ?>
            <div class="slider-dots-box"></div>
            <?php endif; ?>
          
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