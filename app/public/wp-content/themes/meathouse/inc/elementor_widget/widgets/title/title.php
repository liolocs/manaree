<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Jws Heading Widget
 *
 * jws Widget to display heading.
 *
 * @since 1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use ELementor\Group_Control_Box_Shadow;


class Jws_Heading_Elementor_Widget extends Widget_Base {
	public function get_name() {
		return 'jws_widget_heading';
	}

	public function get_title() {
		return esc_html__( 'Jws Heading', 'meathouse' );
	}

	public function get_categories() {
		return array( 'jws-elements' );
	}

	public function get_keywords() {
		return array( 'heading', 'title', 'subtitle', 'text', 'jws', 'dynamic' );
	}

	public function get_icon() {
		return 'jws-elementor-widget-icon widget-icon-heading';
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_heading_title',
			array(
				'label' => esc_html__( 'Title', 'meathouse' ),
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'       => esc_html__( 'Content', 'meathouse' ),
				'description' => esc_html__( 'Select a certain content type among Custom and Dynamic.', 'meathouse' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'custom'  => esc_html__( 'Custom Text', 'meathouse' ),
					'dynamic' => esc_html__( 'Dynamic Content', 'meathouse' ),
				),
				'default'     => 'custom',
			)
		);

		$this->add_control(
			'dynamic_content',
			array(
				'label'       => esc_html__( 'Dynamic Content', 'meathouse' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'title'        => esc_html__( 'Page Title', 'meathouse' ),
					'subtitle'     => esc_html__( 'Page Subtitle', 'meathouse' ),
					'product_cnt'  => esc_html__( 'Products Count', 'meathouse' ),
					'site_tagline' => esc_html__( 'Site Tag Line', 'meathouse' ),
					'site_title'   => esc_html__( 'Site Title', 'meathouse' ),
					'date'         => esc_html__( 'Current Date Time', 'meathouse' ),
					'user_info'    => esc_html__( 'User Info', 'meathouse' ),
				),
				'default'     => 'title',
				'condition'   => array(
					'content_type' => 'dynamic',
				),
				'description' => esc_html__( 'Select the certain dynamic content you want to show in your page. ( ex. page title, subtitle, user info and so on )', 'meathouse' ),
			)
		);

		$this->add_control(
			'userinfo_type',
			array(
				'label'       => esc_html__( 'User Info Field', 'meathouse' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'id'           => esc_html__( 'ID', 'meathouse' ),
					'display_name' => esc_html__( 'Display Name', 'meathouse' ),
					'login'        => esc_html__( 'Username', 'meathouse' ),
					'first_name'   => esc_html__( 'First Name', 'meathouse' ),
					'last_name'    => esc_html__( 'Last Name', 'meathouse' ),
					'description'  => esc_html__( 'Bio', 'meathouse' ),
					'email'        => esc_html__( 'Email', 'meathouse' ),
					'url'          => esc_html__( 'Website', 'meathouse' ),
					'meta'         => esc_html__( 'User Meta', 'meathouse' ),
				),
				'default'     => 'display_name',
				'condition'   => array(
					'content_type'    => 'dynamic',
					'dynamic_content' => 'user_info',
				),
				'description' => esc_html__( 'Select the certain user information you want to show in your page. ( ex. username, email and so on )', 'meathouse' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'meathouse' ),
				'description' => esc_html__( 'Type a certain heading you want to display.', 'meathouse' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Add Your Heading Text Here', 'meathouse' ),
				'placeholder' => esc_html__( 'Enter your title', 'meathouse' ),
				'condition'   => array(
					'content_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'       => esc_html__( 'HTML Tag', 'meathouse' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p'  => 'p',
				),
				'default'     => 'h2',
				'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag,too.', 'meathouse' ),
			)
		);

		$this->add_control(
			'decoration',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Decoration Type', 'meathouse' ),
				'default'     => '',
				'options'     => array(
					''          => esc_html__( 'Simple', 'meathouse' ),
					'cross'     => esc_html__( 'Cross', 'meathouse' ),
					'underline' => esc_html__( 'Underline', 'meathouse' ),
				),
				'description' => esc_html__( 'Select the decoration type among Simple, Cross and Underline options. The Default type is the Simple type.', 'meathouse' ),
			)
		);

