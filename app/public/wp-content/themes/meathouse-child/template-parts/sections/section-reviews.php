<?php
/**
 * Template part for displaying Banniere Reviews section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_reviews = get_theme_mod('meathouse_hs_reviews', '1');
$meathouse_reviews_image = get_theme_mod('meathouse_reviews_image');
$meathouse_reviews_title = get_theme_mod('meathouse_reviews_title');
$meathouse_reviews_quotes = get_theme_mod('meathouse_reviews_quotes', '');

if ($meathouse_hs_reviews == '1'): ?>
<section class="banniere-reviews" id="banniere-reviews" <?php if (is_customize_preview()) { echo 'data-customize-partial-id="meathouse_reviews_section"'; } ?>>
    <div class="reviews-container">
        <?php if (!empty($meathouse_reviews_image)): ?>
            <div class="reviews-image">
                <img src="<?php echo esc_url($meathouse_reviews_image); ?>" alt="<?php echo esc_attr($meathouse_reviews_title); ?>">
            </div>
        <?php endif; ?>

        <div class="reviews-content">
            <?php if (!empty($meathouse_reviews_title)): ?>
                <h2 class="reviews-title"><?php echo wp_kses_post($meathouse_reviews_title); ?></h2>
            <?php endif; ?>

            <?php
            if (!empty($meathouse_reviews_quotes)) {
                $quotes = json_decode($meathouse_reviews_quotes, true);
                if (!empty($quotes) && is_array($quotes)): ?>
                    <div class="reviews-quotes">
                        <?php foreach ($quotes as $quote):
                            if (!empty($quote['quote_text']) || !empty($quote['quote_name'])): ?>
                                <div class="quote-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="quote-icon" viewBox="0 0 35 30" fill="none">
                                        <path d="M22.3838 27.6777C23.5264 28.9961 25.3721 29.6992 27.4814 29.6992C31.6123 29.6992 34.249 26.9746 34.249 22.7559C34.249 18.625 31.5244 15.6367 27.6572 15.6367C26.8662 15.6367 25.9873 15.8125 25.1084 16.0762C24.5811 9.48438 27.833 4.03516 32.2275 2.36523L31.7881 0.871094C24.2295 3.77148 19.4834 11.1543 19.4834 19.8555C19.4834 22.668 20.5381 25.7441 22.3838 27.6777ZM0.499023 19.8555C0.499023 24.6895 3.22363 29.6992 8.49707 29.6992C12.54 29.6992 15.1768 26.9746 15.1768 22.7559C15.1768 18.625 12.4521 15.6367 8.67285 15.6367C7.88184 15.6367 7.00293 15.8125 6.12402 16.0762C5.59668 9.48438 8.84863 4.03516 13.2432 2.36523L12.7158 0.871094C5.24512 3.77148 0.499023 11.1543 0.499023 19.8555Z"></path>
                                    </svg>
                                    <?php if (!empty($quote['quote_text'])): ?>
                                        <p class="quote-text"><?php echo wp_kses_post($quote['quote_text']); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($quote['quote_name'])): ?>
                                        <p class="quote-name"><?php echo esc_html($quote['quote_name']); ?></p>
                                    <?php endif; ?>
                                    <div class="reviews-rating" aria-label="Noté 5 sur 5" role="img">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <div class="rating-icon filled">
                                                <div class="icon-wrapper icon-marked">
                                                    <i aria-hidden="true" class="eicon-star"></i>
                                                </div>
                                                <div class="icon-wrapper icon-unmarked">
                                                    <i aria-hidden="true" class="eicon-star"></i>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                <?php endif;
            } ?>
        </div>
    </div>
</section>
<?php endif; ?>
