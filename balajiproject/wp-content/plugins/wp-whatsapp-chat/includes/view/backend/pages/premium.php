<div class="wrap about-wrap full-width-layout qlwrap">
    <div class="has-2-columns is-wider-left" style="max-width: 100%">
        <div class="column">
            <div class="welcome-header">
                <h1><?php esc_html_e('Premium', 'wp-whatsapp-chat'); ?></h1>
                <div class="about-description">
                    <?php printf(esc_html__('Unlock the power of our premium %s plugin which allows you to include unlimited agent accounts with their names and labels inside the box to provide direct contact to the different support areas of your site.', 'wp-whatsapp-chat'), QLWAPP_PLUGIN_NAME); ?>
                </div>
                <br/>
                <a style="background-color: #006cff;color: #ffffff;text-decoration: none;padding: 10px 30px;border-radius: 30px;margin: 10px 0 0 0;display: inline-block;" target="_blank" href="<?php echo esc_url(QLWAPP_PURCHASE_URL); ?>"><?php esc_html_e('Purchase Now', 'wp-whatsapp-chat'); ?></a>
                <a style="background-color: #ffffff;color: #626262;text-decoration: none;padding: 10px 30px;border-radius: 30px;margin: 10px 0 0 0;display: inline-block;" target="_blank" href="<?php echo esc_url(QLWAPP_SUPPORT_URL); ?>"><?php esc_html_e('Get Support', 'wp-whatsapp-chat'); ?></a>
            </div>
            <hr/>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Multiple agents', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php printf(esc_html__('%s allows you to include unlimited agent accounts with their names and labels inside the box to provide direct contact to the different support areas of your site.', 'wp-whatsapp-chat'), QLWAPP_PLUGIN_NAME); ?>
                </p>
            </div>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Customize colors', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php esc_html_e('Customize the colors to match your site theme through the WordPress live customizer interface.', 'wp-whatsapp-chat'); ?>
                </p>
            </div>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Custom icons', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php esc_html_e('Our plugin allows you to select between more than forty icons to include in your Whatsapp button.', 'wp-whatsapp-chat'); ?>
                </p>
            </div>
        </div>
        <div class="column">
            <img alt="<?php esc_html_e('Premium', 'wp-whatsapp-chat'); ?>" src="<?php echo plugins_url('/assets/backend/img/box1.png', QLWAPP_PLUGIN_FILE); ?>">
        </div>
    </div>
    <br/>
    <br/>
    <div class="has-2-columns is-wider-right" style="max-width: 100%">
        <div class="column">
            <img alt="<?php esc_html_e('Chatbox interface', 'wp-whatsapp-chat'); ?>" src="<?php echo plugins_url('/assets/backend/img/box2.png', QLWAPP_PLUGIN_FILE); ?>">
        </div>
        <div class="column">
            <br/>
            <div class="welcome-header">
                <h1><?php esc_html_e('Chatbox interface', 'wp-whatsapp-chat'); ?></h1>
                <div class="about-description">
                    <?php printf(esc_html__('%s for WordPress allows you to include a chatbox for each agent where your users can type their first message.', 'wp-whatsapp-chat'), QLWAPP_PLUGIN_NAME); ?>
                    <?php //esc_html_e('Take in mind that this chat dosent allow .', 'wp-whatsapp-chat'); ?>
                </div>
            </div>
            <hr/>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Custom agent message', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php esc_html_e('Allow you to set a custom message for each agent that will be displayed on the chatbox.', 'wp-whatsapp-chat'); ?>
                </p>
            </div>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Custom user message', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php esc_html_e('You can choose the predefined user message that will be sent to the agent phone number.', 'wp-whatsapp-chat'); ?>
                </p>
            </div>
            <div class="feature-section" style="margin: 15px 0;">
                <h3><?php esc_html_e('Type user message', 'wp-whatsapp-chat'); ?></h3>
                <p>
                    <?php esc_html_e('Allow your users to type their own messages before sending it to the agent phone number.', 'wp-whatsapp-chat'); ?>
                </p>
            </div>
        </div>
    </div
</div>