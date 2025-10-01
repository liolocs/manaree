<?php
/**
 * JWS Particles Module.
 *
 * @package JWS
 */

namespace Elementor;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class shade_Animation {
    /**
     * Instance
     *
     * @since 1.2.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;
    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.2.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    	/**
	 * Constructor.
	 */
	public function __construct() {

	


			$this->add_actions();
	

	}
	/**
	 * Add actions and set scripts dependencies required to run the widget.
	 *
	 * @since 1.12.0
	 * @access protected
	 */
	protected function add_actions() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_shape' ] );
        
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
        add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action( 'elementor/column/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action( 'elementor/frontend/column/before_render', [ $this, '_before_render' ], 10, 1 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

	}
    
    /**
	 * Enqueue scripts.
	 *
	 * Registers all the scripts defined as extension dependencies and enqueues them.
	 *
	 * @since 1.12.0
	 * @access public
	 */
	public function enqueue_scripts_shape() {
	       wp_enqueue_script('appear');
           wp_enqueue_script('jws-shade-animation');       
	}
    
    	/**
	 * Register shade Animation Backgrounds controls.
	 *
	 * @since 1.12.0
	 * @access public
	 * @param object $element for current element.
	 * @param object $section_id for section ID.
	 * @param array  $args for section args.
	 */
	public function register_controls( $element, $section_id, $args ) {

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) || ( 'column' === $element->get_name() && 'section_style' === $section_id ) ) {
            
			$element->start_controls_section(
				'jws_shade_animation',
				[
					'tab'   => Controls_Manager::TAB_STYLE,
					/* translators: %s admin link */
					'label' =>  esc_html__( 'Shade Animation', 'meathouse' ) , 
				]
			);
            $element->add_control(
				'jws_enable_shade_animation',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable shade Animation', 'meathouse' ),
					'default'      => '',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
                    'prefix_class' => 'jws-shade-',
					'render_type'  => 'template',
				]
			);
            $repeater = new \Elementor\Repeater();
            
    		$repeater->add_control(
    			'shade_image',
    			[
    				'label' =>  esc_html__( 'Choose Image', 'meathouse' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
    			]
    	   	);
            $repeater->add_control(
				'enable_render_to_svg',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable Render Image To Svg Html', 'meathouse' ),
					'default'      => '',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
					'render_type'  => 'template',
				]
			);
            $repeater->add_control(
        			'shape-index',
        			[
        				'label' =>  esc_html__( 'Z index', 'meathouse' ),
        				'type' => \Elementor\Controls_Manager::NUMBER,
        				'min' => -1,
        				'max' => 10,
        				'step' => 1,
                        'selectors' => [
                            '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'z-index: {{VALUE}};',
                        ],
        			]
    		);
             $repeater->add_control(
				'shape-animation',
				[
					'label'     =>  esc_html__( 'Animation Name', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
                        'none'   =>  esc_html__( 'None', 'meathouse' ),
						'MoveLeft'   =>  esc_html__( 'Move Left', 'meathouse' ),
                        'MoveRight'   =>  esc_html__( 'Move Right', 'meathouse' ),
						'MoveTop'   =>  esc_html__( 'Move Top', 'meathouse' ),
                        'MoveBottom'   =>  esc_html__( 'Move Bottom', 'meathouse' ),
                        'MoveCircle'   =>  esc_html__( 'Move Circle', 'meathouse' ),
                        'MoveCircleSmall'   =>  esc_html__( 'Circle Small', 'meathouse' ),
                        'MoveCircle360'   =>  esc_html__( 'Move Circle 360', 'meathouse' ),
                        'MoveZoomIn'   =>  esc_html__( 'Zoom In', 'meathouse' ),
                        'MoveZoomOut'   =>  esc_html__( 'Zoom Out', 'meathouse' ),  
					],
                    'selectors' => [
                        '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'animation-name: {{VALUE}};',
                    ],
                    
				]
			);
            
             $repeater->add_control(
				'shape-animation-timing',
				[
					'label'     =>  esc_html__( 'Animation Timing', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'linear'   =>  esc_html__( 'Linear', 'meathouse' ),
						'ease'   =>  esc_html__( 'Ease', 'meathouse' ),
                        'ease-in'   =>  esc_html__( 'Ease In', 'meathouse' ),
                        
					],
                    'default' => 'linear',
                    'selectors' => [
                        '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'animation-timing-function: {{VALUE}};',
                    ],
                    
				]
			);
            $repeater->add_control(
        			'shape-duration',
        			[
        				'label' =>  esc_html__( 'Animation Duration', 'meathouse' ),
        				'type' => \Elementor\Controls_Manager::NUMBER,
        				'min' => 0,
        				'max' => 20,
        				'step' => 0.1,
        				'default' => 0.5,
                        'selectors' => [
                            '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'animation-duration: {{VALUE}}s;',
                        ],
        			]
    		);
            $repeater->add_control(
        			'shape-delay',
        			[
        				'label' =>  esc_html__( 'Animation Delay', 'meathouse' ),
        				'type' => \Elementor\Controls_Manager::NUMBER,
        				'min' => 0,
        				'max' => 10,
        				'step' => 0.1,
        				'default' => 0,
                        'selectors' => [
                            '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'animation-delay: {{VALUE}}s;',
                        ],
        			]
    		);
            $repeater->add_control(
				'enable_shade_animation_alternate',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable Alternate', 'meathouse' ),
					'default'      => 'yes',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
					'render_type'  => 'template',
				]
			);
            $repeater->add_control(
				'enable_shade_animation_infinite',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable Infinite', 'meathouse' ),
					'default'      => 'yes',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
					'render_type'  => 'template',
				]
			);
            $repeater->add_responsive_control(
            'custom_width',
            [
                'label' => esc_html__( 'Custom Width', 'meathouse' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max' => 100,
                        'step' => 1,
                    ],
                ],

                'size_units' => [ 'px', '%', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
            );
            $repeater->add_control(
				'enable_rightX',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable Position X Right', 'meathouse' ),
					'default'      => '',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
					'render_type'  => 'template',
				]
		);
         $repeater->add_responsive_control(
            'pos_x', [
                'label' => esc_html__('Position X (%)', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner" => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
						'enable_rightX!' => [ 'yes' ],
				],

            ]
        );
         $repeater->add_responsive_control(
            'pos_x2', [
                'label' => esc_html__('Position X (%)', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner" => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
						'enable_rightX' => [ 'yes' ],
				],

            ]
        );
        $repeater->add_control(
				'enable_bottomY',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        =>  esc_html__( 'Enable Position Y Bottom', 'meathouse' ),
					'default'      => '',
					'label_on'     =>  esc_html__( 'Yes', 'meathouse' ),
					'label_off'    =>  esc_html__( 'No', 'meathouse' ),
					'return_value' => 'yes',
					'render_type'  => 'template',
				]
		);
        $repeater->add_responsive_control(
            'pos_y',[
                'label' => esc_html__('Position Y (%)', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 200,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner" => 'top: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
						'enable_bottomY!' => [ 'yes' ],
				],

            ]
        ); 
        $repeater->add_responsive_control(
            'pos_y2',[
                'label' => esc_html__('Position Y (%)', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 200,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner" => 'bottom: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
						'enable_bottomY' => [ 'yes' ],
				],

            ]
        );
         $repeater->add_responsive_control(
            'custom_opacity',
            [
                'label' => esc_html__( 'Opacity', 'meathouse' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner' => 'opacity: {{SIZE}};',
                ],
            ]
            );   
            $repeater->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'meathouse' ),
				'selector' => '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} .shade_wrap_inner',
			]
		);

            
            $repeater->add_control(
				'enable_shade_animation_blend',
				[
					'label'     =>  esc_html__( 'Mix Blend', 'meathouse' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
                        'inherit'   =>  esc_html__( 'None', 'meathouse' ),
						'multiply'   =>  esc_html__( 'Multiply', 'meathouse' ),
						'darken'   =>  esc_html__( 'Darken', 'meathouse' ),  
					],
                    'selectors' => [
                        '{{WRAPPER}} .shade_animation {{CURRENT_ITEM}} img' => 'mix-blend-mode: {{VALUE}};',
                    ],
                    
				]
			);
            
            
    	   $element->add_control(
    			'shade_list',
    			[
    				'label' =>  esc_html__( 'Shade List', 'meathouse' ),
    				'type' => \Elementor\Controls_Manager::REPEATER,
    				'fields' => $repeater->get_controls(),
    
    			]
    		);

			$element->end_controls_section();
		}
	}
	/**
	 * Render Particles Background output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.12.0
	 * @access public
	 * @param object $element for current element.
	 */
	public function _before_render( $element ) {

		if ( $element->get_name() !== 'section' && $element->get_name() !== 'column' ) {
			return;
		}
        
        $settings = $element->get_settings();
		if ( 'yes' === $settings['jws_enable_shade_animation'] ) {  ?>
        <div class="shade_animation<?php echo esc_attr(' shade-'.$element->get_id()); ?>">
		 <?php   foreach (  $settings['shade_list'] as $item ) {
		         $alternate = ($item['enable_shade_animation_alternate'] == 'yes') ? ' alternate' : '';  
                 $infinite = ($item['enable_shade_animation_infinite'] == 'yes') ? ' infinite' : '';  
         
         ?>
            				<div class="shade_wrap elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                                <div class="shade_wrap_inner<?php echo esc_attr($alternate.$infinite); ?>">
                                    <?php 
                                       // Get image URL
		                        
                                      if($item['enable_render_to_svg'] == 'yes') {
                                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                                            	if( substr($item['shade_image']['url'] , -3, 3) == 'svg' ) {
                                    			   WP_Filesystem();
                                    			     global $wp_filesystem;
                                    				if( preg_match( '/^\d+$/', $item['shade_image']['id'] ) ){
                                        			     if( ! $item['shade_image']['id'] || ! is_numeric( $item['shade_image']['id'] ) ) {
                                                    		$images = '';
                                                    	}
                                                    	$images = get_attached_file( $item['shade_image']['id'] );
                                        			}
                                        			else {
                                        				$images = $item['shade_image']['id'];
                                        			} 
                                                    
                                                    if(function_exists('output_ech')) { ?>
                                                        <div class="svg-shade-animation">
                                                            <?php echo output_ech($wp_filesystem->get_contents( $images )); ?>
                                                        </div>
                                                    <?php }
                                                    
                                    			}
                                      }else {
                                        echo '<img src="' . $item['shade_image']['url'] . '" alt="shade_'.$item['shade_image']['id'].'">'; 
                                      }
                                    ?>  
                                </div>  
                            </div>
            <?php } ?>
        </div>
        <?php
		}
	   }
        
        	/**
	 * Render Particles Background output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.12.0
	 * @access public
	 * @param object $template for current template.
	 * @param object $widget for current widget.
	 */
	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'column' ) {
			return $template;
		}
		$old_template = $template;
		ob_start();
		?>
		<# if( 'yes' == settings.jws_enable_shade_animation ) { #>
        	   <div class="shade_animation shade-{{view.getID()}}">
        					<# _.each( settings.shade_list, function( item, index ) { #>
                            
        					<div class="shade_wrap elementor-repeater-item-{{item._id}}">
                                <div class="shade_wrap_inner
                                    <# {{if( 'yes' == item.enable_shade_animation_alternate ) { #>  alternate  <# }  }}#>
                                    <# {{if( 'yes' == item.enable_shade_animation_infinite ) { #>  infinite  <# }  }}#>">
                                    <img src="{{ item.shade_image.url }}">    
                                </div>
        					</div>
        				<#
        				} ); #>
        		</div>
		<# } #>
		<?php
		$slider_content = ob_get_contents();
		ob_end_clean();
		$template = $slider_content . $old_template;
		return $template;
	}
        
        
}
// Instantiate Plugin Class
shade_Animation::instance();
