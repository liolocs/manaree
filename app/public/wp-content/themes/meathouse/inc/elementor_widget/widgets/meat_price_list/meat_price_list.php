<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Meat_price_list extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'meat_price_list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Jws Meat Price list', 'meathouse' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'meat list', 'price', 'list' ];
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon List', 'meathouse' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Name', 'meathouse' ),
				'default' => esc_html__( 'Name', 'meathouse' ),
			]
		);
        $repeater->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Price', 'meathouse' ),
				'default' => esc_html__( '100$', 'meathouse' ),
			]
		);
        
        $repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'meathouse' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Description', 'meathouse' ),
				'default' => esc_html__( 'Description', 'meathouse' ),
			]
		);
        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'meathouse' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
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
		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
			]
		);
        
		$this->end_controls_section();
     
        
		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => esc_html__( 'List', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'meathouse' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-price-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

	

		$this->end_controls_section();
        
        $this->start_controls_section(
			'section_icon_image',
			[
				'label' => esc_html__( 'Image', 'meathouse' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'meathouse' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-price-list .elementor-price-list-items li .image' => 'width: {{SIZE}}{{UNIT}}',

				],
			]
		);
        $this->add_responsive_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'meathouse' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-price-list .elementor-price-list-items li .image' => 'margin-right: {{SIZE}}{{UNIT}}',

				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	   
		$settings = $this->get_settings_for_display();
	
        
        $this->add_render_attribute( 'main_list', 'class', 'jws-price-list' );
     
		$this->add_render_attribute( 'icon_list', 'class', 'elementor-price-list-items' );
  
        
		?>
        <div <?php echo ''.$this->get_render_attribute_string( 'main_list' ); ?>>

    		<ul <?php echo ''.$this->get_render_attribute_string( 'icon_list' ); ?>>
    			<?php
              
    			foreach ( $settings['icon_list'] as $index => $item ) :
    		
    				$migration_allowed = Icons_Manager::is_migration_allowed();
                    $item_key = 'item_' . $index;
                 
                    $this->add_render_attribute($item_key, 'class', 'elementor-price-list-item' );
    				?>
    				<li <?php echo ''.$this->get_render_attribute_string( $item_key ); ?>>
                        
                            <?php if(!empty($item['image']['id'])) echo '<div class="image">' . \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item ).'</div>'; ?> 
                     
                        <div class="content">
                            <div class="content-top">
                                <h6><?php echo esc_html($item['text']); ?></h6>
                                <span class="line"></span>
                                <span class="price"><?php echo esc_html($item['price']); ?></span>
                            </div>
                            <div class="description">
                                <?php echo esc_html($item['description']); ?>
                            </div>
                        </div>
    				</li>
    				<?php
    			endforeach;
    			?>
    		</ul>
        </div>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {

	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
	}
}
