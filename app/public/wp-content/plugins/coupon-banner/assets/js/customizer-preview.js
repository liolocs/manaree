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

    // Add persistent edit button in customizer
    // Simply try to add the button once DOM is ready
    // The customizer preview script loads after this, so just add it directly
    (function initializeEditButton() {
        // Check if we're in the customizer preview
        if (typeof wp !== 'undefined' &&
            typeof wp.customize !== 'undefined' &&
            typeof wp.customize.preview !== 'undefined') {
            // We're in customizer, add button
            addEditButton();
        } else {
            // Not in customizer yet, wait a bit
            setTimeout(initializeEditButton, 100);
        }
    })();

    function addEditButton() {
        const banner = document.getElementById('coupon-banner');

        if (banner) {
            // Don't add button twice
            if (banner.querySelector('.coupon-banner-edit-shortcut-button')) {
                return;
            }

            // Create edit button
            const editButton = document.createElement('button');
            editButton.setAttribute('aria-label', 'Cliquez pour modifier cet élément.');
            editButton.setAttribute('title', 'Cliquez pour modifier cet élément.');
            editButton.className = 'coupon-banner-edit-shortcut-button';
            editButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg>';

            // Add click handler to open customizer section
            editButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Send message to parent customizer to open the section
                if (typeof wp !== 'undefined' && typeof wp.customize !== 'undefined' && typeof wp.customize.preview !== 'undefined') {
                    wp.customize.preview.send('focus-control-for-setting', 'coupon_banner_text');
                }
            });

            // Append button to banner
            banner.appendChild(editButton);
        }
    }

})();
