<div class="jws_post_ql quote">
 <i class="ql_icon">&ldquo;</i>
 <div class="ql_overlay">
    <div class="ql_holder"></div>
    <div class="ql_bg">
        <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
    </div>
 </div>
 <div class="jws_ql_content">
       <h4 class="jws_ql_excerpt">
                <?php  echo get_the_title();?>
       </h4>
       <?php $quote_name = get_post_meta(get_the_ID(), 'blog_name_quote', true); if(isset($quote_name)) echo '<div class="ql_name">'.$quote_name.'</div>';  ?>
 </div>
</div>