		$this->add_control(
			'hide_underline',
			array(
				'label'       => esc_html__( 'Hide Active Underline?', 'meathouse' ),
				'description' => esc_html__( 'Toggle for making your heading has an active underline or not.', 'meathouse' ),
				'type'        => Controls_Manager::SWITCHER,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title::after' => 'content: none',
				),
				'condition'   => array(
					'decoration' => 'underline',
				),
			)
		);

		$this->add_control(
			'title_align',
			array(
				'label'       => esc_html__( 'Title Align', 'meathouse' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'left',
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'meathouse' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'meathouse' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'meathouse' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
                'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'text-align: {{VALUE}};',
				),
				'description' => esc_html__( 'Controls the alignment of title. Options are left, center and right.', 'meathouse' ),
			)
		);


		$this->add_control(
			'show_link',
			array(
				'label'       => esc_html__( 'Show Link?', 'meathouse' ),
				'description' => esc_html__( 'Toggle for making your heading has link or not.', 'meathouse' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_link',
			array(
				'label'     => esc_html__( 'Link', 'meathouse' ),
				'condition' => array(
					'show_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_url',
			array(
				'label'       => esc_html__( 'Link Url', 'meathouse' ),
				'description' => esc_html__( 'Type a certain URL to link through other pages.', 'meathouse' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'       => esc_html__( 'Link Label', 'meathouse' ),
				'description' => esc_html__( 'Type a certain label of your heading link.', 'meathouse' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'link',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'meathouse' ),
				'description' => esc_html__( 'Upload a certain icon of your heading link.', 'meathouse' ),
				'type'        => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_pos',
			array(
				'label'       => esc_html__( 'Icon Position', 'meathouse' ),
				'description' => esc_html__( 'Select a certain position of your icon.', 'meathouse' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'after',
				'options'     => array(
					'after'  => esc_html__( 'After', 'meathouse' ),
					'before' => esc_html__( 'Before', 'meathouse' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'       => esc_html__( 'Icon Spacing (px)', 'meathouse' ),
				'description' => esc_html__( 'Type a certain number for the space between label and icon.', 'meathouse' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-before i' => 'margin-right: {{SIZE}}px;',
					'.elementor-element-{{ID}} .icon-after i'  => 'margin-left: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'       => esc_html__( 'Icon Size (px)', 'meathouse' ),
				'description' => esc_html__( 'Type a certain number for your icon size.', 'meathouse' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} i' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'link_align',
			array(
				'label'       => esc_html__( 'Link Align', 'meathouse' ),
				'description' => esc_html__( 'Choose a certain alignment of your heading link.', 'meathouse' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'link-left'  => array(
						'title' => esc_html__( 'Left', 'meathouse' ),
						'icon'  => 'eicon-text-align-left',
					),
					'link-right' => array(
						'title' => esc_html__( 'Right', 'meathouse' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => 'link-right',
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'       => esc_html__( 'Show Divider?', 'meathouse' ),
				'description' => esc_html__( 'Toggle for making your heading has a divider or not. It is only available in left alignment.', 'meathouse' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'link_align' => 'link-left',
				),
			)
		);

		$this->add_responsive_control(
			'link_gap',
			array(
				'label'       => esc_html__( 'Link Space', 'meathouse' ),
				'description' => esc_html__( 'Type a certain number for the space between heading and link.', 'meathouse' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -50,
						'max'  => 50,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .link' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_title_style',
			array(
				'label' => esc_html__( 'Title', 'meathouse' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'       => esc_html__( 'Title Spacing', 'meathouse' ),
				'description' => esc_html__( 'Controls the padding of your heading.', 'meathouse' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'em',
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => esc_html__( 'Title Color', 'meathouse' ),
				'description' => esc_html__( 'Controls the heading color.', 'meathouse' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '.elementor-element-{{ID}} .title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_link_style',
			array(
				'label' => esc_html__( 'Link', 'meathouse' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'link_typography',
				'description' => esc_html__( 'Controls the link typography.', 'meathouse' ),
				'selector'    => '.elementor-element-{{ID}} .link',
			)
		);

		$this->start_controls_tabs( 'tabs_heading_link' );

		$this->start_controls_tab(
			'tab_link_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'meathouse' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'       => esc_html__( 'Link Color', 'meathouse' ),
				'description' => esc_html__( 'Controls the link color.', 'meathouse' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'meathouse' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'       => esc_html__( 'Link Color', 'meathouse' ),
				'description' => esc_html__( 'Controls the link hover color.', 'meathouse' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_border_style',
			array(
				'label' => esc_html__( 'Border', 'meathouse' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'       => esc_html__( 'Color', 'meathouse' ),
				'description' => esc_html__( 'Controls the border color.', 'meathouse' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-cross .title::before, .elementor-element-{{ID}} .title-cross .title::after, .elementor-element-{{ID}} .title-underline::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_active_color',
			array(
				'label'       => esc_html__( 'Active Color', 'meathouse' ),
				'description' => esc_html__( 'Controls the active border color.', 'meathouse' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_height',
			array(
				'label'       => esc_html__( 'Height', 'meathouse' ),
				'description' => esc_html__( 'Controls the border width.', 'meathouse' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title::before, .elementor-element-{{ID}} .title::after, .elementor-element-{{ID}} .title-wrapper::after' => 'height: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'active_border_height',
			array(
				'label'       => esc_html__( 'Active Border Height', 'meathouse' ),
				'description' => esc_html__( 'Controls the active border width.', 'meathouse' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'height: {{SIZE}}px;',
				),
				'condition'   => array(
					'decoration' => 'underline',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( 'custom' == $settings['content_type'] ) {
			$this->add_inline_editing_attributes( 'title' );
		}
		$this->add_inline_editing_attributes( 'link_label' );
		include 'content.php';
	}
}
