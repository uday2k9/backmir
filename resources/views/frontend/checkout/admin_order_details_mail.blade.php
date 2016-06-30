<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Order Mail</title>
</head>

<body>


<center>
  <style>
@import url('http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic');
</style>
  <table width="620" border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
      <td background="<?php echo url();?>/public/frontend/images/account/acct_back.jpg" style="background-repeat:no-repeat;background-position:0 0;background-size:cover;padding-left:30px;padding-top:30px;padding-right:30px;" bgcolor="#017a95"><table width="560" border="0" cellpadding="0" cellspacing="0" style="">
          <thead>
            <tr>
              <td align="center" colspan="2" style="padding-bottom:22px;"><a href="" target="_blank"><img src="<?php echo url();?>/public/frontend/images/logo.png" alt=""></a></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="top_part" height="70" background="<?php echo url();?>/public/frontend/images/top_image.png" style="background-repeat:no-repeat;background-size:cover;background-position:right 0;"></td>
            </tr>
            <tr>
              <td style="padding-left:30px;padding-right:30px;" bgcolor="#f8f8f8"><table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                  <tbody><tr>
                    <td align="center"><p style="font-size:20px;line-height:22px;color:#1588d1;font-family:'Lato', sans-serif;font-weight:700;display:block;text-align: left;margin:0;margin-bottom:20px;display:block;">Hello {!! $receiver_name !!},</p>
                      <p style="font-size:15px; line-height:20px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:400;margin:0;margin-bottom:15px;display:block;text-align:center">Order number #{!! $order_list[0]->order_number !!} is placed. Please check the order details in admin panel.</p>
                      <div style="font-size:16px;line-height:22px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:700;background:#fff;border:1px solid #1588d1;border-radius:25px;  display: inline-block;margin-bottom:30px;">
                        <p style="margin:0;margin-top:13px;margin-bottom:13px;margin-left:25px;margin-right:25px;"><span style="display: block;">Order Questions?</span>  
                         Email: <a href="mailto:{!! $admin_users_email !!}" style="font-weight:400;font-style:italic;color:#1588d1;text-decoration:none;">{!! $admin_users_email !!}</a></p>
                      </div>
                      </td>
                  </tr>
                  
                  
                </tbody></table></td>
            </tr>
            <tr>
              <td id="bot_part" background="<?php echo url();?>/public/frontend/images/bot_image.png" height="71" style="background-size:cover;background-position:right;background-repeat:no-repeat;"></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td align="center" style="font-size:18px;color:#ffffff;line-height:22px;font-weight:300;font-family:'Lato', sans-serif;padding-top:17px;padding-bottom:17px;">Thank you, <a href="<?php echo url();?>" target="_blank" style="font-weight:700;color:#fff;text-decoration:none;">Miramix.com</a></td>
            </tr>
          </tfoot>
        </table></td>
    </tr>
  </tbody></table>
</center>

</body>
</html>