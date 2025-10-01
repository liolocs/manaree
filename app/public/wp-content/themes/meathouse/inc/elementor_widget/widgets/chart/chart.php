<?php
namespace Elementor;

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Utils as Utils;
use \Elementor\Widget_Base as Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Charts
 *
 * Elementor widget for team vision
 *
 * @since 1.0.0
 */
class PieCharts extends Widget_Base {

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
		return 'jws-pie-charts';
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
		return esc_html__( 'Jws Pie Charts', 'meathouse' );
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
		return 'eicon-dot-circle-o';
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
		return [ 'easypiechart' , 'appear'];
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
		return [ 'general' ];
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
			'section_content',
			[
				'label' => esc_html__( 'Content', 'meathouse' ),
			]
		);

		$this->add_control(
			'percent',
			[
				'label' => esc_html__( 'Percent', 'meathouse' ),
				'type' => Controls_Manager::SELECT,
				'default' => '50',
				'options' => [
										'1' 	=>   '1', 
										'2' 	=>   '2', 
										'3' 	=>   '3', 
										'4' 	=>   '4', 
										'5' 	=>   '5', 
										'6' 	=>   '6', 
										'7' 	=>   '7', 
										'8' 	=>   '8', 
										'9' 	=>   '9', 
										'10' 	=>   '10',
										'11' 	=>   '11',
										'12' 	=>   '12',
										'13' 	=>   '13',
										'14' 	=>   '14',
										'15' 	=>   '15',
										'16' 	=>   '16',
										'17' 	=>   '17',
										'18' 	=>   '18',
										'19' 	=>   '19',
										'20' 	=>   '20',
										'21' 	=>   '21',
										'22' 	=>   '22',
										'23' 	=>   '23',
										'24' 	=>   '24',
										'25' 	=>   '25',
										'26' 	=>   '26',
										'27' 	=>   '27',
										'28' 	=>   '28',
										'29' 	=>   '29',
										'30' 	=>   '30',
										'31' 	=>   '31',
										'32' 	=>   '32',
										'33' 	=>   '33',
										'34' 	=>   '34',
										'35' 	=>   '35',
										'36' 	=>   '36',
										'37' 	=>   '37',
										'38' 	=>   '38',
										'39' 	=>   '39',
										'40' 	=>   '40',
										'41' 	=>   '41',
										'42' 	=>   '42',
										'43' 	=>   '43',
										'44' 	=>   '44',
										'45' 	=>   '45',
										'46' 	=>   '46',
										'47' 	=>   '47',
										'48' 	=>   '48',
										'49' 	=>   '49',
										'50' 	=>   '50',
										'51' 	=>   '51',
										'52' 	=>   '52',
										'53' 	=>   '53',
										'54' 	=>   '54',
										'55' 	=>   '55',
										'56' 	=>   '56',
										'57' 	=>   '57',
										'58' 	=>   '58',
										'59' 	=>   '59',
										'60' 	=>   '60',
										'61' 	=>   '61',
										'62' 	=>   '62',
										'63' 	=>   '63',
										'64' 	=>   '64',
										'65' 	=>   '65',
										'66' 	=>   '66',
										'67' 	=>   '67',
										'68' 	=>   '68',
										'69' 	=>   '69',
										'70' 	=>   '70',
										'71' 	=>   '71',
										'72' 	=>   '72',
										'73' 	=>   '73',
										'74' 	=>   '74',
										'75' 	=>   '75',
										'76' 	=>   '76',
										'77' 	=>   '77',
										'78' 	=>   '78',
										'79' 	=>   '79',
										'80' 	=>   '80',
										'81' 	=>   '81',
										'82' 	=>   '82',
										'83' 	=>   '83',
										'84' 	=>   '84',
										'85' 	=>   '85',
										'86' 	=>   '86',
										'87' 	=>   '87',
										'88' 	=>   '88',
										'89' 	=>   '89',
										'90' 	=>   '90',
										'91' 	=>   '91',
										'92' 	=>   '92',
										'93' 	=>   '93',
										'94' 	=>   '94',
										'95' 	=>   '95',
										'96' 	=>   '96',
										'97' 	=>   '97',
										'98' 	=>   '98',
										'99' 	=>   '99',
										'100' 	=>	 '100'				
				]
			]
		);
        $this->add_control(
			'after_percent',
			[
				'label' => esc_html__( 'After Percent', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,				
				'default' => '%'
			]
		);
        $this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,				
				'default' => ''
			]
		);
		$this->add_control(
			'bar_width',
			[
				'label' => esc_html__( 'Width (example: 400)', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,				
				'default' => '400'
			]
		);
        $this->add_control(
			'line_bar_width',
			[
				'label' => esc_html__( 'Line Width (example: 5)', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,				
				'default' => '10'
			]
		);
		
		$this->end_controls_section();


		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Bar Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FC615D'
			]
		);
        $this->add_control(
			'bar_color2',
			[
				'label' => esc_html__( 'Bar Color 2', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FC615D'
			]
		);

		$this->add_control(
			'track_color',
			[
				'label' => esc_html__( 'Track Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FF0000'
			]
		);
       

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Number Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
                'selectors' => [
							'{{WRAPPER}} .chartselementor-percent' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .chartselementor-percent',
			]
		);
        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
                'selectors' => [
							'{{WRAPPER}} .title' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'meathouse'),
				'selector' => '{{WRAPPER}} .title',
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
		static $instance = 0;
		$instance++;		
		$settings = $this->get_settings_for_display();	

		$bar_color					= esc_html($settings['bar_color']);
        $bar_color2					= esc_html($settings['bar_color2']);
		$track_color				= esc_html($settings['track_color']);
		$percent					= esc_html($settings['percent']);
        $after_percent              = esc_html($settings['after_percent']);
		$bar_width					= esc_html($settings['bar_width']);
	    $title =  (!empty($settings['title'])) ? '<h3 class="title">'.$settings['title'].'</h3>' : '';  
		$responsive_on = '';
		if(empty($bar_width) || $bar_width == '') : $bar_width = '300'; endif;
        if(empty($settings['line_bar_width']) || $settings['line_bar_width'] == '') : $settings['line_bar_width'] = '10'; endif;
        
        
		if($bar_width >= 300) : $responsive_on = ' chartselementor_pie_chart_responsive'; endif;
	
		
        echo '<div class="chartselementor chartselementor-pie-chart">';
			
			echo '<div class="chartselementor-chart-item-container'.esc_html($responsive_on).'">
				<div class="chartselementor-chart chartselementor-rand'.esc_html($instance).' easyPieChart" 
						data-percent="'.esc_html($percent).'" 
						data-color="'.esc_html($bar_color).'" 
                        data-color2="'.esc_html($bar_color2).'"
						data-colortrack="'.esc_html($track_color).'"
						data-piewidth="'.esc_html($bar_width).'"
                        data-line-width="'.$settings['line_bar_width'].'"
						style="width: '.esc_html($bar_width).'px;height: '.esc_html($bar_width).'px;line-height: '.esc_html($bar_width).'px;">
						<span class="chartselementor-percent">'.$title.'<span class="chartselementor-percent-step">'.$percent.'</span><span class="">'.$after_percent.'</span></span>
                        
				</div>
			</div>';

		echo '</div>';	
		
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
