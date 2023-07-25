<?php
/*
Plugin Name:  LW Frontend Dashboard
Plugin URI:   http://
Description:  LW Frontend Dashboard
Version:      1.0
Author:       LW Frontend
Text Domain:  lw
Domain Path:  /languages
*/

class Lw_Frontend_Dashboard{
  public function __construct(){
    add_shortcode('post-stt', array($this,'show_pmeta'));
    add_shortcode('custom-pmeta', array($this,'csmeta'));
    add_shortcode('drop-menu', array($this,'show_drop'));
    add_shortcode('checkmark-custom', array($this,'custom_cmark'));
    add_shortcode('check-sect', array($this,'check_sect'));
    add_shortcode('icon-box-feat2', array($this,'ico_box_feat2'));
    add_shortcode('boxes-sect', array($this,'box_section'));
    add_shortcode('user-logo', array($this,'show_usr_logo'));
    add_shortcode('user-avtr', array($this,'show_usr_avtr'));
    add_shortcode('pds-count', array($this,'pd_ct'));
    add_action( 'wp_enqueue_scripts', array($this,'lw_enqueue_scripts' ));
    add_action( 'admin_enqueue_scripts', array($this,'backend_enqueue' ));
    add_shortcode('all-compaigns', array($this, 'all_campaigns'));
    add_shortcode('usr-courses', array($this, 'usr_courses'));
    add_shortcode('edit-course-ct', array($this, 'ed_course_fun'));
    add_action("wp_ajax_lw_get_post_data", array($this, "lw_get_course_data"));
    add_shortcode('lw-filter-area', array($this, 'lw_filter_front'));
    add_action( 'wp_ajax_filter_pds', array($this, 'filter_pds') );
    add_action( 'wp_ajax_nopriv_filter_pds', array($this, 'filter_pds') );
    add_action( 'wp_ajax_email_verify', array($this, 'email_verify') );
    add_action( 'wp_ajax_nopriv_email_verify', array($this, 'email_verify') );
    add_action( 'wp_ajax_nopriv_send_tst_email', array($this, 'send_tst_email') );
    add_action( 'wp_ajax_send_tst_email', array($this, 'send_tst_email') );

    add_action( 'wp_ajax_nopriv_get_sub_terms', array($this, 'get_sub_terms') );
    add_action( 'wp_ajax_get_sub_terms', array($this, 'get_sub_terms') );

    add_shortcode( 'submit-draft-btn', array($this,'submit_draft_btn'));
    add_shortcode('eml_ct', array($this, 'email_count'));
    add_shortcode('sorting', array($this, 'sort_code'));
    add_filter( 'body_class', array($this, 'add_role_to_body_class') );
    add_action( 'elementor/query/pd_query', array($this, 'custom_pd_query') );
    add_action( "admin_enqueue_scripts", array($this,"ayecode_enqueue") );
    add_action( 'admin_print_footer_scripts-profile.php', array($this,'ayecode_admin_media_scripts') );
    add_action( 'admin_print_footer_scripts-user-edit.php', array($this,'ayecode_admin_media_scripts') );
    add_action( 'show_user_profile', array($this,'custom_user_profile_fields'), 10, 1 );
    add_action( 'edit_user_profile', array($this,'custom_user_profile_fields'), 10, 1 );
    add_action( 'personal_options_update', array($this,'ayecode_save_local_avatar_fields') );
    add_action( 'edit_user_profile_update', array($this,'ayecode_save_local_avatar_fields') );
    add_filter( 'get_avatar_url', array($this,'ayecode_get_avatar_url'), 999, 3 );
    add_action( 'wp_footer' , array($this, 'email_footer_data'));
    add_shortcode( 'email-field-custom' , array($this, 'email_field_custom') );
    add_shortcode('no-ps-found', array($this,'no_posts_found_msg'));
    add_shortcode('logbtn', array($this,'this_logout_btn'));
    add_action( 'jet-form-builder/custom-action/save_fm_data', array($this, 'cstm_api_req'), 10, 2);
    add_action( 'jet-form-builder/custom-action/user-data-save', array($this, 'user_dat_save'), 10, 2);
    add_shortcode('community-edit', array($this, 'edit_this_comm'));
  }

  public function edit_this_comm()
  {
    $pid = @$_GET['pid'];
    $gp = get_post($pid);
    $commid = get_post_meta($pid, 'community-id', true);
    $title_cm = get_post_meta($pid, 'title_cm', true);
    $comm_img = get_post_meta($pid, 'community-image', true);
    $cm_url = get_post_meta($pid, 'cm_url', true);
    $course_linked = get_post_meta($pid, 'linked-to-course', true);
    $stud_count = get_post_meta($pid, 'enrolled-student-count', true);
    $spac_dat = get_post_meta($pid, 'spaces_317', true);
    // echo "<pre>".print_r($spac_dat, true);
    // die ;

    if(is_user_logged_in())
    {
      $cuid = get_current_user_id();
    }

    if(!empty($_POST['submit_community']))
    {
        //echo "<pre>".print_r($_FILES,true); die;
        $ui = array();

        $sbmt_cm = $_POST['submit_community'];

        $communitytitle = $_POST['comm_title'];
        $communityid = $_POST['comm_id'];
        $vfile = $this->mupfile('comm_img', $sbmt_cm);
        // $communityimage = $_POST['comm_img'];
        $communityurl = $_POST['comm_url'];
        $communitycourse = $_POST['comm_course'];
        $communityenrolled = $_POST['comm_enrolled'];
        $spacetitle = $_POST['space_title'];
        $spaceurl = $_POST['space_url'];

        $dm = array();
        foreach($spacetitle as $kp=>$sy)
        {
          $dm['item-'.$kp] = array(
            'space-title' => $sy,
            'space-url' => $spaceurl[$kp],
          );
        }



        $my_post = array(
          'ID'           => $sbmt_cm,
          'post_title'   => $communitytitle,
        // 'post_content' => $pscn,
      );
        wp_update_post( $my_post );
        update_post_meta($sbmt_cm, 'community-id',$communityid);
        update_post_meta($sbmt_cm, 'community-image',$vfile);
        update_post_meta($sbmt_cm, 'cm_url',$communityurl);
        update_post_meta($sbmt_cm, 'linked-to-course',$communitycourse);
        update_post_meta($sbmt_cm, 'enrolled-student-count',$communityenrolled);
        update_post_meta($sbmt_cm, 'spaces_317',$dm);

        $up = site_url().'/community-edit/?pid='.$sbmt_cm.'&updated=1';
        wp_redirect($up);
        exit();


        $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

    }


      ?>



       <form action="" method="POST" enctype="multipart/form-data" class="cedit">

        <div class="roww">
          <div class="columnc">
            <h2>Community Details</h2>
            <p>Edit community details, here.</p>
          </div>
          <div class="columnc borde">
            <div class="ed_single">
              <label>Community Title</label>
              <input class="txt-field" type="text" name="comm_title" value="<?php echo $gp->post_title; ?>">
            </div>
            <div class="ed_single">
              <label>Community ID</label>
              <input class="txt-field" type="text" name="comm_id" value="<?php echo $title_cm; ?>">
            </div>
            <div class="ed_single bdrr">
              <label>Community Image</label>
              <div id="prewimg"> <img src="<?php echo $comm_img; ?>" alt=""> </div>
              <div><input id="ImageMedias" multiple="multiple" type="file" accept=".jfif,.jpg,.jpeg,.png,.gif" class="custom-file-input txt-field" type="file" name="comm_img" value="<?php echo $comm_img; ?>"></div>


            </div>
            <div class="ed_single">
              <label>Community URL</label>
              <input class="txt-field" type="text" name="comm_url" value="<?php echo $cm_url; ?>">
            </div>
            <div class="ed_single">
              <label>Linked Course</label>
              <input class="txt-field" type="text" name="comm_course" value="<?php echo $course_linked; ?>">
            </div>
            <div class="ed_single">
              <label>Enrolled Student Count</label>
              <input class="txt-field" type="text" name="comm_enrolled" value="<?php echo $stud_count; ?>">
            </div>
          </div>
        </div>
        <br>
        <div class="roww">
          <div class="columnc">
            <h2>Space Details</h2>
            <p>Edit space details of your community.</p>
          </div>
          <div class="columnc borde">
            <h4>Space Details</h4>
            <ul class="prt_sections">
              <?php if(!empty($spac_dat))
              {
                foreach ($spac_dat as $key => $value) {
                  $no = $key+1;
                  ?>
                  <li class="single_csect">
                <h4 class="acc_title"><i class="fas fa-arrows-alt"></i>Space <?php echo $no; ?><a data-title="Space" data-index="1" class="act_link2" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i></h4>
                <div class="acc-content">
                  <div class="ed_single">
                    <label>Space Title</label>
                    <input class="txt-field" type="text" name="space_title[]" value="<?php echo $value['space-title']; ?>">
                  </div>
                  <div class="ed_single">
                    <label>Space URL</label>
                    <input class="txt-field" type="text" name="space_url[]" value="<?php echo $value['space-url']; ?>">
                  </div>

                </div>
            </li>
                  <?php
                }
                ?>

                <?php
              } else { ?>
            <li class="single_csect">
                <h4 class="acc_title"><i class="fas fa-arrows-alt"></i>Space 1 <a data-title="Space" data-index="1" class="act_link2" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i></h4>
                <div class="acc-content">
                  <div class="ed_single">
                    <label>Space Title</label>
                    <input class="txt-field" type="text" name="space_title[]" value="">
                  </div>
                  <div class="ed_single">
                    <label>Space URL</label>
                    <input class="txt-field" type="text" name="space_url[]" value="">
                  </div>

                </div>
            </li>
            <?php } ?>
            </ul>
          </div>
        </div>

        <input type="hidden" name="submit_community" value="<?php echo $pid; ?>"/>
        <input type="submit" value="Submit">

      </form>
      <?php
  }

