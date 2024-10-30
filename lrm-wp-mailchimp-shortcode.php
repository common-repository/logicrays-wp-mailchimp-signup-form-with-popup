<?php
session_start();
add_shortcode('LRM_WP_MAILCHIMP', 'lrM_shortcode');
function lrM_shortcode()
{
    ob_start();
    $lrm_wp_top_title = get_option('lrm_wp_top_title');
    $lrm_wp_sub_title = get_option('lrm_wp_sub_title');
    $lrm_wp_show = get_option('lrm_wp_show');
    $lrm_wp_mailchimp_btn_title = get_option('lrm_wp_mailchimp_btn_title');
    $lrm_wp_show = $lrm_wp_show['lrm_wp_show'];
    if ($lrm_wp_show == 'yes')
    {
        if (!empty($_POST['submit']))
        {
            $email = $_POST['email'];
            $cookie_name = "dont_bother";
            $cookie_value = "setemail";
            $dismiss = $_POST['dismiss'];
            $message = "Thank you for subscribing";
            if (($email != '') && $dismiss == 'dis')
            {
                setcookie($cookie_name, $cookie_value, time() + 31556926);
            }

            $api_key = get_option('lrm_wp_mailchimp_key');
            $list_id = get_option('lrm_wp_mailchimp_list');

            $status = 'subscribed'; // subscribed, cleaned, pending
            $dc = substr($api_key, strpos($api_key, '-') + 1); // us5, us8 etc
            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
                )
            );
            $response = wp_remote_get('https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/', $args);
            $body = json_decode(wp_remote_retrieve_body($response));
            $emails = array();

            if (wp_remote_retrieve_response_code($response) == 200)
            {
                foreach ($body->members as $member)
                {
                    if ($member->status != 'subscribed') continue;
                    $emails[] = $member->email_address;
                }
            }
            else
            {
                echo '<b>' . wp_remote_retrieve_response_code($response) . wp_remote_retrieve_response_message($response) . ':</b> ' . $body->detail;
            }
            if (in_array($email, $emails))
            {
                $already = "This user already subscribed";
            }
            else
            {

                $args = array(
                    'method' => 'PUT',
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
                    ) ,
                    'body' => json_encode(array(
                        'email_address' => $email,
                        'status' => $status
                    ))
                );
                $response = wp_remote_post('https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($email)) , $args);
                $body = json_decode($response['body']);
                if ($response['response']['code'] == 200 && $body->status == $status)
                {
                    $already = 'You have been succesfully subscribed';
                }
                else
                {
                    echo '<b>' . $response['response']['code'] . $body->title . ':</b> ' . $body->detail;
                }
            }
        }
?>
        <?php
        if (!isset($_COOKIE['dont_bother']))
        { ?>
        <div id="openModal" class="mb_elegantModal" style="display: block;">
      <div class="animated swing"> <a title="Close" class="mb_elegantModalclose"></a>
      <p class="success"><?php echo $already; ?></p>
      <?php if ($lrm_wp_top_title): ?>
        <h2 class="top-title"><?php echo esc_attr($lrm_wp_top_title); ?></h2>
    <?php
            endif; ?>
    <?php if ($lrm_wp_sub_title): ?>
         <p><?php echo esc_attr($lrm_wp_sub_title); ?></p>
           <?php
            endif; ?>
    <br>
    <br>
    <form method="post" action="" name="frm" enctype="multipart/form-data">
      <input type="hidden" value="pageone/kZNz" name="uri">
      <input type="hidden" value="en_US" name="loc">
      <input id="esp_email" type="email" name="email" value="" required="" placeholder="Enter Your Email">
      <br>
      <div class="dont-show">
        <input class="nothanks" type="checkbox" name="dismiss" value="dis" />Don't show me again!</div>
        <br>
        <?php if($lrm_wp_mailchimp_btn_title):?>
      <input class="mb_elegantpopupbutton" type="submit" name="submit" value="<?php echo esc_html($lrm_wp_mailchimp_btn_title); ?>" />
      <?php else:?>
    <input class="mb_elegantpopupbutton" type="submit" name="submit" value="SUBSCRIBE NOW" />
      <?php endif;?>
    </form>
  </div>
</div>
<?php
        }
    } ?>
<?php return ob_get_clean();
}