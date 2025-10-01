# Hero Section Setup Guide

## Overview
The hero section has been successfully created for the MeatHouse child theme. It follows the theme modification guidelines and allows full customization through the WordPress Customizer.

## Files Created

```
meathouse-child/
├── template-parts/
│   └── sections/
│       └── section-hero.php          # Hero section template
├── inc/
│   ├── extras.php                    # Content modification functions
│   └── customizer/
│       └── customizer-options/
│           └── meathouse-hero.php    # Customizer settings
├── assets/
│   └── css/
│       └── hero-section.css          # Hero section styles
└── functions.php                     # Updated with integrations
```

## How to Use

### Step 1: Add a Placeholder Element to Your Page

In Elementor (or any HTML block), add an element with the id `_section-hero` where you want the hero section to appear:

```html
<div id="_section-hero"></div>
```

Or use any HTML element:

```html
<section id="_section-hero"></section>
```

This element will be automatically replaced with the hero section when the page is rendered.

### Step 2: Customize the Hero Section

1. Go to WordPress Admin Dashboard
2. Navigate to **Appearance > Customize**
3. Open **Custom > Hero**
4. You'll see the following options:

#### Available Settings:

- **Enable Hero Section**: Toggle to show/hide the hero section
- **Background Video (MP4)**: Upload a video file for the background
- **Background Image**: Upload an image (fallback if no video)
- **Overlay Opacity**: Adjust dark overlay (0.0 to 1.0)
- **Hero Title**: Main heading text (supports HTML like `<br>`)
- **Hero Description**: Subtitle/description text
- **Button Text**: Call-to-action button text
- **Button Link**: URL for the button

### Step 3: Save and Preview

1. Click **Publish** in the Customizer
2. View your page to see the hero section

## Features

### ✅ Implemented Features:
- Background video support (MP4)
- Background image fallback
- Adjustable dark overlay
- Customizable title with HTML support
- Description text
- Call-to-action button with custom text and link
- Fully responsive design
- Fade-in animations
- Mobile-optimized (hides video on mobile)

### 🎨 Styling:
- Uses MeatHouse theme color variables (`--main`, `--secondary`, `--light`)
- Clean, custom CSS classes (no Elementor dependencies)
- Lightweight and optimized structure
- Responsive breakpoints at 1024px, 767px, and 480px
- Smooth hover effects on buttons

### 📐 Structure:
```html
<section class="hero-section">
  <div class="hero-container">
    <video class="hero-background-video">...</video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="main-content text-center">
        <h1>Title</h1>
        <div class="hero-description">Description</div>
        <a class="btn btn-primary">Button</a>
      </div>
    </div>
  </div>
</section>
```

## Example Configuration

Here's a sample configuration based on your existing Elementor section:

**Video URL**: `https://mamaree.fr/wp-content/uploads/2022/09/Mamaree-Slider.mp4`

**Title**:
```html
<a href="/shop/homard-europeen-vivant/">Huîtres fraîches<br>Dès 42.50 €</a>
```

**Button Text**: `Commander maintenant`

**Button Link**: `/shop/homard-europeen-vivant/`

## Technical Details

### Content Injection
The element with `id="_section-hero"` is replaced through two filters:
1. Output buffer modification (`ob_start()`)
2. `the_content` filter

The replacement uses regex patterns to match and replace:
- `<div id="_section-hero">...</div>`
- `<section id="_section-hero">...</section>`
- Any HTML element with this id (supports both single and double quotes)

This ensures the hero section is injected regardless of how the content is rendered.

### CSS Loading
The hero section CSS is enqueued with dependency on parent theme styles, ensuring proper loading order.

### Customizer Integration
All settings are stored in WordPress theme mods, making them:
- Easy to export/import
- Safe during theme updates
- Accessible via `get_theme_mod()`

## Troubleshooting

### Issue: Hero section not appearing
**Solution**:
- Verify you added an element with `id="_section-hero"` to your page (e.g., `<div id="_section-hero"></div>`)
- Check that "Enable Hero Section" is checked in Customizer
- Clear browser cache and WordPress cache
- Ensure the placeholder element has proper opening and closing tags

### Issue: Video not playing
**Solution**:
- Ensure video is MP4 format
- Check video file is uploaded to Media Library
- Verify video URL is correct in Customizer

### Issue: Styles not applying
**Solution**:
- Clear WordPress cache
- Hard refresh browser (Ctrl+F5 or Cmd+Shift+R)
- Verify `/assets/css/hero-section.css` exists

### Issue: Customizer 500 error
**Solution**:
- Check PHP error logs
- Verify all files exist in correct locations
- Ensure no syntax errors in PHP files

## Customization

### Changing Colors
Edit `/assets/css/hero-section.css` and modify the CSS custom properties:
```css
background: var(--main, #0d354f);     /* Primary color */
color: var(--secondary, #a3714e);     /* Secondary color */
```

### Adding More Fields
1. Add new setting in `/inc/customizer/customizer-options/meathouse-hero.php`
2. Add sanitization callback
3. Update template in `/template-parts/sections/section-hero.php`
4. Add styles in `/assets/css/hero-section.css`

### Changing Animation
Modify the `@keyframes fadeInUp` in the CSS file or add new animation classes.

## Notes

- Hero section is hidden on mobile for video to improve performance
- Background image is used as fallback on mobile
- All text supports HTML for formatting (br, span, etc.)
- Button uses MeatHouse theme button styles

## Support

For issues or questions, refer to:
- [THEME_MODIFICATIONS_GUIDE.md](THEME_MODIFICATIONS_GUIDE.md)
- WordPress Codex: https://codex.wordpress.org/
- MeatHouse Theme Documentation

---

**Version**: 1.2
**Last Updated**: 2025-10-01
**Author**: MeatHouse Child Theme

**Changelog**:
- v1.2: Removed Elementor classes, using clean custom CSS structure
- v1.1: Changed from `[section-hero]` shortcode to element ID replacement (`id="_section-hero"`)
- v1.0: Initial release