  public function get_sub_terms()
  {
      $prt = $_POST['term_id'];
      $categories = get_terms( array(
          'taxonomy' => 'course-category',
          'hide_empty' => false,
          'parent' => $prt
          //'child_of' => 17 // to target not only direct children
      ) );
      $htm = '';
      if(!empty($categories))
      {
          $htm .= '<option value="">Select Subcategory</option>';
          foreach($categories as $sbc)
          {
              $htm .= '<option value="'.$sbc->term_id.'">'.$sbc->name.'</option>';
          }
          $data = array(
            'status' => true,
            'html' => $htm
          );
      }
      else
      {
            $data = array(
              'status' => false,
              'msg' => "No sub categories found."
            );
      }
      wp_send_json($data);
  }

  public function send_tst_email()
  {
      $cik = $_POST['cid'];
      $tml = $_POST['email_data'];
      $cm = get_post($cik);
      $to = $tml;
      $subject = 'Test Email';
      $body = $cm->post_content;
      //$headers = array('Content-Type: text/html; charset=UTF-8');

     // $du = wp_mail( $to, $subject, $body, $headers );
      //var_dump($du);
      $head = '<!--

      Template Name: Mailster
      Template URI: https://mailster.co
      Description: A plain template for Mailster
      Author: EverPress
      Author URI: https://everpress.co
      Version: 1.0
      Width: 640

    -->
    <!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1 user-scalable=yes">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
    <meta name="x-apple-disable-message-reformatting">
    <!--[if gte mso 9]><xml>
      <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml><![endif]-->

    <style type="text/css" data-embed>

        @import url(\'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap\');
      @media only screen {
        h1,h2,h3,h4,h5,h6{font-family:Roboto, Helvetica, Arial, sans-serif !important;}
        table{font-family:Roboto, Helvetica, Arial, sans-serif !important;}
      }

    </style>
    <style type="text/css">

      #MessageViewBody, #MessageWebViewDiv{width:100% !important;}
      body{width:100%!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0!important;padding:0!important;}
      .bodytbl{margin:0;padding:0;width:100% !important;-webkit-text-size-adjust:none;}
      img{outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;vertical-align:middle;max-width:100%;}
      a img{border:none;}
      p{margin:1em 0;}

      table{border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;}
      table td{border-collapse:collapse;}
      .o-fix table,.o-fix td{mso-table-lspace:0pt;mso-table-rspace:0pt;}

      body,.bodytbl{background-color:#FEFEFE/*Background Color*/;}

      table,td,p{color:#58595B/*Text Color*/;}

      table{font-family:Helvetica, Arial, sans-serif;font-size:16px;line-height:26px;}
      td,p{line-height:26px;}
      ul{margin-top:26px;margin-bottom:26px;}
      li{line-height:26px;}
      a{text-decoration:none;}

      h1,h2,h3,h4,h5,h6{color:#58595B;font-family:Helvetica, Arial, sans-serif;font-weight:700;line-height:120%;}
      h1{font-size:38px;margin-bottom:26px;margin-top:4px;letter-spacing:-1.2px}
      h2{font-size:28px;margin-bottom:26px;margin-top:4px;letter-spacing:-1.2px}
      h3{font-size:26px;margin-bottom:26px;margin-top:4px;letter-spacing:-1.2px}
      h4{font-size:20px;margin-bottom:12px;margin-top:2px;}
      h5{font-size:18px;margin-bottom:12px;margin-top:2px;}
      h6{font-size:16px;}

      .wrap.footer{}
      .padd{width:26px;}

      a,a:link,a:visited,a:hover{color:#2BB3E7/*Contrast*/;}

      .small{font-size:12px;line-height:24px;}
      .btn{margin-top:24px;display:block;}
      .line{border-bottom:1px dotted #58595B}
      .toc{line-height:18px;}

      table.textbutton td{
        border-radius: 4px;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          line-height: 24px;
          text-align: center;
          text-decoration: none !important;
          transition: opacity 0.1s ease-in;
          color: #F8F8F8/*Button Text*/;
          background-color: #2BB3E7;
        padding: 10px 22px;
        border-radius: 4px;
        mso-line-height-rule: exactly;
        letter-spacing: normal;
        }
      table.textbutton td:hover{opacity: 0.8;}
      table.textbutton a{color:#F8F8F8;font-size:16px;font-weight:700;line-height:26px;width:100%;display:inline-block;text-transform:uppercase;}

      .cta table.textbutton td{border:0;min-height:36px;}
      .cta table.textbutton a{font-size:22px;line-height:42px;}

      @media only screen and (max-width: 639px) {
        body{-webkit-text-size-adjust:120% !important;-ms-text-size-adjust:120% !important;}
        .wrap{width:86% !important;}
        .wrap table{width:100% !important;}
        .wrap .padd{width:12px !important;}
        .wrap img{max-width:100% !important;height:auto !important;}
        .wrap.header img,.wrap.footer img{min-width:initial !important;width:auto !important;max-width:50% !important}
        .wrap .m-l{text-align:left !important;}
        .wrap .m-0{width:0;display:none;}
        .wrap .m-b{margin-bottom:26px !important;}
        .wrap.header .m-b,.wrap.footer .m-b{margin-bottom:0 !important;}
        .wrap .m-b,.m-b img{display:block;min-width:100% !important;width:100% !important;}
        table{font-size:15px !important;}
        table.textbutton td{height:auto !important;min-height:44px !important;}
        table.textbutton a{line-height:26px !important;padding:10px 0 !important;font-size:18px !important;}
      }


    </style>
    </head>';
      $names = null;

				$mail = mailster( 'mail' );
        $bouncemail   = mailster_option( 'bounce' );
        $embed_images = mailster_option( 'embed_images' );
				$mail->to           = $tml;
				$mail->subject      = $subject;
				$mail->from         = "hello@alephbeta.co";
				$mail->from_name    = "Instructor: Testing Test";
				$mail->reply_to     = "no-reply@alephbeta.co";
				$mail->bouncemail   = $bouncemail;
				$mail->embed_images = $embed_images;
				$mail->hash         = str_repeat( '0', 32 );
        $mail->content      = "tests";

				$res = $mail->send();

				$mail->close();
        //echo "<pre>".print_r($mail->last_error->getMessage(), true);
       /*
        if($success)
        { */
          $data = array(
              'status' => true,
              'msg' => 'A test email has been sent successfully.'
          );
        /*}
        else
        {
          $data = array(
              'status' => false,
              'msg' => 'There was an error sending test email.'
          );
        } */
      wp_send_json($data);
  }

  public function user_dat_save($request, $action_handler)
  {
      $cslogo = $request['usr_logo'];
      $avatar_copy = $request['avatar_copy'];
      $cdp = get_current_user_id();

      if(empty($cslogo))
      {
          $avp = get_user_meta($cdp, 'avatar_now', true);
          if(!empty($avp))
          {
             update_user_meta($cdp, 'ayecode-custom-avatar', $avp);
          }
      }
      else
      {
          update_user_meta($cdp, 'avatar_now', $cslogo);
      }
      if(empty($avatar_copy))
      {
          $avps = get_user_meta($cdp, 'logo_now', true);
          if(!empty($avps))
          {
             update_user_meta($cdp, 'user_logo', $avps);
          }
      }
      else
      {
            update_user_meta($cdp, 'logo_now', $avatar_copy);
      }
  }

  public function cstm_api_req($request, $action_handler)
  {
    // $post_id = ! empty( $request['inserted_post_id'] ) ? $request['inserted_post_id'] : false;

    // if ( ! $post_id ) {
    //   return;
    // }
    // echo "<pre>".print_r($request, true);
    // die;
    if(!empty($request['post_id']))
    {
        $pst = $request['hidden_field_name_copy'];
        $pcont = wp_kses_post($request['email_content']);
        wp_update_post(array(
          'ID'    =>  $request['post_id'],
          'post_status'   =>  $pst,
          'post_content' => $pcont
          ));
        update_post_meta($request['post_id'], '_mailster_active', $request['is_actv']);
        update_post_meta($request['post_id'], '_mailster_timestamp', $request['schedule_timestamp']);
    }
  }

  public function submit_draft_btn()
  {
      $html = "<a class='dr_btn button' href='#'>Save As Draft</a>";
      $html .= "<a class='dr_btn_sd button' href='#'>Schedule</a>";
      return $html;
  }

  public function this_logout_btn()
  {
     return '<a class="lgbtn" href="'.wp_logout_url(site_url()).'"><img src="'.site_url().'/wp-content/uploads/2023/07/sign-out-icons-1.png"/></a>';
  }

  public function no_posts_found_msg()
  {
    $args = array(
      'post_type' => 'products',
      'posts_per_page' => -1,
      'author' => get_current_user_id(),
    );
    $the_query = new WP_Query( $args );
    if(!$the_query->have_posts())
    {
      return "<p class='text-center txc'>No products to display, create your first product by clicking <a href='https://learn.alephbeta.co/author/dashboard'>here</a>.</p>";
    }

  }

  public function email_verify()
  {
      $email_data = $_POST['email_data'];
      //$to = get_option( 'admin_email' );
      $to = $email_data;
      $subject = 'Email Confirmation';
      //$lk =
      $body = 'Please click on link below to confirm email address

    ';
      $headers = array( 'Content-Type: text/html; charset=UTF-8' );
      $sd = wp_mail( $to, $subject, $message, $headers, array( '' ) );
      if($sd)
      {
          $data = array(
            'status' => true,
            'msg' =>'A confirmation email has been sent successfully.'
          );
      }
      else
      {
        $data = array(
          'status' => false,
          'msg' =>'There was an error sending email.'
        );
      }
      wp_send_json($data);
  }

  public function email_field_custom()
  {
      $cu = wp_get_current_user();
      ob_start();
      ?>
      <div class="jet-form-builder-row field-type-text-field cstm_fd">
        <div class="jet-form-builder__label">
          <div class="jet-form-builder__label-text">Email</div>
        </div>
        <div class="jet-form-builder__field-wrap">
          <input value="<?php echo $cu->user_email; ?>" placeholder="Email" type="text" data-field-name="email" class="jet-form-builder__field text-field eml_fld" data-jfb-sync="">
        </div>
      </div>
      <?php
      $html = ob_get_contents();
      ob_end_clean();
      return $html;
  }

  public function email_footer_data()
  {
    if(is_page(5955))
    {
        ?>
        <div class="loading_footer">
          <div class="footer-inr">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
          <rect x="19" y="19" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0s" calcMode="discrete"></animate>
          </rect><rect x="40" y="19" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.125s" calcMode="discrete"></animate>
          </rect><rect x="61" y="19" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.25s" calcMode="discrete"></animate>
          </rect><rect x="19" y="40" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.875s" calcMode="discrete"></animate>
          </rect><rect x="61" y="40" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.375s" calcMode="discrete"></animate>
          </rect><rect x="19" y="61" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.75s" calcMode="discrete"></animate>
          </rect><rect x="40" y="61" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.625s" calcMode="discrete"></animate>
          </rect><rect x="61" y="61" width="20" height="20" fill="#93dbe9">
            <animate attributeName="fill" values="#689cc5;#93dbe9;#93dbe9" keyTimes="0;0.125;1" dur="1s" repeatCount="indefinite" begin="0.5s" calcMode="discrete"></animate>
          </rect>
          </svg>
          </div>
        </div>
        <?php
    }
    if(is_page(5093))
    {
      $wp_usr = wp_get_current_user();
      $args = array(
        'post_type' => 'newsletter',
        'posts_per_page' => -1,
        'post_status' => array( 'paused', 'draft', 'publish' ),
        'date_query' => array(
          array(
              'year'  => date('Y'),
              'month' => date('m')
          ),
        ),
        'author' => get_current_user_id()
      );
      $the_query = new WP_Query( $args );
      $fd_ps = $the_query->found_posts;
      ?>
      <div class="test_email_popup">
          <div class="test_email_inner">
          <form action="#" method="POST">
              <a class="clz_pop_ne" href="#"><i class="fa fa-times"></i></a>
              <div class="singlefk">
                <label>Enter Email</label>
                <input type="text" class="eml_fd" value="<?php echo $wp_usr->user_email; ?>"/>
              </div>
              <button type="submit" class="button smbtn2">Send</button>
            </form>
          </div>
      </div>
       <div class="schedule_popup">
          <div class="schedule_inner">
            <form action="#" method="POST">
              <a class="clz_pop" href="#"><i class="fa fa-times"></i></a>
              <div class="singlefk">
                <label>Select Date Time</label>
                <input type="text" class="sldt"/>
              </div>
              <button type="submit" class="button smbtn">Save</button>
            </form>
          </div>
      </div>
      <script>
          jQuery('.eml_counts').val(<?php echo $fd_ps; ?>);
          jQuery(document).ready(function(){
            flatpickr('.sldt', {
              enableTime: true
            });
          });
      </script>

      <?php

    }
       if(is_user_logged_in())
       {
            $usr = wp_get_current_user();
            $fname = get_user_meta($usr->ID, 'first_name', true);
            $lname = get_user_meta($usr->ID, 'last_name', true);
            $unm = "Instructor: $fname $lname";
            ?>
            <script>
                jQuery('input#FromName').val('<?php echo $unm; ?>');
            </script>
            <?php
       }
  }

  public function ayecode_get_avatar_url( $url, $id_or_email, $args ) {
    $id = '';
    if ( is_numeric( $id_or_email ) ) {
      $id = (int) $id_or_email;
    } elseif ( is_object( $id_or_email ) ) {
      if ( ! empty( $id_or_email->user_id ) ) {
        $id = (int) $id_or_email->user_id;
      }
    } else {
      $user = get_user_by( 'email', $id_or_email );
      $id = !empty( $user ) ?  $user->data->ID : '';
    }
    //Preparing for the launch.
    $custom_url = $id ?  get_user_meta( $id, 'ayecode-custom-avatar', true ) : '';

    // If there is no custom avatar set, return the normal one.
    if( $custom_url == '' || !empty($args['force_default'])) {
      return esc_url_raw( 'https://secure.gravatar.com/avatar/ce17e0dd7f6584c8b9088a8ed88b7542?s=96&d=mm&r=g' );
    }else{
      return esc_url_raw($custom_url);
    }
  }

  public function ayecode_save_local_avatar_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) ) {
      if( isset($_POST[ 'ayecode-custom-avatar' ]) ){
        $avatar = esc_url_raw( $_POST[ 'ayecode-custom-avatar' ] );
        update_user_meta( $user_id, 'ayecode-custom-avatar', $avatar );
      }
    }
  }

  public function custom_user_profile_fields( $profileuser ) {
    ?>
    <h3><?php _e('Custom Local Avatar', 'ayecode'); ?></h3>
    <table class="form-table ayecode-avatar-upload-options">
      <tr>
        <th>
          <label for="image"><?php _e('Custom Local Avatar', 'ayecode'); ?></label>
        </th>
        <td>
          <?php
          // Check whether we saved the custom avatar, else return the default avatar.
          $custom_avatar = get_the_author_meta( 'ayecode-custom-avatar', $profileuser->ID );
          if ( $custom_avatar == '' ){
            $custom_avatar = get_avatar_url( $profileuser->ID );
          }else{
            $custom_avatar = esc_url_raw( $custom_avatar );
          }
          ?>
          <img style="width: 96px; height: 96px; display: block; margin-bottom: 15px;" class="custom-avatar-preview" src="<?php echo $custom_avatar; ?>">
          <input type="text" name="ayecode-custom-avatar" id="ayecode-custom-avatar" value="<?php echo esc_attr( esc_url_raw( get_the_author_meta( 'ayecode-custom-avatar', $profileuser->ID ) ) ); ?>" class="regular-text" />
          <input type='button' class="avatar-image-upload button-primary" value="<?php esc_attr_e("Upload Image","ayecode");?>" id="uploadimage"/><br />
          <span class="description">
            <?php _e('Please upload a custom avatar for your profile, to remove the avatar simple delete the URL and click update.', 'ayecode'); ?>
          </span>
        </td>
      </tr>
    </table>
    <?php
  }

  public function ayecode_admin_media_scripts() {
    ?>
    <script>
      jQuery(document).ready(function ($) {
        $(document).on('click', '.avatar-image-upload', function (e) {
          e.preventDefault();
          var $button = $(this);
          var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Custom Avatar',
            library: {
              type: 'image' // mime type
            },
            button: {
              text: 'Select Avatar'
            },
            multiple: false
          });
          file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $button.siblings('#ayecode-custom-avatar').val( attachment.sizes.thumbnail.url );
            $button.siblings('.custom-avatar-preview').attr( 'src', attachment.sizes.thumbnail.url );
          });
          file_frame.open();
        });
      });
    </script>
    <?php
  }

