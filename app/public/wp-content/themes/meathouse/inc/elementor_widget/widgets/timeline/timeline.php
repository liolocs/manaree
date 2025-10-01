<?php

namespace Elementor;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Timeline extends Widget_Base {

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
		return 'jws_timeline';
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
		return esc_html__( 'Jws Timeline', 'meathouse' );
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
		return 'eicon-time-line';
	}
    /**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
		return [ 'appear' ];
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
	 * Register Woo post Grid controls.
	 *
	 * @since 0.0.1
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
					],
				]
		);
		$repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'active',
			[
				'label' => esc_html__( 'Active', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'meathouse' ),
				'label_off' => esc_html__( 'Off', 'meathouse' ),
				'return_value' => 'yes',
			]
		);
		$repeater->add_control(
			'list_date', [
				'label' => esc_html__( 'Date', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'January 15th, 2005' , 'meathouse' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title' , 'meathouse' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_content', [
				'label' => esc_html__( 'Content', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Content', 'meathouse' ),
				'placeholder' => esc_html__( 'Type your content here', 'meathouse' ),
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
				'default' => 'large',
				'separator' => 'none',
			]
		);
         $repeater->add_responsive_control(
			'image_radius',
			[
				'label' => __( 'Image Radius', 'meathouse' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .jws_timeline_image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
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
						'list_title' => esc_html__( 'Title #1', 'meathouse' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
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
        
       $this->start_controls_tabs(
			'timeline_content_tabs'
		);
        
        
        $this->start_controls_tab(
			'timeline_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'meathouse' ),
			]
		);
        $this->add_control(
					'timeline_background_color',
					[
						'label' 	=> esc_html__( 'Background Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_content_inner' => 'background: {{VALUE}};',
						],
					]
		);
        $this->add_control(
			'timeline_des',
			[
				'label' => esc_html__( 'Description', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'timeline_des_color',
					[
						'label' 	=> esc_html__( 'Description Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_desc' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'timeline_des_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_desc',
			]
		);
        $this->add_control(
			'timeline_title',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'timeline_title_color',
					[
						'label' 	=> esc_html__( 'Title Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_title' => 'color: {{VALUE}};',
						],
					]
		);  
           
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'timeline_title_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_title',
			]
		);  
        $this->end_controls_tab();
            
        $this->start_controls_tab(
			'timeline_active_tab',
			[
				'label' => esc_html__( 'Active', 'meathouse' ),
			]
		);
        $this->add_control(
					'timeline_background_color_active',
					[
						'label' 	=> esc_html__( 'Background Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field.active .jws_timeline_content .jws_timeline_content_inner' => 'background: {{VALUE}};',
						],
					]
		);
        $this->add_control(
					'timeline_border_color_active',
					[
						'label' 	=> esc_html__( 'Border Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field.active .jws_timeline_content .jws_timeline_content_inner' => 'border-color: {{VALUE}};',
						],
					]
		);
        $this->add_control(
					'timeline_des_color_active',
					[
						'label' 	=> esc_html__( 'Description Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field.active .jws_timeline_content .jws_timeline_desc' => 'color: {{VALUE}};',
						],
					]
		);

        $this->add_control(
					'timeline_title_color_active',
					[
						'label' 	=> esc_html__( 'Title Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field.active .jws_timeline_content .jws_timeline_title' => 'color: {{VALUE}};',
						],
					]
		);  
  
        $this->end_controls_tab();
        
        
        $this->end_controls_tabs();

        
        
        $this->add_responsive_control(
					'timeline_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_content_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'timeline_border',
				'selector' => '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content .jws_timeline_content_inner',
				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
         $this->start_controls_section(
			'timeline_other_style',
			[
				'label' => esc_html__( 'Other', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'timeline_month',
			[
				'label' => esc_html__( 'Month', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'timeline_month_color',
					[
						'label' 	=> esc_html__( 'Month Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_date_inner .jws_timeline_month' => 'color: {{VALUE}};',
						],
					]
		);  
           
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'timeline_month_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_date_inner .jws_timeline_year',
			]
		);
        $this->add_control(
			'timeline_year',
			[
				'label' => esc_html__( 'Year', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'timeline_year_color',
					[
						'label' 	=> esc_html__( 'Year Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_date_inner .jws_timeline_year' => 'color: {{VALUE}};',
						],
					]
		);  
           
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'timeline_year_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_date_inner .jws_timeline_year',
			]
		);  
           $this->add_control(
			'timeline_line',
			[
				'label' => esc_html__( 'Line', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'timeline_line_color',
					[
						'label' 	=> esc_html__( 'Line Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_circle , .jws_timeline .jws_timeline_line' => 'background: {{VALUE}};',
                            '{{WRAPPER}} .jws_timeline .jws_timeline_main .jws_days .jws_timeline_field .jws_timeline_content:after' => 'border-color: {{VALUE}};',
                            '{{WRAPPER}} .jws_timeline .jws_timeline_line:after' => 'border-top-color: {{VALUE}};',
                            
                            
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
    ?>
        <div class="jws_timeline layout_<?php echo esc_attr($settings['slider_layouts']); ?>">
            <div class="jws_timeline_main">
             
                    <?php $i = 1; foreach (  $settings['list'] as $item ) { $position = ($i%2 != 0) ? ' position_right' : ' position_left';
                        $active = ($item['active']) ? ' active' : '';
                     ?>
            				<div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> jws_timeline_field<?php echo esc_attr($position.$active); ?>">
                         
                                    <div class="jws_timeline_circle"><span></span></div> 
                                     <div class="jws_timeline_content">
                                        <div class="jws_timeline_content_inner">
                                            <h6 class="jws_timeline_date">
                                                <?php echo esc_html($item['list_date']); ?>
                                            </h6>  

                                            <h5 class="jws_timeline_title">
                                                <?php echo esc_html($item['list_title']); ?>
                                            </h5>
                                            <div class="jws_timeline_desc">
                                                <?php echo wp_kses_post($item['list_content']); ?>
                                            </div>
                                        </div>
                                        <div class="jws_timeline_image">
                                   
                                                <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item ); ?>
                                           
                                        </div>
                                     </div>
                                  
                                
                            </div>
            		 <?php $i++; } ?>
                
                <div class="jws_timeline_line"></div>
            </div>
        </div>    
	<?php }
    
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