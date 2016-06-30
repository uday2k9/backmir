<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Request to active account!</h2>
    <div>
      Dear Admin,
    </div>
    <div>
      <p>The following user wants to activate his/her account.</p>
      <p>Username: <strong>{!! $name !!}</strong></p>
      <p>Email: <strong>{!! $email !!}</p>      
      
    </div>
    <br/>
    <div>Thank you,<br/>
      The <a href="<?php echo url();?>" target="_blank" style="font-weight:700;color:#fff;text-decoration:none;">Miramix.com</a> Team
    </div>
  </body>
</html>