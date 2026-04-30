<?php
/**
 * Gaza Order - Email Service
 * Uses PHP mail() function with SMTP settings
 * NO COMPOSER/PHPMAILER REQUIRED!
 */

/**
 * Send OTP Email using PHP mail()
 * @param string $toEmail Recipient email
 * @param string $toName Recipient name
 * @param string $otp 6-digit OTP code
 * @param string $type 'signup' or 'reset_password'
 * @return bool Success status
 */
function sendOTPEmail($toEmail, $toName, $otp, $type = 'signup') {
    $from_email = SMTP_FROM_EMAIL;
    $from_name = SMTP_FROM_NAME;
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $from_name <$from_email>" . "\r\n";
    $headers .= "Reply-To: $from_email" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    if ($type === 'signup') {
        $subject = "Your verification code is: $otp - Order Gaza";
        $message = getSignupEmailTemplate($toName, $otp);
    } else {
        $subject = "Your reset code is: $otp - Order Gaza";
        $message = getResetPasswordEmailTemplate($toName, $otp);
    }
    
    ini_set('SMTP', SMTP_HOST);
    ini_set('smtp_port', SMTP_PORT);
    ini_set('sendmail_from', $from_email);
    
    try {
        $result = mail($toEmail, $subject, $message, $headers);
        
        if ($result) {
            error_log("Email sent successfully to: $toEmail");
            return true;
        } else {
            error_log("Failed to send email to: $toEmail");
            return false;
        }
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Signup Email Template
 */
function getSignupEmailTemplate($name, $otp) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #FF6B35 0%, #F7C948 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .otp-box { background: white; border: 2px dashed #FF6B35; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
            .otp-code { font-size: 32px; font-weight: bold; color: #FF6B35; letter-spacing: 8px; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div style='display:none;font-size:1px;color:#f9f9f9;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;'>Your verification code is: $otp - Enter this code to verify your email</div>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to Order Gaza!</h1>
            </div>
            <div class='content'>
                <h2>Hello $name,</h2>
                <p>Thank you for joining Order Gaza! We're excited to have you as part of our food delivery community in Gaza.</p>
                
                <p>To complete your registration, please verify your email address using the code below:</p>
                
                <div class='otp-box'>
                    <p style='margin: 0; color: #666;'>Your Verification Code:</p>
                    <div class='otp-code'>$otp</div>
                    <p style='margin: 10px 0 0 0; color: #999; font-size: 14px;'>This code expires in 30 minutes</p>
                </div>
                
                <p><strong>Important:</strong></p>
                <ul>
                    <li>Enter this code in the app to verify your email</li>
                    <li>Do not share this code with anyone</li>
                    <li>This code is valid for 30 minutes only</li>
                </ul>
                
                <p>If you didn't create an account with Order Gaza, please ignore this email.</p>
                
                <p>Best regards,<br>The Order Gaza Team</p>
            </div>
            <div class='footer'>
                <p>&copy; 2026 Order Gaza. All rights reserved.</p>
                <p>This is an automated email, please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
    ";
}

/**
 * Reset Password Email Template
 */
function getResetPasswordEmailTemplate($name, $otp) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #F7C948 0%, #FF6B35 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .otp-box { background: white; border: 2px dashed #FF6B35; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
            .otp-code { font-size: 32px; font-weight: bold; color: #FF6B35; letter-spacing: 8px; }
            .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div style='display:none;font-size:1px;color:#f9f9f9;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;'>Your reset code is: $otp - Enter this code to reset your password</div>
        <div class='container'>
            <div class='header'>
                <h1>🔒 Password Reset Request</h1>
            </div>
            <div class='content'>
                <h2>Hello $name,</h2>
                <p>We received a request to reset your password for your Order Gaza account.</p>
                
                <p>Use the verification code below to reset your password:</p>
                
                <div class='otp-box'>
                    <p style='margin: 0; color: #666;'>Your Reset Code:</p>
                    <div class='otp-code'>$otp</div>
                    <p style='margin: 10px 0 0 0; color: #999; font-size: 14px;'>This code expires in 30 minutes</p>
                </div>
                
                <div class='warning'>
                    <strong>⚠️ Security Alert:</strong>
                    <ul style='margin: 5px 0;'>
                        <li>If you didn't request this, please ignore this email</li>
                        <li>Never share this code with anyone</li>
                        <li>Order Gaza staff will never ask for this code</li>
                    </ul>
                </div>
                
                <p>After verifying this code, you'll be able to set a new password for your account.</p>
                
                <p>Best regards,<br>The Order Gaza Team</p>
            </div>
            <div class='footer'>
                <p>&copy; 2026 Order Gaza. All rights reserved.</p>
                <p>If you need help, contact us at order@appforyou.store</p>
            </div>
        </div>
    </body>
    </html>
    ";
}

/**
 * Test email configuration
 * @return array Status and message
 */
function testEmailConfiguration() {
    $testEmail = 'order@appforyou.store';
    $testOTP = '123456';
    
    $result = sendOTPEmail($testEmail, 'Test User', $testOTP, 'signup');
    
    return [
        'success' => $result,
        'message' => $result ? 'Email configuration is working!' : 'Email configuration failed. Check error logs.'
    ];
}
?>