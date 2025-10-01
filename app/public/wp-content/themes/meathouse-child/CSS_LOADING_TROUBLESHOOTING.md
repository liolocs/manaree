# CSS Loading Troubleshooting Guide

## Issue: hero-section.css Not Loading

### Fix Applied ✅

Updated `functions.php` to improve CSS loading reliability:

**Changes Made:**
1. Removed conditional `file_exists()` check that could prevent loading
2. Added automatic cache-busting using `filemtime()`
3. Simplified the enqueueing logic

**New Code:**
```php
function meathouse_child_enqueue_styles() {
    // Use file modification time for cache busting
    $hero_css_path = get_stylesheet_directory() . '/assets/css/hero-section.css';
    $hero_css_url = get_stylesheet_directory_uri() . '/assets/css/hero-section.css';

    // Get version from file modification time or use timestamp
    $version = file_exists( $hero_css_path ) ? filemtime( $hero_css_path ) : time();

    // Enqueue hero section CSS
    wp_enqueue_style(
        'meathouse-hero-section-style',
        $hero_css_url,
        array( 'chld_thm_cfg_parent' ),
        $version
    );
}
```

### Benefits:
- **Automatic Cache Busting**: Version number changes automatically when CSS file is modified
- **Always Enqueues**: Removes conditional check that could fail
- **Better Debugging**: Easier to see when CSS changes are loaded

---

## How to Verify CSS is Loading

### Method 1: Check Page Source
1. Open your page in a browser
2. Right-click → "View Page Source"
3. Search for `hero-section.css`
4. You should see: `<link rel='stylesheet' id='meathouse-hero-section-style-css' href='...hero-section.css?ver=TIMESTAMP'>`

### Method 2: Browser DevTools
1. Open DevTools (F12 or Cmd+Opt+I)
2. Go to "Network" tab
3. Refresh the page
4. Filter by "CSS"
5. Look for `hero-section.css` - should show status 200

### Method 3: Elements Inspector
1. Open DevTools
2. Go to "Elements" tab
3. Inspect the hero section
4. Check if styles from `.hero-section` are applied
5. Verify styles aren't crossed out (overridden)

---

## Common Issues & Solutions

### Issue: CSS Not Loading After Changes

**Solutions:**
1. **Clear WordPress Cache**
   - If using a caching plugin (WP Super Cache, W3 Total Cache, etc.)
   - Clear cache from plugin settings

2. **Clear Browser Cache**
   - Hard refresh: `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)
   - Or open in Incognito/Private window

3. **Check File Permissions**
   ```bash
   chmod 644 /path/to/hero-section.css
   ```

4. **Verify File Path**
   - Ensure file exists at: `meathouse-child/assets/css/hero-section.css`
   - Check for typos in filename

### Issue: CSS Loaded But Styles Not Applied

**Solutions:**
1. **Check CSS Specificity**
   - Parent theme styles might override
   - Add `!important` if necessary (use sparingly)

2. **Verify Class Names Match**
   - Ensure template uses same classes as CSS
   - Check for typos in class names

3. **Check Load Order**
   - CSS should load after parent theme CSS
   - Verify priority is set to 20 (loads later)

### Issue: Old CSS Still Showing

**Solutions:**
1. **Version Number Not Updating**
   - The fix uses `filemtime()` which auto-updates
   - If still cached, clear all caches

2. **CDN Caching**
   - If using a CDN, purge CDN cache
   - Wait a few minutes for propagation

3. **Browser Extension Interference**
   - Disable browser extensions temporarily
   - Test in Incognito mode

---

## Debugging Commands

### Check if file exists:
```bash
ls -la /Users/quicktweet/Local\ Sites/mamareefr/app/public/wp-content/themes/meathouse-child/assets/css/hero-section.css
```

### Check file permissions:
```bash
stat -f "%OLp" /path/to/hero-section.css
# Should return: 644
```

### Get file modification time (for debugging version):
```bash
stat -f "%Sm" /path/to/hero-section.css
```

### Check PHP errors (if WordPress debug enabled):
```
/wp-content/debug.log
```

---

## Testing Checklist

After making changes:
- [ ] Clear WordPress cache
- [ ] Hard refresh browser (Ctrl+F5 / Cmd+Shift+R)
- [ ] Check page source for CSS link
- [ ] Verify CSS loads in Network tab (200 status)
- [ ] Inspect hero section element for applied styles
- [ ] Test on different browser/Incognito
- [ ] Check mobile view

---

## WordPress Debug Mode

To enable debug mode for more info:

Add to `wp-config.php`:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Check errors in: `/wp-content/debug.log`

---

## Quick Fix Commands

### Force CSS reload:
```bash
# Update file modification time
touch /path/to/hero-section.css
```

### Verify WordPress can read file:
```bash
# Check ownership
ls -l /path/to/hero-section.css
# Should be owned by web server user (typically www-data or your user)
```

---

## Contact Points

If CSS still won't load after trying all solutions:
1. Check WordPress error logs
2. Verify web server has read permissions
3. Ensure no .htaccess rules blocking CSS
4. Check for plugin conflicts (disable plugins temporarily)

---

**Last Updated**: 2025-10-01
**Issue Status**: RESOLVED ✅
