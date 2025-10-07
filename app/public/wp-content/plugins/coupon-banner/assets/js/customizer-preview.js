/**
 * Customizer Live Preview Script
 * Handles real-time updates for coupon banner settings
 */

(function() {
    'use strict';

    // Base values for scaling
    const BASE_HEIGHT = 60;

    /**
     * Calculate scaled values based on height
     */
    function calculateScaledValues(height) {
        const scale = height / BASE_HEIGHT;
        return {
            textSize: (1.1 * scale).toFixed(2) + 'rem',
            codeSize: (1.2 * scale).toFixed(2) + 'rem',
            paddingV: (0.5 * scale).toFixed(2) + 'em',
            paddingH: (1.5 * scale).toFixed(2) + 'em',
            gap: (1.5 * scale).toFixed(2) + 'em',
            bannerPadding: (height * 0.15).toFixed(2) + 'px'
        };
    }

    // Background Color
    wp.customize('coupon_banner_bg_color', function(value) {
        value.bind(function(newval) {
            const banner = document.querySelector('.coupon-banner');
            const code = document.querySelector('.coupon-banner-code');

            if (banner) {
                banner.style.setProperty('background-color', newval, 'important');
            }
            if (code) {
                code.style.setProperty('color', newval, 'important');
                code.style.setProperty('border-color', newval, 'important');
            }
        });
    });

    // Banner Height
    wp.customize('coupon_banner_height', function(value) {
        value.bind(function(newval) {
            const height = parseInt(newval);
            const scaled = calculateScaledValues(height);

            const banner = document.querySelector('.coupon-banner');
            const container = document.querySelector('.coupon-banner-container');
            const text = document.querySelector('.coupon-banner-text');
            const code = document.querySelector('.coupon-banner-code');

            if (banner) {
                banner.style.minHeight = height + 'px';
                banner.style.padding = scaled.bannerPadding + ' 2em';
            }

            if (container) {
                container.style.gap = scaled.gap;
            }

            if (text) {
                text.style.fontSize = scaled.textSize;
            }

            if (code) {
                code.style.fontSize = scaled.codeSize;
                code.style.padding = scaled.paddingV + ' ' + scaled.paddingH;
            }
        });
    });

    // Banner Text
    wp.customize('coupon_banner_text', function(value) {
        value.bind(function(newval) {
            const text = document.querySelector('.coupon-banner-text');

            if (text) {
                text.innerHTML = newval;
                text.style.display = newval === '' ? 'none' : '';
            }
        });
    });

    // Coupon Code
    wp.customize('coupon_banner_code', function(value) {
        value.bind(function(newval) {
            const code = document.querySelector('.coupon-banner-code');

            if (code) {
                code.textContent = newval;
                code.style.display = newval === '' ? 'none' : '';
            }
        });
    });

    // Enable/Disable Banner
    wp.customize('coupon_banner_enabled', function(value) {
        value.bind(function(newval) {
            const banner = document.getElementById('coupon-banner');

            if (banner) {
                banner.style.display = newval == '1' ? '' : 'none';
            }
        });
    });

    // Add edit button functionality using customizer shortcut links
    wp.customize.preview.bind('ready', function() {
        const banner = document.getElementById('coupon-banner');

        if (banner && typeof wp.customize.preview !== 'undefined') {
            // Add shortcut link data attribute to enable edit button
            banner.setAttribute('data-customize-partial-placement-context', JSON.stringify({
                'sidebar_id': '',
                'widget_id': '',
                'widget_number': ''
            }));

            // Create a custom click handler for the banner
            banner.addEventListener('click', function(e) {
                if (wp.customize.preview.clicked) {
                    wp.customize.preview.clicked(e);
                }
            });

            // Register the shortcut
            wp.customize.preview.bind('ready', function() {
                wp.customize.preview.trigger('edit-shortcut-visibility', {
                    selector: '#coupon-banner',
                    context: 'coupon_banner_section'
                });
            });
        }
    });

})();
