<?php
/**
 * User App - Change Password (duplicate path for user_profile convenience)
 * POST: /user_profile/change_password.php
 * Headers: Authorization: Bearer <token>
 * Body: current_password, new_password, confirm_password
 */
// Delegate to user_auth change_password
include "../user_auth/change_password.php";
?>
