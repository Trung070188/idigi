{{--<div class="card-body">--}}
{{--    <div class="d-flex flex-wrap gap-2 justify-content-between mb-8">--}}
{{--        <div class="d-flex align-items-center flex-wrap gap-2">--}}
{{--            <h2 class="fw-bold me-3 my-1">{{$subject}}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div data-kt-inbox-message="message_wrapper">--}}
{{--        <div class="collapse fade" data-kt-inbox-message="message">--}}
{{--            <div class="py-5">--}}
{{--                <p>Xin chào {{$content['full_name']}},</p>--}}
{{--                <p>Một tài khoản đã được tạo cho bạn tại: idigi.ismart.edu.vn--}}
{{--                    Vui lòng truy cập vào đường link này để đăng nhập với thông tin:</p>--}}
{{--                <p>Tên đăng nhập :{{$content['username']}}</p>--}}
{{--                <p class="mb-0">Mật khẩu :{{$content['password']}}</p>--}}
{{--                <p>Đây là email tự động, vui lòng không trả lời!</p>--}}
{{--                <p>Thanks and best regards!</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<!doctype html>
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Transactional Email</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800&display=swap' rel='stylesheet' type='text/css'>
    <!--<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <link href='https://fonts.googleapis.com/css?family=Baloo+Paaji&display=swap' rel='stylesheet' type='text/css'>-->
    <style>

        /* -------------------------------------
            INLINED WITH htmlemail.io/inline
        ------------------------------------- */
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 600px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }
            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }
            table[class=body] .content {
                padding: 0 !important;
            }
            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table[class=body] .btn table {
                width: 100% !important;
            }
            table[class=body] .btn a {
                width: 100% !important;
            }
            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }

            #MessageViewBody a:hover {
                /*background-color: #34495e !important;
                border-color: #34495e !important;*/

                text-decoration: underline;
            }
        }
    </style>
</head>
<body class="" style="background-color: white; font-family: 'Montserrat',sans-serif !important; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 26px; margin: 0; padding: 10px 0px 20px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #424242;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: white;">
    <tr>
        <td>&nbsp;</td>
        <td class="container" style="margin: 0 auto; max-width: 580px; padding: 0px; width: 580px; border-radius: 16px; background-color: #eee;padding: 2px 5px 8px;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; margin: 0; width: 100%;">
                <tbody>
                <tr>
                    <td style="width: 100%; background: white;border-radius: 14px 14px 0px 0px; padding: 0px 20px;">
                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; margin: 0;">
                            <tbody>
                            <!-- START MAIN CONTENT AREA -->
                            <tr>
                                <td class="wrapper" style="vertical-align: middle; box-sizing: border-box; width: 100%;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; margin: 0;width: 100%">
                                        <tbody>
                                        <tr>
                                            <td width="90px"><a href="https://online.ismart.edu.vn/" target="_blank" style="display: block; padding: 10px 0px 0px;"><img src="{{asset('/images/Logo_ismart.png')}}" height="90px"></a></td>
                                            <td align="right" class="head-title" style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 22px; font-weight: 600; padding: 0;padding-left: 20px;">iSmart Digital Lesson</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="box-sizing: border-box; width: 100%; height: 1px; border-top: dashed 3px #eee; margin: 0; padding-bottom: 20px;"></td>
                            </tr>
                            <tr>
                                <td align="center" style="box-sizing: border-box; width: 100%; vertical-align: middle; margin: 0;">
                                    <p style="margin: 0px; padding: 20px 0px 10px;"><img src="{{asset('/images/Logo_iDIGI1.png')}}" width="192px" style="display: inline-block; outline: none; border: none;margin:0;padding:0;" /></p>
                                </td>
                            </tr>
                            <tr><td style="height:20px;"></td></tr>
                            <tr>
                                <td align="center" style="box-sizing: border-box; width:100%;">
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" fill="true" stroke="false" style="width:540px;v-text-anchor:middle;" arcsize="30%" fillcolor="#f5f5f5">
                                        <v:textbox inset="15px,15px,15px,15px" style="width:100%">
                                            <center style="color:#424242;font-family:sans-serif;font-size:18px;font-weight:700;">Guide to access your account<br>
