<?php 
use App\Helper\helpers;
$obj = new helpers();
$shipping_address = unserialize($order_list[0]->shiping_address_serialize);
?>
<!doctype html>
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta charset="utf-8">
<title>Miramix Order Mail</title>

</head>

<body>
<center>
  <style>
@import url('http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic');
</style>
  <table width="620" border="0" cellpadding="0" cellspacing="0">
    <tr>
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
                  <tr>
                    <td align="center"><p style="font-size:20px;line-height:22px;color:#1588d1;font-family:'Lato', sans-serif;font-weight:700;display:block;text-align:center;margin:0;margin-bottom:20px;display:block;">Thank you for your order from The Miramix Store.</p>
                      <p style="font-size:15px; line-height:20px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:400;margin:0;margin-bottom:15px;display:block;text-align:center">Once your package ships we will send an email with a link to track your order. Your order summary is below. Thank you again for your business.</p>
                      <div style="font-size:16px;line-height:22px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:700;background:#fff;border:1px solid #1588d1;border-radius:25px;  display: inline-block;margin-bottom:30px;">
                        <p style="margin:0;margin-top:13px;margin-bottom:13px;margin-left:25px;margin-right:25px;">Order Questions?   Email: <a href="mailto:{!! $admin_users_email !!}" style="font-weight:400;font-style:italic;color:#1588d1;text-decoration:none;">{!! $admin_users_email !!}</a></p>
                      </div>
                      <p style="font-size:14px;line-height:22px;color:#4e4e4e;font-family:'Lato', sans-serif;margin:0;margin-bottom:30px;"><span style="font-weight:700;">Your order #{!! $order_list[0]->order_number !!}</span><br>
                        Placed on {!! date('m d,Y g:i:s A e ',strtotime($order_list[0]->created_at)) !!}
                        </p></td> <!-- July 14, 2015 7:20:48 AM GMT -->
                  </tr>
                  <tr>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="width:100%;margin-bottom:30px;">
                        <tbody>
                          <tr>
                            
                            <th align="left" scope="col" style="font-size:16px;line-height:20px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:700;padding-bottom:10px;">Shipping Address :</th>
                          </tr>
                          <tr>
                            <td style="font-size:15px;line-height:22px;color:#4e4e4e;font-family:'Lato', sans-serif;">
                            <?php 
                              if((isset($shipping_address['zone_id'])))
                              {
                                if(is_numeric($shipping_address['zone_id']))
                                {
                                  $state = $obj->get_state($shipping_address['zone_id']);
                                }
                                else
                                {
                                  $state = $shipping_address['zone_id'];
                                }
                              }

                              if((isset($shipping_address['country_id'])))
                              {
                                if(is_numeric($shipping_address['country_id']))
                                {
                                  $country = $obj->get_country($shipping_address['country_id']);
                                }
                                else
                                {
                                  $country = $shipping_address['country_id'];
                                }
                              }
                            ?>

                            {!! (isset($receiver_name) && ($receiver_name!=''))?ucwords($receiver_name):'' !!}<br>
                            {!! (isset($shipping_address['address']) && ($shipping_address['address']!=''))?$shipping_address['address']:'' !!}<br>
                            {!! (isset($shipping_address['address2']) && ($shipping_address['address2']!=''))?$shipping_address['address2'].'<br>':'' !!}
                            {!! (isset($shipping_address['city']) && ($shipping_address['city']!=''))?$shipping_address['city']:'' !!}<br>
                            {!! (isset($shipping_address['zone_id']) && ($shipping_address['zone_id']!=''))?$state.',':'' !!}
                            {!! (isset($shipping_address['country_id']) && ($shipping_address['country_id']!=''))?$country:'' !!}<br>
                            {!! (isset($shipping_address['postcode']) && ($shipping_address['postcode']!=''))?$shipping_address['postcode']:'' !!}<br>
                            T: {!! (isset($shipping_address['phone']) && ($shipping_address['phone']!=''))?$shipping_address['phone']:'' !!}
                          
                            </td>                          
                            
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  <tr>
                    <td align="center"><p style="font-size:20px;line-height:22px;color:#1588d1;font-family:'Lato', sans-serif;font-weight:700;display:block;text-align: left;margin:0;margin-bottom:20px;display:block;">Hello {!! $receiver_name !!},</p>
                      <p style="font-size:15px; line-height:20px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:400;margin:0;margin-bottom:15px;display:block;text-align:center">You have received a new offer from Miramix Admin for order number #{!! $order_list[0]->order_number !!}. Please login to your account in brand admin panel.</p>
                      <div style="font-size:16px;line-height:22px;color:#4e4e4e;font-family:'Lato', sans-serif;font-weight:700;background:#fff;border:1px solid #1588d1;border-radius:25px;  display: inline-block;margin-bottom:30px;">
                        <p style="margin:0;margin-top:13px;margin-bottom:13px;margin-left:25px;margin-right:25px;"><span style="display: block;">Order Questions?</span>  
                         Email: <a href="mailto:{!! $admin_users_email !!}" style="font-weight:400;font-style:italic;color:#1588d1;text-decoration:none;">{!! $admin_users_email !!}</a></p>
                      </div>
                    </td>

                  </tr>
                </table></td>
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
  </table>
</center>
</body>
</html>