<?php
/*
Plugin Name: Logicrays WP Mailchimp Signup form with popup
Plugin URI: http://www.logicrays.com/
Description: A full-featured WordPress Mailchimp Subscriber form with modal popup which fulfils all subscribers, emails and get more subscribres easily.
Author: LogicRays WordPress Team
Version: 1.1
*/
define("lrm_wp_mailchimp_text_domain", "lrm_wp__mailchimp_text_domain");
define('lrm_wp_mailchimp_path', plugins_url('', __FILE__));
add_action('admin_menu', 'lrm_wp_mailchimp_menu');

function lrm_wp_mailchimp_menu()
{
    add_menu_page('Lr Mailchimp', 'LR Mailchimp settings', 'manage_options', 'lri-wp-mailchimp', 'lrm_wp_mailchimp_settings');
    add_submenu_page('lrm_wp_mailchimp_text_domain', _('Free plugins') , _('Free plugins') , 'manage_options', 'check_our_free_plugins_mailchimp', 'check_our_free_plugins_mailchimp');
}
include_once 'lrm-wp-mailchimp-shortcode.php';
function lrm_wp_mailchimp_settings()
{ ?>
<div class="wrap">
  <h2>Logicrays WP Mailchimp Signup form with popup settings</h2>
  <form action="options.php" method="post">
    <?php settings_fields("section"); ?>
    <style>
  .lrm_wp_shortcode {display: block;float: left; clear: both;margin: 15px 0 0 0;padding: 15px 20px;   min-width: 808px;border: 1px solid #ccc;background: #eee;background: rgba(255,255,255,0.5);-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;}
.box-item {margin-bottom: 30px; padding: 25px; border: 1px solid #eee;background: #fff;position: relative;
}
</style>
    <div class="lrm_wp_shortcode">
      <h3>Display your Mailchimp Subscriber form with Modal popup</h3>
      <p>Copy and paste this shortcode directly into the page, post or widget where you'd like to display the popup:[LRM_WP_MAILCHIMP]</p>
      <p>Check out our other free plugins: <a href="https://profiles.wordpress.org/logicrays#content-plugins" target="_blank"> Free plugins</a></p>
      <p>Like the plugin? Please rate us!</p>
    </div>
    <?php
    do_settings_sections("mailchimp-options");
    submit_button();
?>
  </form>
</div>
<?php
}
function lrm_wp_mailchimp_fields()
{
    add_settings_section("section", "", null, "mailchimp-options");
    add_settings_field("lrm_wp_show", "Show form ?", "lrm_wp_show_callback", "mailchimp-options", "section");
    add_settings_field("lrm_wp_top_title", "Top title text", "lrm_wp_top_title_callback", "mailchimp-options", "section");
    add_settings_field("lrm_wp_sub_title", "Subtitle text", "lrm_wp_sub_title_callback", "mailchimp-options", "section");
    add_settings_field("lrm_wp_mailchimp_key", "Mailchimp API key", "lrm_wp_mailchimp_key_callback", "mailchimp-options", "section");
    add_settings_field("lrm_wp_mailchimp_list", "Subscriber list id", "lrm_wp_mailchimp_list_callback", "mailchimp-options", "section");
    add_settings_field("lrm_wp_mailchimp_subscribe_button", "Subscriber button", "lrm_wp_mailchimp_subscribe_button_callback", "mailchimp-options", "section");

    register_setting("section", "lrm_wp_show");
    register_setting("section", "lrm_wp_top_title");
    register_setting("section", "lrm_wp_sub_title");
    register_setting("section", "lrm_wp_mailchimp_short");
    register_setting("section", "lrm_wp_mailchimp_key");
    register_setting("section", "lrm_wp_mailchimp_list");
    register_setting("section", "lrm_wp_mailchimp_subscribe_button");
}
add_action("admin_init", "lrm_wp_mailchimp_fields");
add_action('wp_head', 'lrm_wp_style');
function lrm_wp_style()
{
    wp_enqueue_style('mailchimp-main-css', lrm_wp_mailchimp_path . '/css/mailchimp-style.css');
    wp_enqueue_script('mailchimp-custom-js', lrm_wp_mailchimp_path . '/js/mailchimp-custom.js');
}
function lrm_wp_top_title_callback()
{ ?>
    <input type="text" name="lrm_wp_top_title" size='40' id="lrm_wp_top_title" value="<?php echo esc_attr(get_option('lrm_wp_top_title')); ?>" placeholder="Enter top title" />
    <p class="description"><?php _e('Enter top title'); ?></p>
<?php
}
function lrm_wp_sub_title_callback()
{ ?>
  <input type="text" name="lrm_wp_sub_title" size='40' id="lrm_wp_sub_title" value="<?php echo esc_attr(get_option('lrm_wp_sub_title')); ?>" placeholder="Enter Sub title" />
    <p class="description"><?php _e('Enter sub title ?.'); ?></p>
  <?php
}
function lrm_wp_show_callback()
{
    $options = get_option('lrm_wp_show'); ?>
    <select id="lrm_wp_show" name='lrm_wp_show[lrm_wp_show]'>
      <option value='yes'<?php selected($options['lrm_wp_show'], 'yes'); ?>>
        <?php _e('Yes', 'lrm_wp_mailchimp_text_domain'); ?>
      </option>
      <option value='no'<?php selected($options['lrm_wp_show'], 'no'); ?>>
      <?php _e('No', 'lrm_wp_mailchimp_text_domain'); ?>
      </option>
    </select>
    <p class="description"><?php _e('Enable or disable form poup.'); ?></p>
    <?php
}
function check_our_free_plugins_mailchimp()
{
    include_once 'lr-free-plugins.php';
}
function lrm_wp_mailchimp_key_callback()
{ ?>
    <input type="text" name="lrm_wp_mailchimp_key" size='40' id="lrm_wp_mailchimp_key" value="<?php echo esc_attr(get_option('lrm_wp_mailchimp_key')); ?>" placeholder="Enter Mailchimp API key" />
    <p class="description"><?php _e('Mailchimp API key.'); ?>&nbsp;<a href="https://kb.mailchimp.com/integrations/api-integrations/about-api-keys" target="_blank"> How can i get API key ?</a></p>
<?php
}
function lrm_wp_mailchimp_list_callback()
{ ?>
    <input type="text" name="lrm_wp_mailchimp_list" size='40' id="lrm_wp_mailchimp_list" value="<?php echo esc_attr(get_option('lrm_wp_mailchimp_list')); ?>" placeholder="Enter Mailchimp Subscriber list ID" />
    <p class="description"><?php _e('Mailchimp Subscriber list ID.'); ?> &nbsp;<a href="https://kb.mailchimp.com/lists/manage-contacts/find-your-list-id" target="_blank">How can i get list id ?</a></p>
<?php
}

function lrm_wp_mailchimp_subscribe_button_callback()
{ ?>
    <input type="text" name="lrm_wp_mailchimp_btn_title" size='40' id="lrm_wp_mailchimp_list" value="<?php echo esc_attr(get_option('lrm_wp_mailchimp_btn_title')); ?>" placeholder="Enter button title" />
    <p class="description"><?php _e('Mailchimp Subscribe button title.'); ?></p>
<?php
}