<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>    
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Youthhub</title>
  </head>
  <body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
    <div class="Email_template" style="background: #f1f4f5; width: 640px; margin: 0 auto; padding: 8px 0;">
      <!------------------------------------ ---- HEADER -------------------------- ------------------------------------->
      <table class="head-wrap" bgcolor="#f1f4f5" style="width: 100%; border-spacing: 0;">
        <tr>
          <td class="header container" style="display: block!important; max-width: 600px!important; margin: 0 auto!important; clear: both!important; padding:0;">
            <div class="content" style="max-width: 600px; margin: 0 auto; display: block;">
              <table bgcolor="#ffffff" style="width: 100%; border-spacing: 0;">
                <tr>
                  <td align="center" style="padding:0;">	
                    <img class="logo_div" src="https://youthhub.co.nz/assets-new/img/email_logo.png" style="margin: 30px auto; width: 210px;">
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
      <!------------------------------------ ---- BODY -------------------------- ------------------------------------->
      <table class="body-wrap" style="width: 100%; border-spacing: 0;">
        <tr>
          <td class="container" bgcolor="#FFFFFF" style="display: block!important; max-width: 600px!important; margin: 0 auto!important; clear: both!important; padding: 0;">
            <!-- content -->
            <div class="content"  style="max-width: 600px; margin: 0 auto; display: block;">
              <table bgcolor="" class="social" width="100%"  style="width: 100%; border-spacing: 0;">
                <tr>
                  <td align="center">					
                    <img class="header_div" src="https://youthhub.co.nz/assets-new/img/header_img.png" style="width: 100%;">					
                  </td>
                </tr>
              </table>
            </div>
            <!-- COLUMN WRAP -->
            <div class="content" style="max-width: 600px; margin: 0 auto; display: block;">
              <table  style="width: 100%; border-spacing: 0; padding: 0 50px;">
                <tr>
                  <td align="left">
                    <h2 class="welcme_user" style="font-weight: 200;font-size:36px; margin: 50px 0; color: #333;">Hello Admin,
                    </h2>
                    <p class="welcome_description" style="margin-bottom: 15px; line-height: 1.6; color: #333; font-size: 15px;">
                      <b>
                        <?=htmlentities($report);?>
                      </b> - Report By 
                      <?=htmlentities($SESS_NAME);?> (
                      <?=htmlentities($SESS_TYPE_NAME);?>)
                    </p>
                    <p class="welcome_description" style="margin-bottom: 15px; line-height: 1.6; color: #333; font-size: 15px;">
                      <a href="<?=base_url();?>post/<?=htmlentities($pm_code);?>">Click here
                      </a> to View the Post
                    </p>
                    <div class="border_bottom" style="border-bottom: 1px solid #eee; margin: 30px auto;">
                    </div>
                    <!-- /hero -->
                  </td>
                </tr>
              </table>
            </div>
          </td>
          <td>
          </td>
        </tr>
      </table>
      <!-- FOOTER -->
      <table class="footer-wrap" style=" width: 100%; background-color: #f1f4f5; height: 50px;">
        <tr>
          <td>
          </td>
          <td class="container" style="display: block!important; max-width: 600px!important; margin: 0 auto!important; clear: both!important;">
            <!-- content -->
            <div class="content" style="max-width: 600px; margin: 0 auto; display: block;">
              <table  style="width: 100%; border-spacing: 0;">
                <tr>
                  <td class="footer_div" align="center" style="padding-top: 70px;">
                    <p style="color: #aaa; font-size:14px;">Youth Hub 
                    </p>
                    <p style="color: #aaa!important; font-size:14px; text-decoration:none!important;text-underline:none!important;">
                      <a href="javascript:void(0);" style="color: #aaa!important; font-size:14px; text-decoration:none!important;text-underline:none!important;">20 Beaumont Street, Auckland Central 1010
                      </a>
                    </p>
                  </td>
                </tr>
              </table>
            </div>
            <!-- /content -->
          </td>
          <td>
          </td>
        </tr>
      </table>
      <!-- /FOOTER -->
    </div>
  </body>
</html>