<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_tab extends Widget_Base {

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
		return 'jws_tab';
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
		return esc_html__( 'Jws Tab', 'meathouse' );
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
		return 'eicon-tabs';
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
			'nav_tab_display',
			[
				'label' => esc_html__( 'Display', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top'  => esc_html__( 'Top', 'meathouse' ),
                    'left'  => esc_html__( 'Left', 'meathouse' ),
                    'right'  => esc_html__( 'Right', 'meathouse' ),
				],
			]
		);

        $this->end_controls_section();
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Menu List', 'meathouse' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'content_layout',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'template',
				'options' => [
					'template'  => esc_html__( 'Template', 'meathouse' ),
                    'text'  => esc_html__( 'Text', 'meathouse' ),
				],
			]
		);
         $repeater->add_control(
			'content_text',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Default description', 'meathouse' ),
				'placeholder' => esc_html__( 'Type your description here', 'meathouse' ),
                'condition' => ['content_layout' => 'text']
			]
		);
		$repeater->add_control(
				'select_template',
				[
					'label'     => esc_html__( 'Select Template', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'multiple'  => true,
					'default'   => '',
					'options'   => $this->get_saved_data( 'section' ),
                    'condition' => ['content_layout' => 'template']
				]
		);
       
		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Name', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Name' , 'meathouse' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon', 'meathouse' ),
					'type' => \Elementor\Controls_Manager::ICONS,
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
						'list_title' => esc_html__( 'Nav #1', 'meathouse' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();
        $this->start_controls_section(
			'nav_style',
			[
				'label' => esc_html__( 'Nav', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'absolute',
			[
				'label' => esc_html__( 'Tab Position Absolute', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'meathouse' ),
				'label_off' => esc_html__( 'Off', 'meathouse' ),
				'return_value' => 'yes',
			]
		);
        $this->add_control(
				'vertical',
				[
					'label' 		=> esc_html__( 'Vertical Orientation', 'meathouse' ),
					'type' 			=> Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
					'range' => [
        					'px' => [
        						'min' => 0,
        						'max' => 1000,
        						'step' => 1,
        					],
        					'%' => [
        						'min' => 0,
        						'max' => 100,
        					],
        				],
					'selectors' 	=> [
						'{{WRAPPER}}  .jws_tab_wrap .tab_nav_container.tab_absolute' => 'top: {{SIZE}}{{UNIT}};',
					],
                    'condition'	=> [
						'absolute' => 'yes',
				    ],
				]
		);
        $this->add_control(
				'horizontal',
				[
					'label' 		=> esc_html__( 'Horizontal Orientation', 'meathouse' ),
					'type' 			=> Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
					'range' => [
        					'px' => [
        						'min' => -1000,
        						'max' => 1000,
        						'step' => 1,
        					],
        					'%' => [
        						'min' => -100,
        						'max' => 100,
        					],
        				],
					'selectors' 	=> [
						'{{WRAPPER}}  .jws_tab_wrap .tab_nav_container.tab_absolute' => 'left: {{SIZE}}{{UNIT}};',
					],
                    'condition'	=> [
						'absolute' => 'yes',
				    ],
				]
		);
        $this->add_responsive_control(
			'nav__position',
			[
				'label' => esc_html__( 'Position', 'meathouse' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'meathouse' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'meathouse' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'meathouse' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav_container' => 'text-align: {{VALUE}}',
				],
			]
		);
         $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'nav_border',
					'label' 	=> esc_html__( 'Border', 'meathouse' ),
					'selector' 	=> '{{WRAPPER}} .jws_tab_wrap .tab_nav_container .tab_nav_wrap',
				]
		);
        $this->add_responsive_control(
					'nav_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_nav_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'nav_margin',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .tab_nav_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'nav_radius',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_nav_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'meathouse' ),
				'selector' => '{{WRAPPER}} .jws_tab_wrap .tab_nav_wrap',
			]
		);
        $this->add_control(
			'nav_bg',
			[
				'label' => esc_html__( 'Background Color', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav_wrap' => 'background-color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'nav_item_style',
			[
				'label' => esc_html__( 'Item', 'meathouse' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
					'nav_item_margin',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_nav li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'nav_item_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_nav li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
         $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'nav_item_border',
					'label' 	=> esc_html__( 'Border', 'meathouse' ),
					'selector' 	=> '{{WRAPPER}} .jws_tab_wrap .tab_nav li a',
				]
		);
        $this->add_control(
			'nav_item_border_color_active',
			[
				'label' => esc_html__( 'Border Color Active', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li.current a' => 'border-color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'nav_item_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li a' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'nav_item_color_active',
			[
				'label' => esc_html__( 'Color Active', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li.current a' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'nav_item_bgcolor',
			[
				'label' => esc_html__( 'Background Color', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li a' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'nav_item_bgcolor_active2',
			[
				'label' => esc_html__( 'Background Color Active', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li a:before' => 'background: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .jws_tab_wrap .tab_nav li a',
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
        $this->add_responsive_control(
					'content_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Position', 'meathouse' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'meathouse' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'meathouse' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'meathouse' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_content' => 'text-align: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'icon_style',
			[
				'label' => esc_html__( 'Icon', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'meathouse' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'meathouse' ),
					'right' => esc_html__( 'After', 'meathouse' ),
				],
			]
		);
        
        $this->add_responsive_control(
					'icon_margin',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Margin', 'meathouse' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_tab_wrap .tab_nav li a .item-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
          $this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'meathouse' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li a .item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li a .item-icon' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'icon_color_active',
			[
				'label' => esc_html__( 'Color Active', 'meathouse' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws_tab_wrap .tab_nav li.current a .item-icon' => 'color: {{VALUE}};',
				],
			]
		);
        $this->end_controls_section();
	}
	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_saved_data( $type = 'page' ) {

		$saved_widgets = $this->get_post_template( $type );
		$options[-1]   = esc_html__( 'Select', 'meathouse' );
		if ( count( $saved_widgets ) ) {
			foreach ( $saved_widgets as $saved_row ) {
				$options[ $saved_row['id'] ] = $saved_row['name'];
			}
		} else {
			$options['no_template'] = esc_html__( 'It seems that, you have not saved any template yet.', 'meathouse' );
		}
		return $options;
	}

	/**
	 *  Get Templates based on category
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_post_template( $type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => array(
					array(
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {

			$templates[] = array(
				'id'   => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $templates;
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

        $layout = 'display_'.$settings['nav_tab_display']; 
        $this->add_render_attribute( [
			'icon-align' => [
				'class' => [
                    'item-icon',
					'elementor-align-icon-' . $settings['icon_align'],
				],
			],
		] );
        
		if ( $settings['list'] ) {
		     ?>
		      	<div class="jws_tab_wrap <?php echo esc_attr($layout); ?>">
                  <div class="tab_nav_container<?php if($settings['absolute'] == 'yes') echo esc_attr(' tab_absolute'); ?>"> 
                      <div class="tab_nav_wrap">  
                          <ul class="tab_nav">
                                <?php $nav = 1; foreach (  $settings['list'] as $item ) {  ?>
                    				<li class="jws_nav_item<?php if($nav == 1) echo " current"; ?>">
                                        <a href="#" data-tab="<?php echo esc_attr($item['_id']); ?>">
                                            <?php if ( ! empty( $item['icon'] ) ) : ?>
                                            <span <?php echo ''.$this->get_render_attribute_string( 'icon-align' ); ?>>
                                				<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );  ?>  
                                			</span>
                                            <?php endif; ?>
                                            <span><?php echo esc_html($item['list_title']); ?></span>
                                        </a>  
                                    </li>
                    		  <?php $nav++; } ?>
                          </ul>  
                      </div>
                  </div> 
                  <div class="tab_content">  
            		  <?php $content = 1; foreach (  $settings['list'] as $item ) { ?>
            				<div id="<?php echo esc_attr($item['_id']); ?>" class="jws_tab_item<?php if($content == 1) echo " current"; ?>">
                                    <?php 
                                    if($item['content_layout'] == 'template') {
                                      echo do_shortcode('[elementor-template id="'.$item['select_template'].'"]');  
                                    }else {
                                      echo '<div class="content_text">' . $item['content_text'] . '</div>';  
                                    }
                                    ?>   
                            </div>
            		  <?php $content++; } ?>
                  </div>
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