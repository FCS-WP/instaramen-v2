<?php

//Register sidebar for website
if (function_exists("register_sidebar")) {
  register_sidebar();
}

function enqueue_wc_cart_fragments()
{
  wp_enqueue_script('wc-cart-fragments');
}
add_action('wp_enqueue_scripts', 'enqueue_wc_cart_fragments');

//function display name category in loop product elementor
function display_product_categories_in_loop() {
    global $product;

    $categories = get_the_terms($product->get_id(), 'product_cat');
	if (!empty($categories) && !is_wp_error($categories)) {
        $first_category = $categories[0];

        echo '<div class="name-category-in-loop"><span>' . esc_html($first_category->name) . '</span></div>';
  
    }
    
}
add_action('woocommerce_before_shop_loop_item', 'display_product_categories_in_loop', 15);

// Add sentence sign up now and get FREE $5
add_action('woocommerce_before_checkout_form', 'sentence_signup_get_free_point');
add_action('woocommerce_before_cart_table', 'sentence_signup_get_free_point');

function sentence_signup_get_free_point() {
   if(!is_user_logged_in()){
      echo '<p class="custom-notification">Sign up now and get FREE $5 Credits - Click <a href="/my-account">here</a> to register </p>';
   }else{
      return;
   }
  
}


add_filter( 'gettext', 'ahirwp_translate_woocommerce_strings', 999, 3 );
  
function ahirwp_translate_woocommerce_strings( $translated, $untranslated, $domain ) {
 
   if ( ! is_admin() && 'woocommerce' === $domain ) {
 
      switch ( $translated ) {
 
         case 'Ship to a different address?':
 
            $translated = 'Residential Details';
            break;
 
        
         // ETC
       
      }
 
   }   
  
   return $translated;
 
}

function custom_flat_rate_shipping( $rates, $package ) {
   // Get the cart total
   $cart_total = WC()->cart->subtotal;

   if($cart_total >= 50 ){
      unset( $rates['flat_rate:2']);
   }
   
   return $rates;
}
add_filter( 'woocommerce_package_rates', 'custom_flat_rate_shipping', 10, 2 );

add_action( 'woocommerce_thankyou', 'join_membership_thankyou_page', 0, 1 );

function join_membership_thankyou_page() {
    $signup_points = get_option( 'wc_points_rewards_account_signup_points' );
    if ( !is_user_logged_in() ) {
    ?>
    <div class="message-join-membership thank-you-page-message">
        <h2>Get Free $<?php echo $signup_points; ?></h2>
        <p>Click <a href="/my-account">Here</a> to join membership with us</p>
    </div>
    <?php
    }
}
 
function join_membership_after_register_form() {
    $signup_points = get_option( 'wc_points_rewards_account_signup_points' );
    if ( !is_user_logged_in() ) {
    ?>
    <div class="message-join-membership">
    <h3>Member benefits</h3>
    <ul>
        <li>Get Free $<?php echo $signup_points; ?></li>
        <li>Earn points when making purchases</li>
        <li>Use points to make purchases</li>
    </ul>
    </div>
    <?php
    }
}

add_action( 'woocommerce_register_form', 'join_membership_after_register_form' );

function custom_woocommerce_registration_redirect( $redirect ) {
    $redirect = home_url( '/my-account/points-and-rewards/' );
    return $redirect;
}
add_filter( 'woocommerce_registration_redirect', 'custom_woocommerce_registration_redirect' );

function exclude_category_from_shop( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_shop() ) {
        $tax_query = (array) $query->get( 'tax_query' );

        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => array( 18 ),
            'operator' => 'NOT IN',
        );

        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'exclude_category_from_shop' );

function button_membership_on_header(){
    if ( !is_user_logged_in() ){
        ?>
            <a href="/my-account" class="custom-icon elementor-button-link elementor-button elementor-btn-align-icon- elementor-size-sm" role="button">
                <span class="elementor-button-content-wrapper">
				    <span class="elementor-button-text">SIGN IN</span>
		        </span>
            </a>
        <?php
    }else{
        $current_user = wp_get_current_user();
        $user_name = $current_user->display_name;
        ?>
            <a class="box-name-user" href="/my-account">
                
                <i aria-hidden="true" class="far fa-user"></i>
                <p>Hi, <span><?php echo $user_name; ?></span></p>
            </a>
        <?php
    }
}
add_shortcode('member_button', 'button_membership_on_header');

//Add gallery video for product
//Add gallery video for product
function add_product_video_url_meta_box()
{
  add_meta_box(
    'product_video_url',
    'Video Product',
    'display_product_video_url_meta_box',
    'product',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'add_product_video_url_meta_box');

function display_product_video_url_meta_box($post)
{
  $video_url = get_post_meta($post->ID, '_product_video_url', true);
?>
  <label for="product_video_url">Link Video Product:</label>
  <input type="text" id="product_video_url" name="product_video_url" value="<?php echo esc_url($video_url); ?>" style="width: 100%;" />
  <?php
}

function save_product_video_url_meta_box($post_id)
{
  if (isset($_POST['product_video_url'])) {
    update_post_meta($post_id, '_product_video_url', esc_url($_POST['product_video_url']));
  }
}
add_action('save_post_product', 'save_product_video_url_meta_box');


function display_product_video_on_single_product()
{
  global $post;

  $video_url = get_post_meta($post->ID, '_product_video_url', true);
  if (has_post_thumbnail($post->ID)) :
  ?>
    <?php if (!empty($video_url)) : ?>

      <div data-thumb="<?php echo THEME_URL . '-child/assets/icons/thumb-video.png'; ?>" data-thumb-alt="" class="woocommerce-product-gallery__image product-video" style="width: 496px; margin-right: 0px; float: left; display: block;">
        <a data-elementor-open-lightbox="no">
          <video width="100%" height="100%" controls="false" loop="true" autoplay="true">
            <source src="<?php echo $video_url; ?>" type="video/mp4">
          </video>
        </a>
        <?php ?>
      </div>
    <?php endif; ?>

  <?php endif; ?>
  <?php

}
add_action('woocommerce_product_thumbnails', 'display_product_video_on_single_product', 0, 0);


add_filter('woocommerce_single_product_image_thumbnail_html', function ($html, $post_thumbnail_id) {
  global $post;

  $video_url = get_post_meta($post->ID, '_product_video_url', true);
  if (! $post_thumbnail_id) : ?>
    <div class="woocommerce-product-gallery__image--placeholder">
      <video width="100%" height="100%" controls="false" loop="true" autoplay="true">
        <source src="<?php echo $video_url; ?>" type="video/mp4">
      </video>
    </div>
    <?php return; ?>
  <?php endif; ?>
<?php
  return $html;
}, 10, 2);

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

function display_thumbnail_product_loop(){
    global $product;
    $video_url = get_post_meta($product->get_id(), '_product_video_url', true);

    if (has_post_thumbnail()) {
        the_post_thumbnail();
    } elseif (!empty($video_url)) {
        echo '<video width="100%" height="auto" muted loop="true" autoplay="true">';
        echo '<source src="' . $video_url . '" type="video/mp4">';
        echo '</video>';

    } else {
        echo '<img src="' . wc_placeholder_img_src() . '">';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'display_thumbnail_product_loop', 10 );