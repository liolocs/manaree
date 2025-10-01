<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

if (class_exists('WooCommerce')):
    final class JwsProductAdvanced extends Widget_Base
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'jws-product-advanced';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('Jws Product Advanced', 'meathouse');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'eicon-products';
        }
        /**
         * @return array
         */
        public function get_categories()
        {
            return ['jws-elements'];
        }
        public function get_script_depends() {
		return [ 'owl-carousel','jws-woocommerce' , 'magnificPopup' , 'swiper'];
	   }
       public function get_style_depends() {
		return [ 'owl-carousel', 'magnificPopup'];
	   }
        /**
         * Register controls
         */
        protected function register_controls()
        {
            $this->start_controls_section(
                'section_setting', [
                'label' => esc_html__('Setting', 'meathouse')
            ]);

            $this->add_control('layout', [
                'label' => esc_html__('Layout', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'meathouse'),
                    'metro' => esc_html__('Metro', 'meathouse'),
                    'carousel' => esc_html__('Carousel', 'meathouse'),
                ],
            ]);
            
            $this->add_control('display', [
                'label' => esc_html__('Display', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout1',
                'options' => [
                    'layout1' => esc_html__('Layout 1', 'meathouse'),
                    'layout2' => esc_html__('Layout 2', 'meathouse'),
                    'product-deal' => esc_html__('Layout Product Deal', 'meathouse'),
                ],
            ]);

            $this->add_control('tabs_filter', [
                'label' => esc_html__('Filter', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'meathouse'),
                    'cate' => esc_html__('Category', 'meathouse'),
                    'asset' => esc_html__('Asset type', 'meathouse'),
                ],

            ]);
            
            $this->add_control('tabs_filter_display', [
                'label' => esc_html__('Filter Display', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'meathouse'),
                    'vertical' => esc_html__('Vertical', 'meathouse'),
                ],
                'condition' => [
                    'tabs_filter!' => ['none'],
                ],
            ]);
            $this->add_control('show_text_starting', [
                'label' => esc_html__('Show Text Before Price', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
            ]);
            $this->end_controls_section();
            
            $this->start_controls_section(
                'section_readmore', [
                'label' => esc_html__('Read More', 'meathouse'),
                'condition' => [
                    'tabs_filter_display' => ['vertical'],
                ],
            ]);
            
             $this->add_control(
    				'readmore',
    				[
    					'label'     => esc_html__( 'Read More', 'meathouse' ),
    					'type'      => Controls_Manager::TEXT,
    					'default'   => 'Create your Jewelry',
    				]
    		);
        
            $this->add_control(
    			'readmore_url',
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
                'section_options', [
                'label' => esc_html__('Options', 'meathouse')
            ]);
            //Cate
            $this->add_control('filter_categories', [
                'label' => esc_html__('Categories', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_jws('product_cat', 2),
                'condition' => [
                    'tabs_filter' => ['none', 'cate'],
                ],
            ]);
            $this->add_control('default_category', [
                'label' => esc_html__('Default categories', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_categories_for_jws('product_cat'),
                'condition' => [
                    'tabs_filter' => 'cate',
                ],
            ]);

            $this->add_control('asset_type', [
                'label' => esc_html__('Asset type', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $this->get_woo_asset_type_for_jws(),
                'condition' => [
                    'tabs_filter' => ['none', 'cate'],
                ],
            ]);

            // Asset
            $this->add_control('filter_assets', [
                'label' => esc_html__('Asset type', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_woo_asset_type_for_jws(2),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);
            $this->add_control('default_asset', [
                'label' => esc_html__('Default asset', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_woo_asset_type_for_jws(),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);
            $this->add_control('filter_categories_for_asset', [
                'label' => esc_html__('Categories', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_jws('product_cat', 2),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);

            // Filter default
            $this->add_control('ex_product_ids', [
                'label' => esc_html__('Exclude product IDs', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_list_posts('product'),
            ]);
            $this->add_control('inc_product_ids', [
                'label' => esc_html__('Include product IDs', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_list_posts('product'),
            ]);
            $this->add_control('orderby', [
                'label' => esc_html__('Order by', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => $this->get_woo_order_by_for_jws(),
            ]);
            $this->add_control('order', [
                'label' => esc_html__('Order', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => $this->get_woo_order_for_jws(),
            ]);

            $this->add_control('posts_per_page', [
                'label' => esc_html__('Products per pages', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]);
            // Grid
            $this->add_responsive_control('columns', [
                'label' => esc_html__('Columns for row', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => esc_html__('12', 'meathouse'),
                    '2' => esc_html__('6', 'meathouse'),
                    '3' => esc_html__('4', 'meathouse'),
                    '4' => esc_html__('3', 'meathouse'),
                    '6' => esc_html__('2', 'meathouse'),
                    '12' => esc_html__('1', 'meathouse'),
                    '20' => esc_html__('5', 'meathouse'),
                    '2' => esc_html__('6', 'meathouse'),
                ],

            ]);

            $this->add_control('pagination', [
                'label' => esc_html__('Pagination', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'meathouse'),
                    'numeric' => esc_html__('Numeric', 'meathouse'),
                    'ajaxload' => esc_html__('Ajax Load More', 'meathouse'),
                ],
                'condition' => [
                    'layout!' => 'carousel',
                    'tabs_filter' => 'none',
                ],
            ]);
            // Carousel
            $this->add_responsive_control('slides_to_show', [
                'label' => esc_html__('Slides to Show', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'condition' => [
                    'layout' => 'carousel',
                ],

            ]);
            
            
             $this->add_control(
    			'enble_muntirow',
    			[
    				'label'        => esc_html__( 'Enble Munti row', 'meathouse' ),
    				'type'         => Controls_Manager::SWITCHER,
    				'return_value' => 'yes',
    				'default'      => '',
                    'condition' => [
                        'layout' => 'carousel',
                    ],
    			]
    		);
            
            $this->add_responsive_control('number_row', [
                'label' => esc_html__('Number Row', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'condition' => [
                    'enble_muntirow' => 'yes',
                ],
            ]);
            
            
            $this->add_responsive_control('number_col_row', [
                'label' => esc_html__('Number Item On Row', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'condition' => [
                    'enble_muntirow' => 'yes',
                ],
            ]);

            $this->add_control('speed', [
                'label' => esc_html__('Carousel: Speed to Scroll', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'condition' => [
                   'layout' => 'carousel',
                 ],

            ]);
            $this->add_control('scroll', [
                'label' => esc_html__('Carousel: Slide to Scroll', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('autoplay', [
                'label' => esc_html__('Carousel: Auto Play', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('center_mode', [
                'label' => esc_html__('Carousel: Center Mode', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
  
            $this->add_responsive_control('infinite', [
                'label' => esc_html__('Carousel: Infinite', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            
             $this->add_responsive_control('center_mode_padding', [
                'label' => esc_html__('Cener Mode Padding', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ]
                ],
                'desktop_default' => [
                    'size' => 220,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'condition' => [
                    'layout' => 'carousel',
                    'center_mode' => 'true',
                ],

            ]);
            
            $this->add_responsive_control('show_pag', [
                'label' => esc_html__('Carousel: Pagination', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('show_nav', [
                'label' => esc_html__('Carousel: Navigation', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            
            $this->add_control('nav_position', [
                'label' => esc_html__('Carousel: Navigation position', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SELECT,
                'default' => 'middle-nav',
                'options' => [
                    'top-nav' => esc_html__('Top', 'meathouse'),
                    'middle-nav' => esc_html__('Middle', 'meathouse'),
                ],
                'condition' => [
                    'show_nav' => 'true',
                    'layout' => 'carousel',
                ],

            ]);
            
            $this->add_responsive_control(
    			'nav_offset_left',
    			[
    				'label' => esc_html__( 'Nav Offset Left', 'meathouse' ),
    				'type' => \Elementor\Controls_Manager::SLIDER,
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
    					'{{WRAPPER}} .products-tab .jws-nav-carousel .jws-button-prev' => 'left: {{SIZE}}{{UNIT}};',
    				],
    			]
    		);
            
            $this->add_responsive_control(
    			'nav_offset_right',
    			[
    				'label' => esc_html__( 'Nav Offset Right', 'meathouse' ),
    				'type' => \Elementor\Controls_Manager::SLIDER,
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
    					'{{WRAPPER}} .products-tab .jws-nav-carousel .jws-button-next' => 'right: {{SIZE}}{{UNIT}};',
    				],
    			]
    		);
            
            
            
            $this->add_responsive_control('hiden_item_outside', [
                'label' => esc_html__('Carousel Hidden Item Out Side', 'meathouse'),
                'description' => esc_html__('', 'meathouse'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'meathouse'),
                'label_off' => esc_html__('Hide', 'meathouse'),
                'return_value' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
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
					'{{WRAPPER}} .product-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .product-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control('bg_image_color', [
                'label' => esc_html__('Background For Image', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-item .product-image' => 'background: {{VALUE}};'
                ]
        ]);
        $this->end_controls_section();
        
           $this->start_controls_section(
                'content_item_style', [
                'label' => esc_html__('Content item style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            
            $this->add_control('item_title_style', [
                'label' => esc_html__('Product name', 'meathouse'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]);
    
            $this->add_responsive_control(
				'product_name_margin',
				[
					'label' 		=> esc_html__( 'Margin', 'meathouse' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .product-item.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],

					'separator' => 'before',
				]
		  );

        $this->end_controls_section();
        
        $this->start_controls_section(
                'normal_style_settings', [
                'label' => esc_html__('Heading style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);

            $this->add_control('title_color', [
                'label' => esc_html__('Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meathouseduct-title' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .meathouseduct-title',
                ]
            );
            $this->add_control('title_background', [
                'label' => esc_html__('Background', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meathouseduct-title' => 'background: {{VALUE}};'
                ]
            ]);
            
            $this->add_responsive_control(
				'title_margin',
				[
					'label' 		=> esc_html__( 'Margin', 'meathouse' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .meathouseduct-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],

					'separator' => 'before',
				]
		  );

            $this->end_controls_section();
            
             $this->start_controls_section(
                'banner_style_settings', [
                'label' => esc_html__('Banner style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'design' => 'design_2',
                ],
            ]);

            $this->add_control('title1_color', [
                'label' => esc_html__('Title 1 Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-banner .banner-text h6' => 'color: {{VALUE}};'
                ]
            ]);
            
            $this->add_control('title2_color', [
                'label' => esc_html__('Title 2 Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-banner .banner-text h3' => 'color: {{VALUE}};'
                ]
            ]);
            
            $this->add_control('title3_color', [
                'label' => esc_html__('Title 3 Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-banner .banner-text p' => 'color: {{VALUE}};'
                ]
            ]);



            $this->end_controls_section();
            
            
             $this->start_controls_section(
                'pagination_style_settings', [
                'label' => esc_html__('Pagination style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);

            
            $this->add_responsive_control(
    			'pagination_margin',
    			[
    				'label' => esc_html__( 'Margin', 'meathouse' ),
    				'type' => Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px', 'em', '%' ],
    				'selectors' => [
    					'{{WRAPPER}} .jws-products-load-more , {{WRAPPER}} .jws-products-load-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
    			]
    		);


            $this->end_controls_section();
            
            
            $this->start_controls_section(
                'filter_style_settings', [
                'label' => esc_html__('Filter style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_responsive_control(
                'filter_align',
                [
                    'label' => esc_html__('Align', 'meathouse'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__('Left', 'meathouse'),
                            'icon' => 'fa fa-align-left',
                        ], 'center' => [
                            'title' => esc_html__('Center', 'meathouse'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'meathouse'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .jws-head-filter' => 'text-align: {{VALUE}};'
                    ]
                ]
            );
            $this->add_responsive_control(
    			'filter_padding',
    			[
    				'label' => esc_html__( 'Padding', 'meathouse' ),
    				'type' => Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px', 'em', '%' ],
    				'selectors' => [
    					'{{WRAPPER}} .jws-head-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
    			]
    		);
            $this->add_responsive_control(
    			'filter_margin',
    			[
    				'label' => esc_html__( 'Margin', 'meathouse' ),
    				'type' => Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px', 'em', '%' ],
    				'selectors' => [
    					'{{WRAPPER}} .jws-head-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
    			]
    		);
            $this->add_responsive_control(
    			'filter_item_margin',
    			[
    				'label' => esc_html__( 'Item Margin', 'meathouse' ),
    				'type' => Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px', 'em', '%' ],
    				'selectors' => [
    					'{{WRAPPER}} .jws-head-filter .jws-ajax-load li:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
    			]
    		);
            $this->add_control('filter_color', [
                'label' => esc_html__('Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-head-filter a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('filter_active_color', [
                'label' => esc_html__('Active Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-head-filter a.active, {{WRAPPER}} .jws-head-filter a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'filter_typography',
                    'selector' => '{{WRAPPER}} .jws-head-filter a',
                ]
            );

            $this->end_controls_section();
            $this->start_controls_section(
                'carousel_style_settings', [
                'label' => esc_html__('Carousel style', 'meathouse'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('arrow_style', [
                'label' => esc_html__('Arrow', 'meathouse'),
                'type' => Controls_Manager::HEADING,
            ]);

            $this->add_control('arrow_color', [
                'label' => esc_html__('Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel .jws-carousel-btn' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('arrow_hover_color', [
                'label' => esc_html__('Hover Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel .jws-carousel-btn:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('arrow_size', [
                'label' => esc_html__('Size', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel .jws-carousel-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->add_control('dotted_style', [
                'label' => esc_html__('Dotted', 'meathouse'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]);
            $this->add_control('dotted_color', [
                'label' => esc_html__('Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel ul.slick-dots li' => 'background: {{VALUE}};'
                ]
            ]);
            $this->add_control('dotted_hover_color', [
                'label' => esc_html__('Hover & Active Color', 'meathouse'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel ul.slick-dots li:hover, {{WRAPPER}} .jws-carousel ul.slick-dots li.slick-active' => 'background: {{VALUE}};'
                ]
            ]);
            $this->add_control('dotted_size', [
                'label' => esc_html__('Size', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel ul.slick-dots li' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jws-carousel ul.slick-dots li.slick-active' => 'width: calc({{SIZE}}{{UNIT}} * 3);',
                ],
            ]);
            $this->add_control('dotted_radius', [
                'label' => esc_html__('Border radius', 'meathouse'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jws-carousel ul.slick-dots li' => 'border-radius: {{SIZE}}{{UNIT}};'
                ],
            ]);
            $this->end_controls_section();
        }

        /**
         * Retrieve the list of scripts the image carousel widget depended on.
         *
         * Used to set scripts dependencies required to run the widget.
         *
         * @since 1.3.0
         * @access public
         *
         * @return array Widget scripts dependencies.
         */

        protected function get_categories_for_jws($taxonomy, $select = 1)
        {
            $data = array();
    
            $query = new \WP_Term_Query(array(
                'hide_empty' => true,
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
        protected function get_woo_asset_type_for_jws($select = 1)
        {
        if ($select == 1) {
            $asset_type = array(
                'all'          => esc_html__('All', 'meathouse'),
                'latest'       => esc_html__('New Arrivals', 'meathouse'),
                'featured'     => esc_html__('Featured', 'meathouse'),
                'onsale'       => esc_html__('On sale', 'meathouse'),
                'deal'         => esc_html__('Deal', 'meathouse'),
                'best-selling' => esc_html__('Best Seller', 'meathouse'),
                'toprate'      => esc_html__('Top Rate', 'meathouse'),
            );
        } else {
            $asset_type = array(
                'latest'       => esc_html__('New Arrivals', 'meathouse'),
                'featured'     => esc_html__('Featured', 'meathouse'),
                'onsale'       => esc_html__('On sale', 'meathouse'),
                'deal'         => esc_html__('Deal', 'meathouse'),
                'best-selling' => esc_html__('Best Seller', 'meathouse'),
                'toprate'      => esc_html__('Top Rate', 'meathouse'),
            );
        }

        return $asset_type;
        }
        protected function get_list_posts($post_type = 'post')
        {
            $args = array(
                'post_type'        => $post_type,
                'suppress_filters' => true,
                'posts_per_page'   => 300,
                'no_found_rows'    => true,
            );
    
            $the_query = new \WP_Query($args);
            $results   = [];
    
            if (is_array($the_query->posts) && count($the_query->posts)) {
                foreach ($the_query->posts as $post) {
                    $results[ $post->ID ] = sanitize_text_field($post->post_title);
                }
            }
    
            return $results;
        }
            /**
     * Get oder by
     *
     * @return array oder_by
     */
    protected function get_woo_order_by_for_jws()
    {
        $order_by = array(
            'date'       => esc_html__('Date', 'meathouse'),
            'menu_order' => esc_html__('Menu order', 'meathouse'),
            'title'      => esc_html__('Title', 'meathouse'),
            'rand'       => esc_html__('Random', 'meathouse'),
        );

        return $order_by;
    }

    /**
     * Get oder
     *
     * @return array order
     */
    protected function get_woo_order_for_jws()
    {
        $order = array(
            'desc' => esc_html__('DESC', 'meathouse'),
            'asc'  => esc_html__('ASC', 'meathouse'),
        );

        return $order;
    }
        /**
         * Render
         */
        protected function render()
        {
            // default settings
            $settings = array_merge([
                'title' => '',
                'tabs_filter' => 'cate',
                'filter_categories' => '',
                'default_category' => '',
                'asset_type' => 'all',
                'filter_assets' => '',
                'default_asset' => '',
                'product_ids' => '',
                'orderby' => 'date',
                'order' => 'desc',
                'posts_per_page' => 6,
                'columns' => '',
                'pagination' => '',
                'slides_to_show' => 4,
                'speed' => 5000,
                'scroll' => 1,
                'autoplay' => 'true',
                'show_pag' => 'true',
                'show_nav' => 'true',
                'nav_position' => 'middle-nav',
               

            ], $this->get_settings_for_display());

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('title', 'class', 'meathouseduct-title');

            include 'content.php';
        }
    }
endif;