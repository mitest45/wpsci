<?php
class wpsciEmailTemplate{
    
    public static function getTemplate($header, $message, $footer){    
        $message = '
            <body link="#00a5b5" vlink="#00a5b5" alink="#00a5b5">
        
            <table class="main" align="center" style="font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #555559;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
                <tr>
                    <td class="border" style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                        <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td align="center" colspan="4" valign="top" class="image-section" style="border-collapse: collapse;border: 0;margin: 0;padding: 30px 15px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #333;border-bottom: 2px solid #ccc">
                                    <h2 style="margin: 0;"><a href="#" style="color:#fff;text-decoration:none;">'.$header.'</a></h2>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
        
                                    <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                        <tr>
                                            <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;min-height: 400px;">
                                                <div style="min-height: 400px;"><br>
                                                    '.$message.'
                                                </div>
                                            </td>
                                        </tr>
        
                                        <tr bgcolor="#fff" style="border-top: 2px solid #ccc;">
                                            <td valign="top" class="footer" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                                                <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                                    <tr>
                                                        <td class="inside-footer" align="center" valign="middle" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 13px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                                        <div id="address" class="mktEditable">
                                                            <p>
                                                            '.$footer.'
                                                            </p>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                </table>
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
        ';
        return $message;
    }
}
