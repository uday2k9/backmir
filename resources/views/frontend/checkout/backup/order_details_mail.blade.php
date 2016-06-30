<?php 
$shipping_address = unserialize($order_list[0]->shiping_address_serialize);

?>
<!doctype html>
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta charset="utf-8">
<!--<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>-->
<title>Miramix Order Mail</title>

<!--[if gte mso 9]>
    <style type="text/css">
     #top_part{
     background:#f8f8f8;
     }
     #bot_part{
     background:#f8f8f8;
     }
    </style>
<![endif]-->
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
                    <td align="center"><p style="font-size:20px;line-height:22px;color:#1588d1;font-family:'Lato', sans-serif;font-weight:700;display:block;text-align:center;margin:0;margin-bottom:20px;display:block;">Thank you for your order from miramix store.</p>
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

{!! (isset($receiver_name) && ($receiver_name!=''))?ucwords($receiver_name):'' !!}<br>
{!! (isset($shipping_address['address']) && ($shipping_address['address']!=''))?$shipping_address['address']:'' !!}<br>
{!! (isset($shipping_address['address2']) && ($shipping_address['address2']!=''))?$shipping_address['address2']:'' !!}<br>
{!! (isset($shipping_address['country_id']) && ($shipping_address['country_id']!=''))?$shipping_address['country_id']:'' !!}<br>
T: {!! (isset($shipping_address['phone']) && ($shipping_address['phone']!=''))?$shipping_address['phone']:'' !!}</td>
                            
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  <tr>
                    <td><table style="border-collapse: collapse; width: 100%; border-top: 1px solid #dddddd; border-left: 1px solid #dddddd; margin-bottom: 20px;font-family: 'Lato', sans-serif;">
                        <thead>
                          <tr>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222">Product</td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222">Brand</td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; background-color: #efefef; font-weight: bold; text-align: right; padding: 7px; color: #222222">Quantity</td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; background-color: #efefef; font-weight: bold; text-align: right; padding: 7px; color: #222222">Price</td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; background-color: #efefef; font-weight: bold; text-align: right; padding: 7px; color: #222222">Total</td>
                          </tr>
                        </thead>
                        <tbody>
                <?php 
                if(!empty($order_list))
                {
                  foreach($order_list as $each_order) 
                  {
                ?>
                  <tr>
                    <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 7px">{!! $each_order->product_name !!}</td>
                    <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 7px">{!! $each_order->brand_name !!}</td>
                    <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">{!! $each_order->quantity !!}</td>
                    <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">${!! number_format(($each_order->price),2) !!}</td>
                    <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">${!! number_format((($each_order->price) * ($each_order->quantity)),2) !!}</td>
                  </tr>
                <?php 
                  } 
                }
                ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px" colspan="4"><b>Sub-Total:</b></td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">${!! number_format(($order_list[0]->sub_total),2) !!}</td>
                          </tr>
                          <tr>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px" colspan="4"><b>Flat Shipping Rate:</b></td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">${!! number_format(($order_list[0]->shipping_cost),2) !!}</td>
                          </tr>
                          <tr>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px" colspan="4"><b>Total:</b></td>
                            <td style="font-size: 12px; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 7px">${!! number_format(($order_list[0]->order_total),2) !!}</td>
                          </tr>
                        </tfoot>
                      </table></td>
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