  public function ayecode_enqueue($hook)
  {
    if( $hook === 'profile.php' || $hook === 'user-edit.php' ){
      add_thickbox();
      wp_enqueue_script( 'media-upload' );
      wp_enqueue_media();
    }
  }

  public function custom_pd_query($query)
  {
    $query->set( 'author', get_current_user_id() );
    $query->set( 'post_status', array('publish', 'draft') );
  }

  public function add_role_to_body_class( $classes ) {
    $current_user = wp_get_current_user();
    $current_role = (array) $current_user->roles;

    if( $current_role[0] ){
        $classes[] = 'user-role-'.$current_role[0];
    }

    return $classes;
  }

  public function email_count($atts)
  {
    $args = array(
      'post_type' => 'newsletter',
      'posts_per_page' => -1,
      'post_status' => array( 'paused', 'draft', 'publish' ),
      'author' => get_current_user_id()
    );
    $the_query = new WP_Query( $args );
    if(!empty($atts['at']))
    {
        return $the_query->found_posts;
    }
    else
    {
        return '<span class="bdg">'.$the_query->found_posts .' emails'.'</span>';
    }
    // return "2 out of 4 emails";
  }

  public function filter_pds()
  {
     $form_dat = $_POST['form_data'];
     if(!empty($form_dat))
     {
        $arr = null;
        $returnValue = parse_str($form_dat, $arr);

        $args = array(
          'post_type' => 'products',
          'posts_per_page' => -1,
          'author'  => get_current_user_id(),
          'orderby' => 'DATE'
        );
        if(!empty($arr['search_string']))
        {
          $args['s'] = $arr['search_string'];
        }
        if(!empty($arr['product_type']))
        {
            $args['meta_query'] = array(
              array(
              'key' => 'select-cpt',
              'value' => $arr['product_type'],
              'compare' => 'LIKE',
              ),
            );
        }
        if(!empty($arr['product_status']))
        {
          $args['post_status'] = $arr['product_status'];
        }
        if(!empty($_POST['sort']))
        {
            $args['order'] = $_POST['sort'];
        }
        $the_query = new WP_Query( $args );
        ob_start();
        if ( $the_query->have_posts() ) {
        //echo '<ul>';
        ?>
<style id="loop-4792">.elementor-4792 .elementor-element.elementor-element-6955ab9{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--justify-content:space-evenly;--align-items:center;--gap:0px;--flex-wrap:wrap;--background-transition:0.3s;border-style:solid;--border-style:solid;border-width:0px 0px 1px 0px;--border-width-top:0px;--border-width-right:0px;--border-width-bottom:1px;--border-width-left:0px;border-color:#CCCCCC;--border-color:#CCCCCC;--margin-top:0px;--margin-right:0px;--margin-bottom:0px;--margin-left:0px;--padding-top:20px;--padding-right:20px;--padding-bottom:20px;--padding-left:20px;}.elementor-4792 .elementor-element.elementor-element-6955ab9, .elementor-4792 .elementor-element.elementor-element-6955ab9::before{--border-transition:0.3s;}.elementor-4792 .elementor-element.elementor-element-6955ab9.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-12a0038{--width:17.145%;--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--justify-content:center;--background-transition:0.3s;border-style:none;--border-style:none;--margin-top:0px;--margin-right:0px;--margin-bottom:0px;--margin-left:0px;--padding-top:0px;--padding-right:0px;--padding-bottom:0px;--padding-left:0px;}.elementor-4792 .elementor-element.elementor-element-12a0038:not(.elementor-motion-effects-element-type-background), .elementor-4792 .elementor-element.elementor-element-12a0038 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-4792 .elementor-element.elementor-element-12a0038, .elementor-4792 .elementor-element.elementor-element-12a0038::before{--border-transition:0.3s;}.elementor-4792 .elementor-element.elementor-element-12a0038.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-fdda04c{--width:30%;--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--justify-content:center;--background-transition:0.3s;border-style:none;--border-style:none;--margin-top:0px;--margin-right:0px;--margin-bottom:0px;--margin-left:0px;--padding-top:20px;--padding-right:20px;--padding-bottom:20px;--padding-left:20px;}.elementor-4792 .elementor-element.elementor-element-fdda04c:not(.elementor-motion-effects-element-type-background), .elementor-4792 .elementor-element.elementor-element-fdda04c > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-4792 .elementor-element.elementor-element-fdda04c, .elementor-4792 .elementor-element.elementor-element-fdda04c::before{--border-transition:0.3s;}.elementor-4792 .elementor-element.elementor-element-fdda04c.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-4fa7214 .elementor-heading-title{color:#68696B;font-size:18px;font-weight:500;}.elementor-4792 .elementor-element.elementor-element-e166270{--width:20%;--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--justify-content:center;--background-transition:0.3s;border-style:none;--border-style:none;--margin-top:0px;--margin-right:0px;--margin-bottom:0px;--margin-left:0px;--padding-top:0px;--padding-right:0px;--padding-bottom:0px;--padding-left:0px;}.elementor-4792 .elementor-element.elementor-element-e166270:not(.elementor-motion-effects-element-type-background), .elementor-4792 .elementor-element.elementor-element-e166270 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-4792 .elementor-element.elementor-element-e166270, .elementor-4792 .elementor-element.elementor-element-e166270::before{--border-transition:0.3s;}.elementor-4792 .elementor-element.elementor-element-e166270.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-18781dc .elementor-icon-list-icon{width:14px;}.elementor-4792 .elementor-element.elementor-element-18781dc .elementor-icon-list-icon i{font-size:14px;}.elementor-4792 .elementor-element.elementor-element-18781dc .elementor-icon-list-icon svg{--e-icon-list-icon-size:14px;}.elementor-4792 .elementor-element.elementor-element-18781dc .elementor-icon-list-text, .elementor-4792 .elementor-element.elementor-element-18781dc .elementor-icon-list-text a{color:#000000;}.elementor-4792 .elementor-element.elementor-element-18781dc > .elementor-widget-container{margin:10px 0px 0px -10px;}.elementor-4792 .elementor-element.elementor-element-9ca23c9{--width:20%;--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--justify-content:space-between;--align-items:center;--background-transition:0.3s;border-style:none;--border-style:none;--margin-top:0px;--margin-right:0px;--margin-bottom:0px;--margin-left:0px;--padding-top:0px;--padding-right:0px;--padding-bottom:0px;--padding-left:0px;}.elementor-4792 .elementor-element.elementor-element-9ca23c9:not(.elementor-motion-effects-element-type-background), .elementor-4792 .elementor-element.elementor-element-9ca23c9 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-4792 .elementor-element.elementor-element-9ca23c9, .elementor-4792 .elementor-element.elementor-element-9ca23c9::before{--border-transition:0.3s;}.elementor-4792 .elementor-element.elementor-element-9ca23c9.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-be0a1e9.elementor-element{--flex-grow:0;--flex-shrink:0;}.elementor-4792 .elementor-element.elementor-element-17aa021 .elementor-heading-title{color:#2E2E2E;}.elementor-4792 .elementor-element.elementor-element-17aa021.elementor-element{--flex-grow:0;--flex-shrink:0;}@media(max-width:1024px){.elementor-4792 .elementor-element.elementor-element-be0a1e9{width:initial;max-width:initial;}.elementor-4792 .elementor-element.elementor-element-17aa021{width:initial;max-width:initial;}}@media(max-width:767px){.elementor-4792 .elementor-element.elementor-element-17aa021{width:initial;max-width:initial;}}</style>
        <?php
        while ( $the_query->have_posts() ) {
          $the_query->the_post();
          $sd = get_post_meta(get_the_ID(), 'select-cpt', true);
          $slg = '';
          if($sd=='course')
          {
            $scr = get_post_meta(get_the_ID(), 'select-course', true);
            $slg = 'course-edit';
          }
          elseif($sd=='landing-page')
          {
            $scr = get_post_meta(get_the_ID(), 'select-landing-page', true);
            $slg = 'landing-edit';
          }
          elseif($sd=='community')
          {
            $scr = get_post_meta(get_the_ID(), 'select-community', true);
            $slg = 'community-edit';
          }

          $pm = array(
            "course"        =>  "Course",
            "community"     =>  "Community",
            "landing-page"  =>  "Landing Page",
          );
          ?>
          <div data-elementor-type="loop-item" data-elementor-id="4792" class="elementor elementor-4792 e-loop-item e-loop-item-5739 post-5739 products type-products status-publish has-post-thumbnail hentry" data-custom-edit-handle="1">
            <div class="elementor-element elementor-element-6955ab9 e-con-full e-flex e-con" data-id="6955ab9" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;}">
              <div class="elementor-element elementor-element-12a0038 e-con-full e-flex e-con" data-id="12a0038" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;background_background&quot;:&quot;classic&quot;,&quot;background_motion_fx_motion_fx_scrolling&quot;:&quot;yes&quot;,&quot;background_motion_fx_devices&quot;:[&quot;desktop&quot;,&quot;laptop&quot;,&quot;tablet&quot;,&quot;mobile&quot;]}">
                <div class="elementor-element elementor-element-c1f63b8 elementor-widget elementor-widget-theme-post-featured-image elementor-widget-image" data-id="c1f63b8" data-element_type="widget" data-widget_type="theme-post-featured-image.default">
                  <div class="elementor-widget-container">
                    <?php echo get_the_post_thumbnail(); ?>
                  </div>
                </div>
              </div>
              <div class="elementor-element elementor-element-fdda04c e-con-full e-flex e-con" data-id="fdda04c" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;background_background&quot;:&quot;classic&quot;,&quot;background_motion_fx_motion_fx_scrolling&quot;:&quot;yes&quot;,&quot;background_motion_fx_devices&quot;:[&quot;desktop&quot;,&quot;laptop&quot;,&quot;tablet&quot;,&quot;mobile&quot;]}">
                <div class="elementor-element elementor-element-4fa7214 elementor-widget elementor-widget-theme-post-title elementor-page-title elementor-widget-heading" data-id="4fa7214" data-element_type="widget" data-widget_type="theme-post-title.default">
                  <div class="elementor-widget-container">
                    <h1 class="elementor-heading-title elementor-size-default"><?php the_title(); ?></h1>
                  </div>
                </div>
                <div class="elementor-element elementor-element-a8eb5e1 elementor-widget elementor-widget-shortcode" data-id="a8eb5e1" data-element_type="widget" data-widget_type="shortcode.default">
                  <div class="elementor-widget-container">
                    <div class="elementor-shortcode">
                      <div class="prd_meta">
                        <div class="pd_badge">
                          <span class="badge badge_<?php echo $sd; ?>"><?php echo $pm[$sd]; ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="elementor-element elementor-element-e166270 e-con-full e-flex e-con" data-id="e166270" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;background_background&quot;:&quot;classic&quot;,&quot;background_motion_fx_motion_fx_scrolling&quot;:&quot;yes&quot;,&quot;background_motion_fx_devices&quot;:[&quot;desktop&quot;,&quot;laptop&quot;,&quot;tablet&quot;,&quot;mobile&quot;]}">
                <div class="elementor-element elementor-element-18781dc elementor-widget elementor-widget-post-info" data-id="18781dc" data-element_type="widget" data-widget_type="post-info.default">
                  <div class="elementor-widget-container">
                    <ul class="elementor-inline-items elementor-icon-list-items elementor-post-info">
                      <li class="elementor-icon-list-item elementor-repeater-item-2ad4084 elementor-inline-item" itemprop="datePublished">
                        <a href="https://wordpress-965314-3406517.cloudwaysapps.com/2023/07/06/">
                          <span class="elementor-icon-list-text elementor-post-info__item elementor-post-info__item--type-date"> <?php echo get_the_date( 'F j, Y' ); ?> </span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="elementor-element elementor-element-9ca23c9 e-con-full e-flex e-con" data-id="9ca23c9" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;background_background&quot;:&quot;classic&quot;,&quot;background_motion_fx_motion_fx_scrolling&quot;:&quot;yes&quot;,&quot;background_motion_fx_devices&quot;:[&quot;desktop&quot;,&quot;laptop&quot;,&quot;tablet&quot;,&quot;mobile&quot;]}">
                <div class="elementor-element elementor-element-be0a1e9 elementor-widget-tablet__width-initial elementor-widget elementor-widget-shortcode" data-id="be0a1e9" data-element_type="widget" data-widget_type="shortcode.default">
                  <div class="elementor-widget-container">
                    <div class="elementor-shortcode">
                      <span class="badge badge_<?php echo get_post_status( ); ?>"><?php echo get_post_status_object( get_post_status( ) )->label; ?></span>
                    </div>
                  </div>
                </div>
                <div class="elementor-element elementor-element-17aa021 top_ku elementor-widget-tablet__width-initial elementor-widget-mobile__width-initial elementor-widget elementor-widget-heading" data-id="17aa021" data-element_type="widget" data-widget_type="heading.default">
                  <div class="elementor-widget-container">
                    <h2 class="elementor-heading-title elementor-size-default">
                      <div class="drop_area">
                        <a href="#" class="open_drp">...</a>
                        <div class="drp_mn">
                          <ul>
                            <li>
                              <a href="<?php echo get_the_permalink($scr); ?>">View</a>
                            </li>
                            <li>
                              <a href="<?php echo site_url(); ?>/<?php echo $slg; ?>/?pid=<?php echo $scr; ?>">Edit</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
        //echo '</ul>';
        } else {
        esc_html_e( 'Sorry, no posts matched your criteria.' );
        }
        wp_reset_postdata();

        $html = ob_get_contents();
        ob_end_clean();
        if(!empty($html))
        {
           $de = array(
              'status' => true,
              'html'   => $html
           );
        }
        else
        {
            $de = array(
              'status' => false,
              //'html'   => $html
            );
        }
        wp_send_json($de);
     }
  }

  public function lw_filter_front()
  {
      ob_start();
      ?>
       <form method="POST" class="filter_fom">
      <div class="container dj mx-auto px-4 h-full filter_fm">

      <div class="author-toolbar-filter-wrapper author-toolbar-search-wrapper v-middle"><div class="author-toolbar-search-bar"><input class="searchh input-simple" type="text" placeholder="Search" name="search_string"> <div class="-search-input-btn"></div></div></div>



        <div class="flex items-center justify-center h-full w-full">
          <div class="relative mb-2 flex items-center after:w-[8px] after:h-[8px] after:border-black/70 after:border-b after:border-r after:transform after:rotate-45 after:absolute after:right-3">
            <!-- Select menu -->
            <select class="selbox" name="product_type">
              <option value="" disabled selected>All Products</option>
              <option value="course">Course</option>
              <option value="community">Community</option>
              <option value="landing-page">Landing Page</option>
            </select>
          </div>
        </div>

        <div class="flex items-center justify-center h-full w-full">
          <div class="relative mb-2 flex items-center after:w-[8px] after:h-[8px] after:border-black/70 after:border-b after:border-r after:transform after:rotate-45 after:absolute after:right-3">
            <select class="selbox" name="product_status">
              <option value="" disabled selected>Product status</option>
              <option value="publish">Published</option>
              <option value="pending">Pending</option>
              <option value="draft">Draft</option>
            </select>
          </div>
        </div>


        <!-- <div class="flex items-center justify-center h-full w-full">
          <div class="relative mb-2 flex items-center after:w-[8px] after:h-[8px] after:border-black/70 after:border-b after:border-r after:transform after:rotate-45 after:absolute after:right-3">
            <select required class="selbox">
              <option value="" selected>ALL Categories</option>
              <option value="option1">HTML</option>
              <option value="option2">JavaScript</option>
              <option value="option3">Tailwind CSS</option>
            </select>
          </div>
        </div> -->
        <div class="toggl">
          <a class="view grid on" href="#" aria-label="Grid"><i class="fa fa-th" aria-hidden="true"></i></a>
          <a class="view list" href="#" aria-label="List"><i class="fa fa-bars" aria-hidden="true"></i></a>
        </div>
        <div class="flex items-center justify-center h-full w-full">
          <!-- Select menu div contains custom arrow -->
          <div class="relative mb-2 flex items-center after:w-[8px] after:h-[8px] after:border-black/70 after:border-b after:border-r after:transform after:rotate-45 after:absolute after:right-3">
              <button class="button rd_btn" type="submit" name="submit_filter">Apply</button>
          </div>
        </div>
      </div>
      </form>

      <style>
      input.searchh.input-simple {
        width: 50%;
      }
        .dj {
              gap: 10px;
          display: flex;
        justify-content:space-between;
      }
          div#searchBox {
            float: none;
            color: #777;
          }

      </style>
      <?php
      $html = ob_get_contents();
      ob_end_clean();
      return $html;
  }

  public function show_pmeta()
  {
    global $post;
    $post_stt = array(
      'publish' => 'Published',
      'draft' => 'Draft'
    );
    $pst = get_post_status($post->ID);
    return '<span class="badge badge_'.$pst.'">'.$post_stt[$pst].'</span>';
  }

  public function csmeta()
  {
    global $post;
    $selc = get_post_meta($post->ID, 'select-cpt', true);
    $ar = array(
      'course' => 'Course',
      'community' => 'Community',
      'landing-page' => 'Landing Page',
    );
    $html = '';
    $html .= '<div class="prd_meta">';
    if(!empty($selc))
    {
      $html .= '<div class="pd_badge">';
      $html .= '<span class="badge badge_'.$selc.'">'.$ar[$selc].'</span>';
      $html .= '</div>';
    }
    $csdt = '';
    if($selc=='course')
    {
       $sp = get_post_meta($post->ID, 'select-course', true);
       $csdt = get_post_meta($sp, 'number-of-students', true);
    }
    elseif($selc=='community')
    {
       $sp = get_post_meta($post->ID, 'select-community', true);
       $csdt = get_post_meta($sp, 'student-enroll_0', true);
    }
    elseif($selc=='landing-page')
    {
       $sp = get_post_meta($post->ID, 'select-landing-page', true);
       $csdt = get_post_meta($sp, 'students-enrolled', true);
    }
    $html .= '</div>';
    $htmlb = '';
    $im = site_url()."/wp-content/uploads/2023/07/Screenshot.png";
    $htmlb = '<span class="pct"><img src="'.$im.'"/>'.$csdt.'</span>';
    return $html.$htmlb;
  }


  public function show_drop()
  {
    global $post;
    $selc = get_post_meta($post->ID, 'select-cpt', true);
    $ur = '';
    if($selc=='course')
    {
      $sk = get_post_meta($post->ID, 'select-course', true);
      $ur = site_url().'/course-edit/?pid='.$sk;
    }
    elseif($selc=='landing-page')
    {
      $sp = get_post_meta($post->ID, 'select-landing-page', true);
      $ur = site_url().'/landing-edit/?pid='.$sp;
    }
    elseif($selc=='community')
    {
      $sp = get_post_meta($post->ID, 'select-community', true);
      $ur = site_url().'/community-edit/?pid='.$sp;
    }
    $html = '<div class="drop_area">';
      $html .= '<a href="#" class="open_drp">...</a>';
        $html .= '<div class="drp_mn"><ul>';
        $html .= '<li><a href="#">View</a></li>';
        $html .= '<li><a href="'.$ur.'">Edit</a></li>';
        $html .= '</ul></div>';
    $html .= '</div>';
    return $html;
  }


  public function custom_cmark()
  {
    global $post;
    $cm = get_post_meta($post->ID, 'checkmark', true);

    $html = '<div class="cstm_cklist"><ul class="elementor-icon-list-items elementor-inline-items">';
    if(!empty($cm))
    {
      foreach($cm as $cd)
      {
        $cont = $cd['check_cont'];
        $html .= '<li class="elementor-icon-list-item elementor-inline-item">
                  <span class="elementor-icon-list-icon"><i aria-hidden="true" class="far fa-check-circle"></i></span>
                  <span class="elementor-icon-list-text">'.$cont.'</span>
              </li>';
      }
    }
    $html .= '</ul></div>';
    return $html;
  }

  public function check_sect()
  {
    global $post;
    $cm = get_post_meta($post->ID, 'checkmark_469', true);
    $html = '';
    if(!empty($cm))
    {
      $html = '<div class="cstm_list"><ul class="elementor-icon-list-items elementor-inline-items">';
      foreach($cm as $ci)
      {
        $html .= '<li class="elementor-icon-list-item elementor-inline-item">
              <span class="elementor-icon-list-icon">
      <i aria-hidden="true" class="fas fa-check-circle"></i>						</span>
            <span class="elementor-icon-list-text">'.$ci['check-content'].'</span>
          </li>';
      }
      $html .= '</ul></div>';
    }
    return $html;
  }

  public function ico_box_feat2()
  {
    global $post;
    $html = '';
    $cm = get_post_meta($post->ID, 'checkmark_827', true);

    ob_start();
    if(!empty($cm))
    {
      echo "<div class='cstm_icn_area'>";
      foreach($cm as $ck)
      {
        ?>
        <div class="elementor-widget-container">
          <link rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/uploads/elementor/css/custom-widget-icon-box.min.css?ver=1686771194">		<div class="elementor-icon-box-wrapper">
                <div class="elementor-icon-box-icon">
            <span class="elementor-icon elementor-animation-">
            <i aria-hidden="true" class="far fa-check-circle"></i>				</span>
          </div>
                <div class="elementor-icon-box-content">
            <h3 class="elementor-icon-box-title">
              <span><?php echo $ck['feat2_title']; ?></span>
            </h3>
                      <p class="elementor-icon-box-description"><?php echo $ck['feat2_cnt']; ?></p>
                  </div>
        </div>
        </div>
        <?php
      }
      echo '</div>';
    }
    ?>

    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function box_section()
  {

  }


  public function show_usr_logo()
  {
    $uid = get_current_user_id();
    $ulogo = get_user_meta($uid, 'user_logo', true);
    if(!empty($ulogo))
    {
      return "<div class='pro_co'>
      <div class='usr_logo'><p><b>Logo</b><p><img src='".$ulogo['url']."'/></div>
      </div>";
    }
  }


  public function show_usr_avtr()
  {
    $uid = get_current_user_id();
    $ulogo = get_user_meta($uid, 'ayecode-custom-avatar', true);
    if(!empty($ulogo))
    {
      return "<div class='pro_co'><div class='usr_logo'><p><b>Avatar</b><p><img src='".$ulogo."'/></div></div>";
    }
  }



  public function all_campaigns()
  {
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $rcl = $_GET['ed'];
    $args = array(
      'post_type' => 'newsletter',
      'author' => get_current_user_id(),
      'paged'         => $paged,
      'orderby'       => 'date',
      'posts_per_page' => 5,
      'post_status' => array( 'paused', 'draft', 'publish' )
    );
    $the_query = new WP_Query( $args );
    ob_start();
    if ( $the_query->have_posts() ) {
      echo '<div class="cstm_field">
      <ul class="rec_list">
      ';
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        //echo '<option value="'.get_the_ID().'">' . esc_html( get_the_title() ) . '</option>';
        $clk = '';
        if($rcl==get_the_ID())
        {
          $clk = 'act';
        }
        echo '<li class="'.$clk.'">';
        echo '<a href="'.site_url().'/dashboard/email?ed='.get_the_ID().'" data-id="'.get_the_ID().'">'.get_the_title().'</a>';
        echo '<span class="dbg">'.get_post_status(get_the_ID()).'</span>';
        echo '</li>';
      }
      echo "<nav class=\"sw-pagination\">";
       $big = 999999999; // need an unlikely integer
       echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $the_query->max_num_pages
    ) );
    echo "</nav>";
      echo '</ul></div>';
    }
    wp_reset_postdata();
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }


  public function usr_courses()
  {
    $args = array(
      'post_type' => 'courses',
      'author' => get_current_user_id()
    );
    $the_query = new WP_Query( $args );
    ob_start();
    if ( $the_query->have_posts() ) {
      echo '<div class="cstm_field">
      <label>Recipients</label>
      <select class="sel_rec">
      <option value="">Select a Course</option>
      ';
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        echo '<option value="'.get_the_ID().'">' . esc_html( get_the_title() ) . '</option>';
      }
      echo '</select></div>';
    } /*else {
      esc_html_e( 'Sorry, no posts matched your criteria.' );
    }*/
    wp_reset_postdata();
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }


  public function ed_course_fun()
  {
    $pid = $_GET['pid'];
    $gp = get_post($pid);
    $cvd = get_post_meta($pid, 'course-video', true);
    $cpos = get_post_meta($pid, 'course-video-thumbnail', true);
    $cmt = get_post_meta($pid, 'learning-unit', true);
    $wt = get_post_meta($pid, 'what-you-learn', true);
    $creq = get_post_meta($pid, 'what-are-the-requirement-or-prerequisites-for-taking-your-course-', true);
    $clrns = get_post_meta($pid, 'intended-learners_823', true);
    $author_id = get_post_field( 'post_author', $pid );
    if(is_user_logged_in())
    {
      $cuid = get_current_user_id();
    }
    // echo $author_id;
    // die;
    //$req = get_post_meta($pid, 'reqbox', true);
    //echo "<pre>".print_r($wt,true); die;
    if(!empty($_POST['submit_course']))
    {
        //echo "<pre>".print_r($_FILES,true); die;
        $ui = array();

        $cid = $_POST['submit_course'];
        $pstl = sanitize_text_field($_POST['edcourse']);
        $pscn = $_POST['course_desc'];
        $vfile = $this->mupfile('video_file', $cid);
        //$video_poster = $this->mupfile('video_poster', $cid);
        //$_POST['video_poster'];
        $vid_url = $_POST['vid_url'];
        $sec_id = $_POST['sect_id'];
        $lnumbr = $_POST['lunit_unit_number'];
        $ltl = $_POST['lunit_title'];
        $utyp = $_POST['unit_type'];
        $mat = $_POST['material_name'];
        $mat_det = $_POST['material_details'];
        //$lrunit = $_POST['learning_units'];
        $csrss = $_POST['crs_cat'];
        $Cct = $_POST['subcat'];
        $req = $_POST['reqbox'];
        $ln = $_POST['learners'];
        $lr = $_POST['learn_box'];
        $lpt = $_POST['learning_paths'];
        $stle = $_POST['sect_title'];
        $ldt = array();
        foreach($lr as $ju=>$ruz)
        {
          $ldt['item-'.$ju] = array(
            'learning-item' => $ruz
          );
        }
		$dm = array();
        foreach($sec_id as $kp=>$sy)
        {
          $mp = $kp+1;
		  $vm = '';
          if(!empty($_FILES['learning_unit_'.$mp]['name']))
          {
              $vfileb = $this->mupfile('learning_unit_'.$mp, $cid);
			  //echo wp_get_attachment_url($vfileb);
              if(!empty($vfileb))
              {
                 $dm[$mp] = $vfileb;
              }
          }
          else
          {
                $dm[$mp] = $cmt['item-'.$kp]['learning-unit-preview'];
          }
          $ui['item-'.$kp] =
            array(
              'section-title' => $sy,
              //'merarial-name' 	=> $mat[$kp],
              'learning-unit-id' => $lnumbr[$kp],
              'learning-unit-preview' => $dm[$mp],
              'learning-unit-type' => $utyp[$kp],
              'learning-unit-title' => $ltl[$kp]
              );
        }
		//echo "<pre>".print_r($ui, true);
		//die;
        $rq = array();
        foreach($req as $il=>$ru)
        {
          $rq['item-'.$il] = array(
            'text' => $ru
          );
        }
        $uz = array();
        foreach($ln as $qm=>$lo)
        {
        $uz['item-'.$qm] = array(
            'text' => $lo
          );
        }
        $my_post = array(
            'ID'           => $cid,
            'post_title'   => $pstl,
            'post_content' => $pscn,
        );
        wp_update_post( $my_post );
        update_post_meta($cid, 'course-video', $vfile);
        update_post_meta($cid, 'learning-unit', $ui);
        update_post_meta($cid, 'what-are-the-requirement-or-prerequisites-for-taking-your-course-', $rq);
        update_post_meta($cid, 'intended-learners_823', $uz);
        update_post_meta($cid, 'course-metarials', $cmt);
        $cat_ids = array();
        if(!empty($csrss))
        {
            $cat_ids[0] = $csrss;
            if(!empty($csrss))
            {
              $cat_ids[1] = $Cct;
            }
            $cat_ids = array_map( 'intval', $cat_ids );
            $cat_ids = array_unique( $cat_ids );
            wp_set_object_terms( $cid, $cat_ids, 'course-category' );
        }
        $learning = array();
        foreach($stle as $st=>$km)
        {
            $learning[$st]['title'] = $km;
            $learning[$st]['course'] = $lpt[$st];
        }
        update_post_meta($cid, 'learning-paths', $learning);
        // echo "<pre>".print_r($learning, true);
        // die;
        update_post_meta($cid, 'what-you-learn', $ldt);
        $up = site_url().'/course-edit/?pid='.$cid.'&updated=1';
        wp_redirect($up);
        exit();
    }
    ob_start();
    if(!empty($_POST['updated']))
    {
      ?>
      <div class="al al-success">Course has been updated successfully</div>
      <?php
    }
    if($cuid==$author_id)
    {
    ?>
    <form action="" method="POST" enctype="multipart/form-data" class="cedit">

      <div class="roww">
        <div class="columnc">
          <h2>Details</h2>
          <p> Give your product a title, add a description, and upload a product thumbnail image. This image will show in your customer's library of photos they have purchased for you</p>
        </div>
        <div class="columnc borde">
      <div class="ed_single">
            <label>Title</label>
            <input class="txt-field" type="text" name="edcourse" value="<?php echo $gp->post_title; ?>">
      </div>
        <div class="ed_single">
            <label>Course Description</label>
            <?php
              $content   = $gp->post_content;
              $editor_id = 'course_desc';
              $settings  = array( 'media_buttons' => false, 'textarea_rows' => 5, 'quicktags' => false );
              wp_editor( $content, $editor_id, $settings );

              ?>
      </div>
      <div class="ed_single">
        <p>Product Video</p>
        <?php
              $course_video = get_post_meta($gp->ID, 'course-video', true);
              $vid = '';
              if(!empty($course_video))
              {
                  $mn = get_post_mime_type($course_video);
                  $sd = wp_get_attachment_url($course_video);
                  $vid = '<video width="320" height="240" controls>
                            <source src="'.$sd.'" type="'.$mn.'">
                            Your browser does not support the video tag.
                          </video>';
              }
            ?>
       <?php if(!empty($vid)) {  echo $vid; } ?>
      </div>
          <div>
      <div class="ed_single">

            <p>This video will be displayed as a preview on the course details page.</p>
            <p>Recommended dimensions of 1280x720</p>
            <input type='file'  id='vdf' name="video_file"/>
            <!-- <div class="sng_fd">
              <label>Video Poster</label>
                <input type='file'  id='videoUpload' name="video_poster"/>
            </div> -->

        <?php /*
        <div class="vid_url">
            <input type="text" name="vid_url" placeholder="Insert Video URL here." value="<?php echo $cvd; ?>"/>
        </div> */ ?>
      </div>
        </div>
        </div>
      </div>
      <br>
  <!-- second section  -->
      <div class="roww">
        <div class="columnc ">
          <h2>Details</h2>
          <p> Give your product a title, add a description, and upload a product thumbnail image. This image will show in your customer's library of photos they have purchased for you</p>
        </div>
        <div class="columnc">
          <div class="borde">
            <h3>Course Sections</h3>
        <?php if(!empty($cmt)) {
        $i = 1;
        foreach($cmt as $ce)
        {
          $ltp = $ce['learning-unit-type'];
          ?>
          <div class="single_csect">
          <h4 class="acc_title">Section <?php echo $i; ?> <a class="act_link" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i> </h4>
          <div class="acc-content">
          <div class="ed_single">
            <label>Section Title</label>
            <input type="text" name="sect_id[]" value="<?php echo $ce['section-title']; ?>"/>
          </div>
          <div class="ed_single">
            <label>Learning Unit Number</label>
            <input type="text" name="lunit_unit_number[]" value="<?php echo $ce['learning-unit-id']; ?>"/>
          </div>
           <div class="ed_single">
            <label>Learning Unit Title</label>
            <input type="text" name="lunit_title[]" value="<?php echo $ce['learning-unit-title']; ?>"/>
          </div>
          <div class="ed_single">
            <label>Learning Unit Type</label>
            <select class="vd_type" name="unit_type[]">
              <option value=""></option>
              <option <?php if($ltp=='ivideo') { ?>selected="selected" <?php } ?> value="ivideo">iVideo</option>
              <option <?php if($ltp=='video') { ?>selected="selected" <?php } ?> value="video">Video</option>
            </select>
          </div>
          <div class="ed_single">
            <label>Learning Units Preview</label>
			  <?php
			if(!empty($ce['learning-unit-preview']))
			{
        //echo $ce['learning-unit-preview'];
				$vd = $ce['learning-unit-preview'];
				 $mn = get_post_mime_type($vd);
                 $sd = wp_get_attachment_url($vd);
                 $vid = '<video width="320" height="240" controls>
                            <source src="'.$sd.'" type="'.$mn.'">
                            Your browser does not support the video tag.
                          </video>';
                          if(!empty($sd))
                          {
                            echo $vid;
                          }
			}
			 ?>
            <input type="file" name="learning_unit_<?php echo $i; ?>" value=""/>
          </div>
          </div>
          </div>
          <?php
          $i++;
        }
        ?>

        <?php } else { ?>
            <div class="single_csect">
              <h4 class="acc_title">Section 1 <a class="act_link" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i> </h4>
              <div class="acc-content">
              <div class="ed_single">
                <label>Section Title</label>
                <input type="text" name="sect_id[]"/>
              </div>
              <div class="ed_single">
                <label>Learning Unit Number</label>
                <input type="text" name="lunit_unit_number[]"/>
              </div>
              <div class="ed_single">
                <label>Learning Unit Title</label>
                <input type="text" name="lunit_title[]"/>
              </div>
              <div class="ed_single">
                <label>Learning Unit Type</label>
                <select class="vd_type" name="unit_type[]">
                  <option value=""></option>
                  <option value="ivideo">iVideo</option>
                  <option value="video">Video</option>
                </select>
              </div>
              <div class="ed_single">
                <label>Learning Units Preview</label>
                <input type="file" name="learning_unit_1/>
              </div>
              </div>
            </div>
        <?php } ?>
          </div>

    </div>
    </div>

  <br>
  <!-- add text box section  -->
  <div class="roww">
    <div class="columnc">
      <h2>Bullet Points</h2>
      <p> You must enter at least 4 learnig objectives or outcomes that learners can expect to achive after completing your course.</p>
    </div>
    <div class="columnc borde">

      <div class="newboxtext" style=" overflow: auto">
        <div class="nxbox" id='TextBoxesGroup'>
        <h3>What will student learn in your course ?</h3>
          <div class="dyn_area">
            <?php if(!empty($wt)) {
              $m = 1;
              foreach($wt as $dim=>$wk) {
              ?>
              <div class="tbx">
                <input value="<?php echo $wk['learning-item']; ?>" class="txt-field" type='textbox' id='reqbox1' name='learn_box[]' style="margin-bottom:10px;"/><a data-id="<?php echo $dim; ?>" class="delpt" href="#">&times;</a>
              </div>
              <?php
                $m++;
              }
            } else { ?>
              <div class="tbx">
                <input class="txt-field" type='textbox' id='reqbox1' name='learn_box[]' style="margin-bottom:10px;"/>
              </div>
            <?php } ?>
            </div>
        </div>
    </div>
    <?php /*
    <input type='button' value='Add more to your response' class='adbt'/>
    <input type='button' value='Remove Field' class='rmbt'/> */ ?>
    <button class="adbt" type="button"><i class="fa fa-plus"></i>&nbsp;Add more to your response</button>
    <button class="rmbt" type="button"><img src="<?php echo site_url(); ?>/wp-content/uploads/2023/07/del-icon.png"/>Remove Field</button>

    </div>
  </div>
  <br>

  <!-- Course Requirements
  -->
  <div class="roww">
    <div class="columnc">
      <h2>Course Requirements    </h2>
      <p>
        List the required skills, experience, tools or
        equipment learners should have prior to taking your
        course. If there are no requirements, use this space
        as an opportunity to lower the barrier for beginners.</p>
    </div>
    <div class="columnc borde">

      <div class="newboxtext" style=" overflow: auto">
        <div class="nxbox"  id='TextBoxesGroup1'>
        <h3>What are the requirement or prerequisites for taking your course ?</h3>
              <div class="dyn_area">
              <?php if(!empty($creq)) {
                $q = 1;
                foreach($creq as $cs)
                {
                    ?>
                      <div class="tbx">
                          <input class="txt-field" type='textbox' id='reqbox<?php echo $q; ?>' name='reqbox[]' style="margin-bottom:10px;" value="<?php echo $cs['text']; ?>"/>
                          <a data-id="item-3" class="delpt" href="#">×</a>
                      </div>
                    <?php
                  $q++;
                }
              }
              else
              { ?>
                      <div class="tbx">
                          <input class="txt-field" type='textbox' id='reqbox1' name='reqbox[]' style="margin-bottom:10px;"/>
                          <a data-id="item-3" class="delpt" href="#">×</a>
                      </div>
            <?php } ?>
            </div>
        </div>
    </div>
    <button class="adbt" type="button"><i class="fa fa-plus"></i>&nbsp;Add more to your response</button>
    <button class="rmbt" type="button"><img src="<?php echo site_url(); ?>/wp-content/uploads/2023/07/del-icon.png"/>Remove Field</button>

    </div>
  </div>
  <br>
  <!-- Intended Learners  -->
  <div class="roww">
    <div class="columnc">
      <h2>Intended Learners</h2>
      <p> Write a clear description of the intended learners for
        your course who will find your course content
        valuable. This will help you attract the right learners
        to your course.</p>
    </div>
    <div class="columnc borde">

      <div class="newboxtext" style=" overflow: auto">
        <div class="nxbox"  id='TextBoxesGroup1'>
        <h3>Who is this course for ?</h3>
        <div class="dyn_area">
              <?php if(!empty($clrns)) {
                $q = 1;
                foreach($clrns as $cs)
                {
                    ?>
                      <div class="tbx">
                          <input class="txt-field" type='textbox' id='reqbox<?php echo $q; ?>' name='learners[]' style="margin-bottom:10px;" value="<?php echo $cs['text']; ?>"/>
                          <a data-id="item-3" class="delpt" href="#">×</a>
                      </div>
                    <?php
                  $q++;
                }
              }
              else
              { ?>
                      <div class="tbx">
                          <input class="txt-field" type='textbox' id='reqbox1' name='learners[]' style="margin-bottom:10px;"/>
                          <a data-id="item-3" class="delpt" href="#">×</a>
                      </div>
            <?php } ?>
            </div>
        </div>
    </div>
    <button class="adbt" type="button"><i class="fa fa-plus"></i>&nbsp;Add more to your response</button>
    <button class="rmbt" type="button"><img src="<?php echo site_url(); ?>/wp-content/uploads/2023/07/del-icon.png"/>Remove Field</button>

    </div>
  </div>
  <br>
  <!-- basic onfo section  -->
  <div class="roww">
    <div class="columnc">
      <h2>Basic Info</h2>
      <p>
        Provide basic course info such as the language,category and course level.</p>
    </div>
    <div class="columnc borde">

      <section >
        <p>Language</p>
        <select class="drop1">
          <option value="1">English (US)</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
        </select>

        <select class="drop2">
          <option selected>--Select Level--</option>
          <option value="1">Option 1</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
        </select>
      <?php
        $terms = get_terms([
        'taxonomy' => 'course-category',
        'hide_empty' => false,
      ]);
      ?>
        <select class="drop3 sel_catg" name="crs_cat">
          <option selected value="Category">--Select Category--</option>
        <?php
        foreach($terms as $tr) {
        ?>
        <option value="<?php echo $tr->term_id; ?>"><?php echo $tr->name; ?></option>
        <?php } ?>
        </select>

        <select class="drop4 dyn_sbc" name="subcat">
          <option selected>--Select subcategory--</option>
        </select>
        <br>
      <label for="">Select your money back guarantee policy?
      <i class="fa fa-exclamation-circle" aria-hidden="true"></i></label>
      <br>
        <select class="drop5">
          <option selected>--Money Back Guarantee--</option>
          <option value="1">Option 1</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
        </select>


      </section>

    </div>
  </div>
  <br>
  <?php
    $lt_path = get_post_meta($gp->ID, 'learning-paths', true);
    $user_courses = array();
    $args = array(
        'post_type' => 'courses',
        'author' => get_current_user_id()
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $pstid = get_the_ID();
        $user_courses[$pstid] = get_the_title();
      }
    }
    wp_reset_postdata();
  ?>
  <div class="roww">
    <div class="columnc">
      <h2>Learning Paths</h2>

      </div>
      <div class="columnc borde crs">

        <section>
          <h3>Learning Paths</h3>
          <ul class="prt_sections">
            <?php if(!empty($lt_path)) {
              //echo "<pre>".print_r($lt_path, true);
              foreach($lt_path as $qk=>$qwe)
              {
                $mno = $qk+1;
              ?>
              <li class="single_csect">
                <h4 class="acc_title"><i class="fas fa-arrows-alt"></i>Learning Path <?php echo $mno; ?><a class="act_link2" href="#" data-index="<?php echo $mno; ?>"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i></h4>
                <div class="acc-content">
                  <?php /*
                <div class="ed_single">
                      <label>Section Title</label>
                      <input type="text" name="sect_title[]" value="<?php echo $qwe['title']; ?>" class="sct_ttl"/>
                </div>*/ ?>
                <input type="hidden" name="sect_title[]" value="<?php echo $qwe['title']; ?>"/>
                  <div class="ed_single">
                      <label>Select Course</label>
                      <?php
                        if(!empty($user_courses))
                        {
                          $crs = $qwe['course'];
                          echo '<select name="learning_paths[]">
                          <option value=""></option>';
                          foreach($user_courses as $cidd=>$usc)
                          {
                            ?>
                            <option <?php if($crs==$cidd) { ?>selected="selected" <?php } ?> value="<?php echo $cidd; ?>"><?php echo esc_html( $usc ); ?></option>
                            <?php
                          }
                          echo '</select>';
                        }
                      // Restore original Post Data.
                  ?>
              </li>
              <?php } ?>
              <?php } else { ?>
              <li class="single_csect">
                <h4 class="acc_title"><i class="fas fa-arrows-alt"></i>Learning Path 1 <a data-index="1" class="act_link2" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i></h4>
                <div class="acc-content">
                  <?php /*
                <div class="ed_single">
                      <label>Section Title</label>
                      <input type="text" name="sect_title[]" value="Learning Path 1" class="sct_ttl"/>
                </div> */ ?>
                <input type="text" name="sect_title[]" value="Learning Path 1" class="sct_ttl"/>
                  <div class="ed_single">
                      <label>Select Course</label>
                      <?php
                        if(!empty($user_courses))
                        {
                          echo '<select name="learning_paths[]">
                          <option value=""></option>';
                          foreach($user_courses as $cidd=>$usc)
                          {
                            ?>
                            <option value="<?php echo $cidd; ?>"><?php echo esc_html( $usc ); ?></option>
                            <?php
                          }
                          echo '</select>';
                        }
                      // Restore original Post Data.
                  ?>
              </li>
              <?php } ?>
                </ul>
              <?php /*
              <div class="ed_single">
                  <label>Learning Units</label>
                  <input type="text" name="learning_units[]" value="">
              </div> */ ?>
            </div>
          </div>

        </section>

  <input type="hidden" name="submit_course" value="<?php echo $pid; ?>"/>
  <input type="submit" value="Submit">
  </div>
    </div>

    </form>
    <?php
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }


  public function mupfile($fd, $cd)
  {
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    $attachment_id = media_handle_upload( $fd, $cd );

    if ( is_wp_error( $attachment_id ) ) {
      return 0;
    } else {
      return $attachment_id;
    }
  }

  public function pd_ct()
  {
    $args = array(
        'post_type' => 'products',
        'post_status' => array('publish', 'draft'),
        'author' => get_current_user_id(),
        'posts_per_page' => -1,


    );

    $query = new WP_Query( $args );
    return $query->post_count;


    //return 1;
  }


  public function lw_enqueue_scripts() {
    wp_enqueue_script( 'custom-sortable', plugins_url( 'js/jquery-sortable.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'custom-dpick', plugins_url( 'js/flatpickr.min.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'custom-script', plugins_url( 'js/custom.js', __FILE__ ), array( 'jquery' ) );
    wp_localize_script( 'custom-script', 'ajx',
      array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'data_var_1' => 'value 1',
      )
    );
    wp_enqueue_style('dtepick', plugins_url( 'css/flatpickr.min.css', __FILE__ ), array(), '1.0.0', 'all' );
    wp_enqueue_style('customstyle', plugins_url( 'css/custom.css', __FILE__ ), array(), '1.0.0', 'all' );
  }


  public function backend_enqueue( $hook ) {
    $ap = array('post-new.php', 'post.php');
    if(in_array($hook, $ap))
    {
        wp_enqueue_script( 'custom-back', plugins_url( 'js/custom-back.js', __FILE__ ), array( 'jquery' ) );
    }
  }

  public function lw_get_course_data()
  {
      $cid = $_POST['course_id'];
      $oi = get_post($cid);
      $pthumb = get_post_thumbnail_id($cid);
      $pst = $_POST['post_cid'];
      //$att_url = wp_get_attachment_url($pthumb, 'thumbnail');
      if($oi)
      {
          $html = '<p class="hide-if-no-js"><a href="'.site_url().'/wp-admin/media-upload.php?post_id='.$pst.'&amp;type=image&amp;TB_iframe=1" id="set-post-thumbnail" aria-describedby="set-post-thumbnail-desc" class="thickbox">'.wp_get_attachment_image($pthumb, 'thumbnail').'</a>
              </p>
              <p class="hide-if-no-js howto" id="set-post-thumbnail-desc">Click the image to edit or update</p>
              <p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">Remove featured image</a></p><input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="'.$pthumb.'">';
          $data = array(
            'status' => true,
            'post_title' => $oi->post_title,
            'post_thumb' => $pthumb,
            'post_content' => $oi->post_content,
           // 'thumb_url' => wp_get_attachment_url($pthumb),
          //  'html' => $html
        );
        if(!empty($pthumb))
        {
          $data['html'] = $html;
        }
      }
      else
      {
          $data = array(
            'status' => false,
        );
      }

      wp_send_json($data);
  }
}

$cw = new Lw_Frontend_Dashboard();