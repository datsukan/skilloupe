<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  </head>
  <body>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
      <tr>
        <td align="center" valign="top" bgcolor="#fff" style="background-color: #fff;">
          <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
              <td align="center" valign="top">
                <table border="0" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 500px;">
                  <tr>
                    <td align="center" valign="top">
                      <div class="main" style="padding: 0.5em 1em; margin: 10px 0; background: #fff; border: solid 1px #cccccc; border-radius: 10px;">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <tr>
                            <td align="center" valign="top">
                              <h1 style="font-size: 1.5rem; font-weight: bold; color: #1976d2; margin: 15px 0;">Skilloupe</h1>
                            </td>
                          </tr>
                        </table>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <tr>
                            <td align="center" valign="top">
                              <h2 style="font-size: 1.5rem; font-weight: normal; color: #111111;">パスワードのリセット</h2>
                                <p>
                                  <a style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px">
                                    {{ $email }}
                                  </a>
                                </p>
                              <hr style="height: 0; margin: 0; padding: 0; border: 0; border-top: 1px solid #cccccc;">
                            </td>
                          </tr>
                        </table>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <tr>
                            <td align="left" valign="top">
                              <p class="main-text" style="font-size: 0.8rem; margin-top: 25px;">
                                このメールは、アカウントのパスワード再設定リクエストを受け取った方にお送りしています。<br>
                                このパスワードリセットリンクは60分で期限切れになります。<br>
                                パスワードのリセットが不要の場合、操作は必要ありません。
                              </p>
                            </td>
                          </tr>
                        </table>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <tr>
                            <td align="center" valign="top">
                              <a class="reset-button" href="{{ $reset_url }}" style="margin: 2rem 0; display: inline-block; background-color: #1976d2; color: #fff; font-size: 0.8rem; line-height: 1; text-decoration: none; padding: 0.8rem 1rem; border-radius: 5px; cursor: pointer; box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12),
          0 3px 1px -2px rgba(0, 0, 0, 0.2); -webkit-tap-highlight-color: transparent; transition: 0.3s ease-out;">パスワードをリセット</a>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <p class="help-text" style="font-size: 0.7rem; color: #999; margin: 10px 0;">© 2020 Skilloupe. All rights reserved.</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
