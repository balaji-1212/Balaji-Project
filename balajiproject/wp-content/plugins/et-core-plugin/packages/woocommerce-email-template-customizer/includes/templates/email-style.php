<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
    body {
    padding: 0;
    }

    #wrapper {
    -webkit-text-size-adjust: none !important;
    width: 100%;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    min-width:480px;
    margin:auto;
    box-sizing:border-box;
    padding:20px 0;
    }

    #body_content {
    background-color: inherit;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    padding: 70px 0;
    }

    #body_content p {
    margin: 0 !important;
    line-height: inherit !important;
    font-family:Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;
    font-size: inherit;
    }

    #body_content table {
    vertical-align: middle;
    border-collapse: separate;
    border-spacing: 0;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
    }

    #body_content table td{
    margin:0;
    font-family:Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;
    }

    #body_content div{
    font-family: inherit;
    }

    #body_content span{
    vertical-align: middle;
    font-family:Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;
    }
    #body_content strong{
    font-family:Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;
    }

    #body_content ins{
    text-decoration:none;
    }

    #body_content img{
    border: 0;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
    vertical-align: middle;
    }

    h1 {
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    }

    h2 {
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 0 0 18px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    }

    h3 {
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    }
    .woocommerce-Price-currencySymbol{
    vertical-align:top !important;
    }

    .viwec-inline-block{
    font-size:0 !important;
    }

    div.viwec-responsive{
    display:inline-block;
    }
<?php
