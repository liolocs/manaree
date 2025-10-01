# Theme Modifications Guide - MeatHouse Child Theme

This document provides comprehensive guidelines for understanding and extending the modifications made to the MeatHouse child theme. It serves as a reference for AI assistants and developers working on this WordPress theme.

## Overview

**Parent Theme**: MeatHouse by JWSThemes
**Child Theme**: MeatHouse Child
**WordPress Theme Structure**: Child theme approach for safe customization
**WooCommerce**: Fully integrated with extensive template customization options
**Page Builder**: Elementor with 30+ custom widgets
**Text Domain**: `meathouse`

### Quick Start
1. All modifications should be made in the child theme (`meathouse-child/`)
2. Never modify parent theme files directly
3. Use function prefix `meathouse_child_` for custom functions
4. CSS custom properties are defined in `:root` for easy color theming
5. WooCommerce templates can be overridden by copying to child theme with same directory structure

## Table of Contents
1. [Theme Architecture](#theme-architecture)
2. [Content Modification System](#content-modification-system)
3. [CSS Organization](#css-organization)
4. [Customizer Integration](#customizer-integration)
5. [Template Modifications](#template-modifications)
6. [Common Patterns and Best Practices](#common-patterns-and-best-practices)
7. [WooCommerce Customization](#woocommerce-customization)
8. [Elementor Integration](#elementor-integration)
9. [Adding New Sections](#adding-new-sections)
10. [Troubleshooting](#troubleshooting)
11. [Function Naming Conventions](#function-naming-conventions)
12. [Future Development Guidelines](#future-development-guidelines)

## Theme Architecture

### Current File Structure
```
meathouse-child/
├── functions.php                    # Main child theme functions (auto-generated)
├── style.css                       # Child theme styles
├── screenshot.jpg                  # Theme screenshot
└── THEME_MODIFICATIONS_GUIDE.md   # This documentation file
```

### Recommended Extended Structure (Create as Needed)
```
meathouse-child/
├── functions.php                    # Main child theme functions
├── style.css                       # Child theme styles
├── assets/
│   ├── css/                        # Modular CSS files
│   │   ├── style.css               # Main styles
│   │   ├── style-min.css           # Minified main styles
│   │   ├── hero-section.css        # Hero section specific styles
│   │   ├── checkout.css            # Checkout page styles
│   │   ├── product-category-pages.css
│   │   ├── product-page.css
│   │   ├── footer-section.css
│   │   ├── header.css
│   │   └── blog-post.css
│   ├── js/                         # JavaScript files
│   └── images/                     # Child theme images
├── inc/
│   ├── extras.php                  # Content modification functions
│   └── customizer/
│       └── customizer-options/
│           ├── meathouse-hero.php  # Hero section customizer
│           └── payment-banner.php  # Payment banner customizer
└── template-parts/
    └── sections/
        └── section-hero.php        # Hero section template
```

### Key Principles
1. **Child Theme Approach**: All modifications are made in the child theme to preserve parent theme integrity
2. **Modular CSS**: Each major section has its own CSS file for better organization
3. **Content Modification via Output Buffer**: Use `ob_start()` and filters to modify page content
4. **Localization Ready**: All user-facing text should use proper WordPress localization functions

### Parent Theme (MeatHouse) Structure Overview
The parent MeatHouse theme includes:
- **Theme Constants**: `JWS_ABS_PATH`, `JWS_ABS_PATH_ELEMENT`, `JWS_ABS_PATH_WC`, `JWS_URI_PATH`
- **WooCommerce Templates**: Extensive `/woocommerce/` directory with cart, checkout, single-product templates
- **Elementor Widgets**: 30+ custom Elementor widgets in `/inc/elementor_widget/widgets/`
- **Template Parts**: `/template-parts/` for header, footer, content layouts
- **Text Domain**: `meathouse`

## Content Modification System

### Core Function: `modify_page_content()`
Located in `inc/extras.php`, this function processes all content modifications:

```php
function modify_page_content( $content ) {
    $content = replace_burger_companion_image( $content );
    $content = reorder_main_content_elements( $content );
    $content = remove_home_slider_thumbs( $content );
    $content = modify_service_section( $content );
    $content = convert_service_overlay_to_link( $content );
    $content = replace_clipart_shapes( $content );
    $content = modify_product_descriptions( $content );
    $content = modify_product_category_layout( $content );
    $content = modify_mobile_header( $content );
    $content = modify_checkout_page( $content );
    $content = translate_checkout_strings_to_french( $content );
    $content = add_payment_banner( $content );
    return $content;
}
```

### Hook Implementation
In `functions.php`:
```php
// Hook into the output buffer to modify content before it's sent to browser
add_action( 'init', function() {
    ob_start( 'modify_page_content' );
} );

// Also hook into the_content filter as an additional layer
add_filter( 'the_content', 'modify_page_content' );
```

### Content Modification Patterns

#### 1. Image Replacement
```php
function meathouse_child_replace_image( $content ) {
    // Example: Replace a specific image with a custom one
    $new_src = get_stylesheet_directory_uri() . '/assets/images/custom-logo.png';
    $content = preg_replace(
        '/src=["\']([^"\']*\/assets\/images\/original-image\.png)["\']/i',
        'src="' . $new_src . '"',
        $content
    );
    return $content;
}
```

#### 2. Element Reordering
```php
function reorder_main_content_elements( $content ) {
    $content = preg_replace_callback(
        '/<div class="main-content[^"]*"[^>]*>(.*?)<\/div>/s',
        function( $matches ) {
            // Extract and reorder elements
            // Return modified content
        },
        $content
    );
    return $content;
}
```

#### 3. Text Replacement/Translation
```php
function meathouse_child_translate_strings( $content ) {
    // Example: Translate or modify specific strings
    $translations = array(
        'Original Text' => 'Replacement Text',
        'Add to Cart' => 'Ajouter au Panier',
        'Select Options' => 'Sélectionnez les Options',
        // ... more translations
    );

    foreach ( $translations as $original => $replacement ) {
        $content = str_replace( $original, $replacement, $content );
    }
    return $content;
}
```

#### 4. Page-Specific Modifications
Always check page context:
```php
function meathouse_child_modify_checkout( $content ) {
    if ( ! is_checkout() ) {
        return $content;
    }
    // Checkout-specific modifications
    return $content;
}

function meathouse_child_modify_product_page( $content ) {
    if ( ! is_product() ) {
        return $content;
    }
    // Product page-specific modifications
    return $content;
}

function meathouse_child_modify_shop_page( $content ) {
    if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
        return $content;
    }
    // Shop/archive page-specific modifications
    return $content;
}
```

## CSS Organization

### File Naming Convention
- `{section-name}.css` for section-specific styles
- `{section-name}-min.css` for minified versions (if needed)

### CSS Loading in functions.php

**Current Auto-Generated Setup:**
```php
function chld_thm_cfg_parent_css() {
    wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css',
        array( 'circula','jws-jwsicon','jws-default','magnificPopup','slick','awesome','jws-style','jws-style-reset' ) );
}
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );
```

**Enhanced Setup with Modular CSS Files:**
```php
function meathouse_child_enqueue_styles() {
    $theme   = wp_get_theme( 'MeatHouse' );
    $version = $theme->get( 'Version' );

    // Parent theme styles are already loaded via chld_thm_cfg_parent_css()

    // Load main child theme CSS
    wp_enqueue_style( 'meathouse-child-style', get_stylesheet_directory_uri() . '/style.css',
        array( 'chld_thm_cfg_parent' ), $version );

    // Load additional modular CSS files as needed
    if ( file_exists( get_stylesheet_directory() . '/assets/css/hero-section.css' ) ) {
        wp_enqueue_style( 'hero-section-style', get_stylesheet_directory_uri() . '/assets/css/hero-section.css',
            array( 'meathouse-child-style' ), $version );
    }

    if ( is_checkout() && file_exists( get_stylesheet_directory() . '/assets/css/checkout.css' ) ) {
        wp_enqueue_style( 'checkout-style', get_stylesheet_directory_uri() . '/assets/css/checkout.css',
            array( 'meathouse-child-style' ), $version );
    }

    // Add more modular CSS files as needed
}
add_action( 'wp_enqueue_scripts', 'meathouse_child_enqueue_styles', 20 );
```

**MeatHouse Parent Theme Style Dependencies:**
- `circula` - Font family
- `jws-jwsicon` - Icon font
- `jws-default` - Default theme styles
- `magnificPopup` - Lightbox/popup library
- `slick` - Carousel/slider library
- `awesome` - Font Awesome icons
- `jws-style` - Main theme styles
- `jws-style-reset` - CSS reset styles

### CSS Best Practices
1. Use CSS custom properties for colors defined in MeatHouse theme:
   - `var(--main)` - Primary color (#0d354f)
   - `var(--secondary)` - Secondary color (#a3714e)
   - `var(--heading)` - Heading color
   - `var(--body)` - Body text color
   - `var(--light)`, `var(--dark)`, `var(--bodybg)`
   - `var(--btn-bgcolor)`, `var(--btn-color)`, `var(--btn-bgcolor2)`, `var(--btn-color2)`
2. Page-specific targeting: `body.woocommerce-checkout`, `body.tax-product_cat`, `body.single-product`
3. Use `!important` sparingly and only when overriding parent theme styles
4. Mobile-first responsive design with media queries (MeatHouse uses `@media only screen and (max-width: 767px)`)

## Customizer Integration

### File Structure
Customizer options should be organized in separate files:
- `inc/customizer/customizer-options/meathouse-hero.php`
- `inc/customizer/customizer-options/payment-banner.php`
- Add more as needed for different sections

### Standard Customizer Pattern
```php
function meathouse_child_section_customizer($wp_customize) {
    // Add section
    $wp_customize->add_section(
        'meathouse_child_section_id',
        array(
            'title' => __('Section Title', 'meathouse'),
            'priority' => 10,
            'panel' => 'panel_id', // Optional
        )
    );

    // Add setting
    $wp_customize->add_setting(
        'meathouse_child_setting_name',
        array(
            'default' => 'default_value',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_text',
        )
    );

    // Add control
    $wp_customize->add_control(
        'meathouse_child_setting_name',
        array(
            'label' => __('Control Label', 'meathouse'),
            'section' => 'meathouse_child_section_id',
            'type' => 'text', // text, checkbox, select, etc.
        )
    );
}
add_action('customize_register', 'meathouse_child_section_customizer');
```

### Selective Refresh Implementation
```php
// Add selective refresh support
$wp_customize->get_setting('setting_name')->transport = 'postMessage';

$wp_customize->selective_refresh->add_partial(
    'setting_name',
    array(
        'selector' => '.target-element',
        'render_callback' => 'render_callback_function',
        'container_inclusive' => false,
    )
);
```

### Sanitization Functions
Always include proper sanitization:
```php
if (!function_exists('meathouse_child_sanitize_checkbox')) {
    function meathouse_child_sanitize_checkbox($input) {
        return ($input == true) ? '1' : '0';
    }
}

if (!function_exists('meathouse_child_sanitize_text')) {
    function meathouse_child_sanitize_text($input) {
        return sanitize_text_field($input);
    }
}

if (!function_exists('meathouse_child_sanitize_url')) {
    function meathouse_child_sanitize_url($input) {
        return esc_url_raw($input);
    }
}
```

### Localization
All customizer labels should be in french
```php
// French example
'label' => __('Activer la Bannière', 'meathouse'),
'description' => __('Cochez pour activer la bannière de paiement', 'meathouse'),
```

## Template Modifications

### Moving Templates to Child Theme
When modifying parent theme templates:
1. Copy the template file to the child theme maintaining directory structure
2. Make modifications in the child theme version
3. Update any hardcoded paths to use child theme functions

### Example: Hero Section Template
Located at `template-parts/sections/section-hero.php`:
```php
// Get customizer values
$hero_background_image = get_theme_mod('hero_background_image');
$hero_title = get_theme_mod('hero_title');

// Use child theme assets
$logo_src = get_stylesheet_directory_uri() . '/assets/images/logo.png';
```

## Common Patterns and Best Practices

### 1. Duplicate Prevention
For functions that might run multiple times:
```php
// Check if content was already modified
if (strpos($content, 'unique-identifier') !== false) {
    return $content;
}
```

### 2. Page Context Checking
Always verify the correct page context:
```php
if (!is_checkout() && !is_product()) {
    return $content;
}
```

### 3. Regex Patterns for Content Modification
Common patterns used:

#### Finding elements with specific classes:
```php
'/<div[^>]*class="[^"]*target-class[^"]*"[^>]*>/'
```

#### Capturing content within elements:
```php
'/<div class="target">(.*?)<\/div>/s'
```

#### Finding nested elements:
```php
'/<div[^>]*class="parent"[^>]*>.*?<div class="child">/s'
```

### 4. Complex Element Injection
For injecting content at the end of elements with nested divs:
```php
// Use div counting method for complex structures
if (preg_match('/(<div[^>]*id="target"[^>]*>)/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
    $start_pos = $matches[0][1] + strlen($matches[0][0]);
    $div_count = 1;
    $pos = $start_pos;
    // ... div counting logic
}
```

### 5. Fallback Patterns
Always provide fallbacks for regex operations:
```php
$patterns = array(
    '/pattern1/',
    '/pattern2/',
    '/pattern3/'
);

foreach ($patterns as $pattern) {
    if (preg_match($pattern, $content)) {
        // Apply modification
        break;
    }
}
```

## WooCommerce Customization

### MeatHouse WooCommerce Structure
The parent theme has extensive WooCommerce templates located in `/woocommerce/`:
- **Archive/Shop Pages**: `archive-product.php`, `archive-layout/`
- **Cart**: `cart/` directory with cart templates
- **Checkout**: `checkout/` directory with checkout form templates
- **Single Product**: `single-product.php`, `single-product/` directory
- **Product Loop**: `loop/` directory with product display templates
- **My Account**: `myaccount/` directory
- **Notices**: `notices/` directory

### Overriding WooCommerce Templates
To customize WooCommerce templates in the child theme:
1. Copy the template from `/meathouse/woocommerce/{path}` to `/meathouse-child/woocommerce/{path}`
2. Maintain the exact directory structure
3. Modify the copied template
4. WordPress will automatically use the child theme version

**Example**: To customize the checkout page:
```
/meathouse/woocommerce/checkout/form-checkout.php
→ Copy to →
/meathouse-child/woocommerce/checkout/form-checkout.php
```

### WooCommerce Hooks
Use WooCommerce hooks for modifications instead of template overrides when possible:
```php
// Modify product price display
add_filter( 'woocommerce_get_price_html', 'meathouse_child_custom_price', 10, 2 );

// Add custom content after product title
add_action( 'woocommerce_single_product_summary', 'meathouse_child_custom_content', 6 );

// Customize checkout fields
add_filter( 'woocommerce_checkout_fields', 'meathouse_child_custom_checkout_fields' );
```

## Elementor Integration

### MeatHouse Elementor Widgets
The parent theme includes 30+ custom Elementor widgets in `/inc/elementor_widget/widgets/`:
- Product-related: `product_tabs`, `product_group`, `category_tabs`
- Shop features: `mini-cart`, `wishlist`
- Content: `slider`, `testimonial_slider`, `team`, `blog`
- Special: `meat_box`, `meat_price_list`, `menu_list`

### Working with Elementor in Child Theme
To customize Elementor widgets or add new ones:
1. Create widget file in child theme: `inc/elementor_widget/widgets/{widget-name}/{widget-name}.php`
2. Register widget in `functions.php`:
```php
add_action( 'elementor/widgets/widgets_registered', 'meathouse_child_register_widgets' );
function meathouse_child_register_widgets() {
    require_once get_stylesheet_directory() . '/inc/elementor_widget/widgets/custom-widget/custom-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \MeatHouse_Child_Custom_Widget() );
}
```

### Elementor Template Override
To override Elementor templates:
- Copy from parent theme to child theme maintaining structure
- Use `elementor/theme/templates_path` filter if needed

## Adding New Sections

### 1. Create CSS File
- Create `assets/css/new-section.css`
- Add section-specific styles
- Use existing CSS custom properties for consistency

### 2. Enqueue CSS
Add to `functions.php`:
```php
wp_enqueue_style( 'new-section-style', get_stylesheet_directory_uri() . '/assets/css/new-section.css', array( 'meathouse-child-style' ), $version );
```

### 3. Create Customizer Options
- Create `inc/customizer/customizer-options/new-section.php`
- Include in `functions.php`: `require get_stylesheet_directory() . '/inc/customizer/customizer-options/new-section.php';`

### 4. Add Content Modification Function
- Add function to `inc/extras.php`
- Include in `modify_page_content()` pipeline

### 5. Create Template (if needed)
- Create template in `template-parts/sections/`
- Override parent theme template if necessary

## Troubleshooting

### Common Issues

#### 1. Customizer 500 Error
Usually caused by:
- Missing sanitization functions
- Referencing non-existent panels
- PHP syntax errors

Solution: Check error logs and verify all function dependencies.

#### 2. Content Modifications Not Working
Check:
- Function is included in `modify_page_content()` pipeline
- Page context is correct (`is_checkout()`, `is_product()`, etc.)
- Regex patterns are matching correctly

#### 3. CSS Not Loading
Verify:
- File exists in correct path
- Properly enqueued in `functions.php`
- No PHP errors preventing enqueue

#### 4. Duplicate Content
Caused by multiple hooks calling the same function:
- Add duplicate prevention checks
- Verify function isn't called multiple times in pipeline

### Debugging Tips

1. **Use error_log() for debugging**:
```php
error_log('Debug: ' . print_r($variable, true));
```

2. **Test regex patterns separately**:
```php
if (preg_match($pattern, $content, $matches)) {
    error_log('Matches found: ' . print_r($matches, true));
}
```

3. **Check WordPress debug logs**:
Enable WP_DEBUG in wp-config.php and check debug.log

## Version Control Best Practices

1. **Keep parent theme untouched**: All modifications in child theme
2. **Document changes**: Update this guide when making significant modifications
3. **Test thoroughly**: Verify modifications work across different page types
4. **Backup before major changes**: Always have a rollback plan

## Function Naming Conventions

### Current Auto-Generated Functions
The child theme uses `chld_thm_cfg_` prefix for auto-generated functions:
- `chld_thm_cfg_parent_css()`
- `chld_thm_cfg_locale_css()`

**Important**: Do not modify or remove these auto-generated functions.

### Custom Function Naming
For custom functions, use consistent prefixes to avoid conflicts:

**Option 1: Use `meathouse_child_` prefix** (Recommended)
```php
function meathouse_child_custom_function() {
    // Your code here
}
```

**Option 2: Use `mc_` short prefix**
```php
function mc_custom_function() {
    // Your code here
}
```

### Naming Best Practices
1. Always prefix functions to avoid conflicts with WordPress core, plugins, and parent theme
2. Use descriptive names: `meathouse_child_modify_checkout()` not `mc_mod_co()`
3. Group related functions with similar prefixes:
   - `meathouse_child_woo_*` for WooCommerce functions
   - `meathouse_child_customizer_*` for Customizer functions
   - `meathouse_child_enqueue_*` for asset loading functions

## Future Development Guidelines

1. **Follow established patterns**: Use existing code structure as templates
2. **Maintain localization**: Use WordPress localization functions (`__()`, `_e()`, etc.) with 'meathouse' text domain
3. **Keep CSS modular**: Create separate files for new sections
4. **Document new functions**: Add clear comments explaining functionality
5. **Test cross-browser compatibility**: Verify styles work across browsers
6. **Maintain responsiveness**: Ensure new styles work on mobile devices
7. **Use proper function prefixes**: Follow naming conventions (see above)
8. **Leverage parent theme features**: Utilize MeatHouse's Elementor widgets and WooCommerce templates
9. **Test WooCommerce functionality**: Verify cart, checkout, and product pages work correctly
10. **Keep parent theme untouched**: Never modify parent theme files directly

This guide should be updated whenever significant modifications are made to the theme structure or new patterns are established.