<?php

return [

//------------------------//
// SYSTEM SETTINGS
//------------------------//

    /**
     * Registration Needs Activation.
     *
     * If set to true users will have to activate their accounts using email account activation.
     */
    'rna' => true,

    /**
     * Login With Email.
     *
     * If set to true users will have to login using email/password combo.
     */
    'lwe' => true,

    /**
     * Force Strong Password.
     *
     * If set to true users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

//------------------------//
// EMAILS
//------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'sendmail4ram@gmail.com',

    /**
     * Not used in template.
     * You can set support email here.
     */
    'supportEmail' => 'sendmail4ram@gmail.com',
];