<span>(Hướng dẫn truy cập tài khoản)</span>
</center>
                                        </v:textbox>
                                    </v:roundrect>
                                    <![endif]-->
                                </td>
                            </tr>
                            <![if !mso]>
                            <tr>
                                <td align="center" style="box-sizing: border-box; border-radius: 16px;background-color: #f5f5f5;">
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 18px; font-weight: 700;margin:0;padding:15px; color: #424242;display: inline-block;border-radius: 16px;background-color: #f5f5f5;">Guide to access your account<br><span >(Hướng dẫn truy cập tài khoản)</span></p>

                                </td>
                            </tr>
                            <![endif]>
                            <tr><td style="height:20px;"></td></tr>
                            <tr>
                                <td style="box-sizing: border-box; width: 100%; padding: 20px 25px;border-radius: 16px; border: dashed 3px #eee;">
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 10px;">Hi <span style="font-weight: 700;">{{$content['full_name']}}</span>,
                                    <br>
                                        <span style="font-style:italic">Gửi anh/ chị</span> <span style="font-weight: 700;">{{$content['full_name']}}</span>
                                    </p>

                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">To take new password for your account, please check the following information:
                                    <br>
                                        <span style="font-style:italic">Để tạo mật khẩu mới cho tài khoản của bạn, vui lòng kiểm tra lại các thông tin dưới đây:</span>
                                    </p>

                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242;margin: 0; margin-left: 15px; margin-bottom: 5px;">- Username<span style="font-style: italic">(Tên đăng nhập)</span>: <span style="font-weight: 700;">{{$content['username']}}</span></p>
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242; margin: 0; margin-left: 15px; margin-bottom: 15px;">- Password<span style="font-style: italic">(Mật khẩu)</span>: <span style="font-weight: 700;">{{$content['password']}}</span></p>
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">Please click on the following button to access to iSMART-DIGI system.<br>
                                        <span style="font-style:italic">Sau khi kiểm tra xong thông tin, vui lòng nhấn vào đường dẫn bên dưới đây để truy cập vào hệ thống iSMART-DIGI.</span>
                                    </p>

                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 16px; font-weight: 500; color: #424242; margin: 0; margin-top: 10px; margin-bottom: 5px;">Best regards,<br>
                                        <span style="font-style:italic">Trân trọng,</span>
                                    </p>

                                </td>
                            </tr>
                            <tr><td style="height:20px;"></td></tr>
                            <tr>
                                <td align="center" style="margin: 0; padding: 0px; box-sizing: border-box; width: 100%;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; margin: 0;width: 100%">
                                        <tbody>
                                        <tr>
                                            <td align="center" style="box-sizing: border-box;width:100%;">
                                                <!--[if mso]>
                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://ninox.dreamlab.com.vn/" fill="true" stroke="false" style="width:540px;v-text-anchor:middle;" arcsize="30%" fillcolor="#e84e1c">
                                                    <w:anchorlock/>
                                                    <v:fill color2="#ff8760" type="gradient" />
                                                    <v:textbox inset="15px,15px,15px,15px" style="width:100%">
                                                        <center style="color:white;font-family:Arial,sans-serif;font-size:18px;font-weight:700;">Access Now</center>
                                                    </v:textbox>
                                                </v:roundrect>
                                                <![endif]-->
                                            </td>
                                        </tr>
                                        <![if !mso]>
                                        <tr>
                                            <td align="center" style="box-sizing: border-box;width: 100%; background-color: rgba(232, 78, 28, 0.2); border-radius: 16px; padding: 2px 3px 4px;">
                                                <a class="btn-primary" href="https://idigi.ismart.edu.vn/xadmin/login" target="_blank" style="display: block;color: #ffffff;background: linear-gradient(to top, #e84e1c, #ff8760);border: solid 2px white;border-radius: 14px;box-sizing: border-box;cursor: pointer;text-decoration: none;margin: 0;padding: 10px 20px; font-family: 'Montserrat',Arial,sans-serif;-webkit-font-smoothing: antialiased;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-size: 19px;font-weight: 600;color: white;">Access Now</a>
                                            </td>
                                        </tr>
                                        <![endif]>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr><td style="height:20px;"></td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="box-sizing:border-box;height:3px;border-bottom: dashed 3px #eee;background-color:#ffffff;"></td>
                </tr>
                <tr>
                    <td style="box-sizing:border-box;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F7f7f7; border-radius: 0px 0px 14px 14px; padding: 20px; box-sizing: border-box;">
                            <tbody>
                            <tr>
                                <td align="center" style="box-sizing: border-box;width:10%;vertical-align: middle; margin: 0;">
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 22px; font-weight: 700; text-transform: none; color: #424242; margin: 0px;">iSMART Education</p>
                                </td>
                            </tr>
                            <tr><td style="height:10px;"></td></tr>
                            <tr>
                                <td align="center" class="content-block" style="box-sizing: border-box;width:10%;vertical-align: middle; margin: 0; ">
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 14px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">E-mail: <a href="mailto:online@ismart.edu.vn" target="_blank" style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 14px; font-weight: 600; color: #424242; text-decoration: underline;">online@ismart.edu.vn</a></p>
                                    <p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 14px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">Address: <span style="font-weight: 600;">The 3rd floor, Lant Building, 60 Hai Ba Trung, Ben Nghe, District 1, Ho Chi Minh City, Vietnam</span></p>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                        <tbody>
                                        <tr>
                                            <td align="center" style="padding: 0px 10px;"><p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 14px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">Hanoi - Hotline:<br><span style="font-weight: 600;">0987 68 0402</span></p></td>
                                            <td align="center" style="padding: 0px 10px;"><p style="font-family: 'Montserrat',Arial,sans-serif; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 14px; font-weight: 500; color: #424242; margin: 0; margin-bottom: 5px;">HCM City - Hotline:<br><span style="font-weight: 600;">0901 456 913</span></p></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                <!-- END MAIN CONTENT AREA -->
            </table>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
