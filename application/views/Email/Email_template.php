<tr>
    <td align="center" valign="top" id="templateBody" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #fff;border-top: 0;border-bottom: 0;">
        <table  align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;">
            <tr>
                <td valign="top" class="bodyContainer" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <table  style="background-color: #eeeeee !important;" border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                       <tbody class="mcnTextBlockOuter">
                            <tr>
                                <td valign="top" class="mcnTextBlockInner" style="padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <div valign="top" class="mcnTextBlockInner" style="padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                                    </div>
                                    <div class="mcnTextContent" style="padding: 0px 18px 9px;">
                                        <span style="color:#222222; font-weight: bold;">
											Hello <?php echo $name; ?>,
                                        </span>
                                        <br>
                                        <span style="text-decoration: none; color:#222222;">
                                         Click On Reset Password button to reset passowrd of your account
                                        </span><br><br>
                                        <span style="text-decoration: none; color:#222222;">
                                         <a href="<?php echo $passwordResetLink; ?>" target="_blank"><button class="reset_btn" style=" background-color: #0bb9b7; border: none; border-radius:10px; color: white; padding: 10px 35px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin-top:20px">Reset Password</button></a>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnCaptionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <tbody class="mcnCaptionBlockOuter"  style="background-color: #eeeeee !important;">
                            <tr>
                                <td class="mcnCaptionBlockInner" valign="top" style="padding: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnCaptionTopContent" width="false" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                        <tbody>
                                            <tr>
                                                <td class="mcnTextContent" valign="top" style="padding: 0 9px 0 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #FFFFFF;font-family: Helvetica;font-size: 16px;line-height: 150%;text-align: left;" width="564">
                                                    <span style="font-size:16px">
                          <span style="font-family:tahoma,verdana,segoe,sans-serif;color:#222222;">
                          Best Regards,
                          <br />
                        <strong><?php echo $this->config->item('company_name'); ?></strong></span></span>
                                                    <br />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="mcnCaptionTopImageContent" align="center" valign="top" style="padding: 9px 9px 0 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    <a href="<?php echo $this->config->item('site_name'); ?>"><img alt="" src="<?php echo $this->config->item('company_logo'); ?>" width="104" height="70" style="max-width: 64px;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;vertical-align: bottom;" class="mcnImage">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>