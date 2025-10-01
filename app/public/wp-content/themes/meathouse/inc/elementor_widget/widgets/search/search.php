<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_Search extends Widget_Base {

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
		return 'jws_search';
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
		return esc_html__( 'Jws Search', 'meathouse' );
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
		return 'eicon-site-search';
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
		return ['jquery-autocomplete'];
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
			'section_search_setting_global',
			[
				'label' => esc_html__( 'Setting', 'meathouse' ),
			]
		);
        
         $this->add_control(
				'layouts',
				[
					'label'     => esc_html__( 'Layout', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'popup',
					'options'   => [
						'popup'   => esc_html__( 'Popup', 'meathouse' ),
						'icon_left'   => esc_html__( 'Icon Left', 'meathouse' ),
					],
                    
				]
			);
        
    	$this->add_control(
			'query_type',
			[
				'label'   => esc_html__( 'Source', 'meathouse' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all'    => esc_html__( 'All category', 'meathouse' ),
					'manual' => esc_html__( 'Manual Category', 'meathouse' ),
				],
			]
		);
        
         $this->add_control('filter_categories', [
                'label' => esc_html__('Categories', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_jws('product_cat', 2),
                'condition'  => [
					'query_type' => 'manual',
				],
              
       ]);
        
        $this->end_controls_section();
       
       
		$this->start_controls_section(
			'section_search_setting',
			[
				'label' => esc_html__( 'Toggle', 'meathouse' ),
			]
		);
        $this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon Click Popup', 'meathouse' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
         $this->add_control(
				'icon3',
				[
					'label' => esc_html__( 'Icon Close', 'meathouse' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
        $this->add_control(
			'placeholder',
			[
			    'label' => esc_html__( 'Placeholder Text', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' 		=> 'Search',
			]
		);
		$this->end_controls_section();
  
		$this->start_controls_section(
			'toggle_style',
			[
				'label' => esc_html__( 'Toggle Style', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
							'{{WRAPPER}} .jws_search > button' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
         $this->add_control(
					'icon_color',
					[
						'label' 	=> esc_html__( 'Icon Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_search > button' => 'color: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'icon_sticky_color',
					[
						'label' 	=> esc_html__( 'Icon Sticky Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'.jws_header .is-sticky  .elementor-element-{{ID}} .jws_search > button' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .jws_search > button' => 'font-size: {{SIZE}}px;',
					],
				]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'form_style',
			[
				'label' => esc_html__( 'Form Style', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition'  => [
					'layouts!' => 'popup',
				],
			]
		);
       $this->add_control(
					'form_background',
					[
						'label' 	=> esc_html__( 'Background', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform' => 'background: {{VALUE}};',
						],
					]
		);
         $this->add_responsive_control(
					'form_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => esc_html__( 'Border', 'meathouse' ),
				'selector' => '{{WRAPPER}} form.searchform',
			]
		);
         $this->add_responsive_control(
					'form_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
			'form_height',
			[
				'label'     => esc_html__( 'Min Height', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} form.searchform' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
        $this->start_controls_section(
			'button_style',
			[
				'label' => esc_html__( 'Button Style', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition'  => [
					'layouts!' => 'popup',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} form.searchform button',
			]
		);
        $this->add_control(
					'button_color',
					[
						'label' 	=> esc_html__( 'Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform button' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_control(
					'button_color_hover',
					[
						'label' 	=> esc_html__( 'Color Hover', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform button:hover' => 'color: {{VALUE}};',
						],
					]
		);
       $this->add_control(
					'button_background',
					[
						'label' 	=> esc_html__( 'Background', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform button' => 'background: {{VALUE}};',
						],
					]
		);
        $this->add_control(
					'button_background_hover',
					[
						'label' 	=> esc_html__( 'Background Hover', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform button:hover' => 'background: {{VALUE}};',
						],
					]
		);
         $this->add_responsive_control(
					'button_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
         $this->add_responsive_control(
					'button_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
			'button_width',
			[
				'label'     => esc_html__( 'Min Width', 'meathouse' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} form.searchform button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
        $this->start_controls_section(
			'input_style',
			[
				'label' => esc_html__( 'Input Style', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition'  => [
					'layouts!' => 'popup',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'label' => esc_html__( 'Typography', 'meathouse' ),
				'selector' => '{{WRAPPER}} form.searchform input',
			]
		);
        $this->add_control(
					'input_color',
					[
						'label' 	=> esc_html__( 'Color', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform input , {{WRAPPER}} form.searchform input::placeholder' => 'color: {{VALUE}};',
						],
					]
		);
       $this->add_control(
					'input_background',
					[
						'label' 	=> esc_html__( 'Background', 'meathouse' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} form.searchform input' => 'background: {{VALUE}};',
						],
					]
		);
         $this->add_responsive_control(
					'input_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
         $this->add_responsive_control(
					'input_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'meathouse' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} form.searchform input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $class = '';
        $class.= $settings['layouts'];
        $this->add_render_attribute(
			'wrapper',
			'class',
			[
				'jws_search',
				$class,
			]
		);
        ?>
            <div <?php echo ''.$this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php include( 'layout/'.$settings['layouts'].'.php' ); ?>
            </div>
        <?php    
        

	}
    
   protected function get_categories_for_jws($taxonomy, $select = 1)
        {
            $data = array();
    
            $query = new \WP_Term_Query(array(
                'hide_empty' => false,
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