# Theme Modifications Guide - Les Moussaillons

This document provides comprehensive guidelines for understanding and extending the modifications made to the Appetizer child theme. It serves as a reference for AI assistants and developers working on this WordPress theme.

## Table of Contents
1. [Theme Architecture](#theme-architecture)
2. [Content Modification System](#content-modification-system)
3. [CSS Organization](#css-organization)
4. [Customizer Integration](#customizer-integration)
5. [Template Modifications](#template-modifications)
6. [Common Patterns and Best Practices](#common-patterns-and-best-practices)
7. [Troubleshooting](#troubleshooting)

## Theme Architecture

### File Structure
```
appetizer-child/
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
│           ├── appetizer-hero.php  # Hero section customizer
│           └── payment-banner.php  # Payment banner customizer
└── template-parts/
    └── sections/
        └── section-hero.php        # Hero section template
```

### Key Principles
1. **Child Theme Approach**: All modifications are made in the child theme to preserve parent theme integrity
2. **Modular CSS**: Each major section has its own CSS file for better organization
3. **Content Modification via Output Buffer**: Use `ob_start()` and filters to modify page content
4. **French Localization**: All user-facing text is in French

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
function replace_burger_companion_image( $content ) {
    $new_src = get_stylesheet_directory_uri() . '/assets/images/les-moussaillons-logo.png';
    $content = preg_replace(
        '/src=["\']([^"\']*\/assets\/images\/hr-line-white\.png)["\']/i',
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
function translate_checkout_strings_to_french( $content ) {
    $translations = array(
        'Select delivery location' => 'Sélectionnez le lieu de livraison',
        'Select Time' => 'Sélectionnez l\'heure',
        // ... more translations
    );

    foreach ( $translations as $english => $french ) {
        $content = str_replace( $english, $french, $content );
    }
    return $content;
}
```

#### 4. Page-Specific Modifications
Always check page context:
```php
function modify_checkout_page( $content ) {
    if ( ! is_checkout() ) {
        return $content;
    }
    // Checkout-specific modifications
    return $content;
}
```

## CSS Organization

### File Naming Convention
- `{section-name}.css` for section-specific styles
- `{section-name}-min.css` for minified versions (if needed)

### CSS Loading in functions.php
```php
function appetizer_child_enqueue_parent_style() {
    $theme   = wp_get_theme( 'Appetizer' );
    $version = $theme->get( 'Version' );

    // Load main child theme CSS
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'appetizer-style' ), $version );

    // Load additional modular CSS files
    wp_enqueue_style( 'hero-section-style', get_stylesheet_directory_uri() . '/assets/css/hero-section-min.css', array( 'child-style' ), $version );
    wp_enqueue_style( 'checkout-style', get_stylesheet_directory_uri() . '/assets/css/checkout-min.css', array( 'child-style' ), $version );
    // ... more CSS files
}
add_action( 'wp_enqueue_scripts', 'appetizer_child_enqueue_parent_style' );
```

### CSS Best Practices
1. Use CSS custom properties for colors: `var(--bs-primary)`, `var(--main-bg-color)`
2. Page-specific targeting: `body.woocommerce-checkout`, `body.tax-product_cat`
3. Use `!important` sparingly and only when overriding parent theme styles
4. Mobile-first responsive design with media queries

## Customizer Integration

### File Structure
Customizer options are organized in separate files:
- `inc/customizer/customizer-options/appetizer-hero.php`
- `inc/customizer/customizer-options/payment-banner.php`

### Standard Customizer Pattern
```php
function section_name_customizer($wp_customize) {
    // Add section
    $wp_customize->add_section(
        'section_id',
        array(
            'title' => __('Section Title', 'appetizer'),
            'priority' => 10,
            'panel' => 'panel_id', // Optional
        )
    );

    // Add setting
    $wp_customize->add_setting(
        'setting_name',
        array(
            'default' => 'default_value',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_function_name',
        )
    );

    // Add control
    $wp_customize->add_control(
        'setting_name',
        array(
            'label' => __('Control Label', 'appetizer'),
            'section' => 'section_id',
            'type' => 'text', // text, checkbox, select, etc.
        )
    );
}
add_action('customize_register', 'section_name_customizer');
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
if (!function_exists('appetizer_sanitize_checkbox')) {
    function appetizer_sanitize_checkbox($input) {
        return ($input == true) ? '1' : '0';
    }
}

if (!function_exists('appetizer_sanitize_text')) {
    function appetizer_sanitize_text($input) {
        return sanitize_text_field($input);
    }
}

if (!function_exists('appetizer_sanitize_url')) {
    function appetizer_sanitize_url($input) {
        return esc_url_raw($input);
    }
}
```

### French Localization
All customizer labels should be in French:
```php
'label' => __('Activer la Bannière', 'appetizer'),
'description' => __('Cochez pour activer la bannière de paiement', 'appetizer'),
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

## Adding New Sections

### 1. Create CSS File
- Create `assets/css/new-section.css`
- Add section-specific styles
- Use existing CSS custom properties for consistency

### 2. Enqueue CSS
Add to `functions.php`:
```php
wp_enqueue_style( 'new-section-style', get_stylesheet_directory_uri() . '/assets/css/new-section.css', array( 'child-style' ), $version );
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

## Future Development Guidelines

1. **Follow established patterns**: Use existing code structure as templates
2. **Maintain French localization**: All user-facing text in French
3. **Keep CSS modular**: Create separate files for new sections
4. **Document new functions**: Add clear comments explaining functionality
5. **Test cross-browser compatibility**: Verify styles work across browsers
6. **Maintain responsiveness**: Ensure new styles work on mobile devices

This guide should be updated whenever significant modifications are made to the theme structure or new patterns are established.