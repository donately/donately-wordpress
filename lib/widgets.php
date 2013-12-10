<?php
/**
 * Widgets
 */

class DNTLY_WIDGET extends WP_Widget 
{

    // constructor
    function dntly_widget() 
    {
        /* ... */
        parent::WP_Widget(false, $name = __('Donately Form', 'dntly_widget') );
    }

    // widget form creation
    function form( $instance )
    {  
        /* ... */
        // Check values
        if( $instance ) {
             $title          = esc_attr($instance['title'] );
             $text           = esc_attr($instance['text'] );
             $textarea       = esc_textarea($instance['textarea'] );
             $show_address   = esc_attr( $instance['show_address']);
             $show_phone     = esc_attr( $instance['show_phone']);
             $show_comments  = esc_attr( $instance['show_comments']);
             $show_onbehalf  = esc_attr( $instance['show_onbehalf']);
             $show_anonymous = esc_attr( $instance['show_anonymous']);
             $amount         = esc_attr($instance['amount'] );
             $ssl_true       = esc_attr( $instance['ssl_true']);
             $custom_css     = esc_attr($instance['custom_css'] );
             $embed_css      = esc_attr( $instance['embed_css']);
             $tracking_codes = esc_textarea($instance['tracking_codes'] );

        } else {
             $title          = '';
             $text           = '';
             $textarea       = '';
             $show_address   = '';
             $show_phone     = '';
             $show_comments  = '';
             $show_onbehalf  = '';
             $show_anonymous = '';
             $amount         = '';
             $ssl_true       = '';
             $custom_css     = '';
             $embed_css     = '';
             $tracking_codes     = '';
        }
        ?>
        
        <p><strong>Basic Settings</strong></p>
        <p>These settings will override the ones set in your settings panel. They are all optional.</p>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Form Title', 'dntly'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'dntly'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'dntly'); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('show_address'); ?>" name="<?php echo $this->get_field_name('show_address'); ?>" type="checkbox" value="1" <?php checked( '1', $show_address ); ?> />
        <label for="<?php echo $this->get_field_id('show_address'); ?>"><?php _e('Show address field.', 'dntly'); ?></label>
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('show_phone'); ?>" name="<?php echo $this->get_field_name('show_phone'); ?>" type="checkbox" value="1" <?php checked( '1', $show_phone ); ?> />
        <label for="<?php echo $this->get_field_id('show_phone'); ?>"><?php _e('Show phone number field.', 'dntly'); ?></label>
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" type="checkbox" value="1" <?php checked( '1', $show_comments ); ?> />
        <label for="<?php echo $this->get_field_id('show_comments'); ?>"><?php _e('Show comments field.', 'dntly'); ?></label>
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('show_onbehalf'); ?>" name="<?php echo $this->get_field_name('show_onbehalf'); ?>" type="checkbox" value="1" <?php checked( '1', $show_onbehalf ); ?> />
        <label for="<?php echo $this->get_field_id('show_onbehalf'); ?>"><?php _e('Show "on-behalf-of" field.', 'dntly'); ?></label>
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('show_anonymous'); ?>" name="<?php echo $this->get_field_name('show_anonymous'); ?>" type="checkbox" value="1" <?php checked( '1', $show_anonymous ); ?> />
        <label for="<?php echo $this->get_field_id('show_anonymous'); ?>"><?php _e('Show anonymous field.', 'dntly'); ?></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('amount'); ?>"><?php _e('Default Amount:', 'dntly'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="text" value="<?php echo $amount; ?>" />
        </p>
        
        <p><strong>Advanced Settings</strong></p>

        <p>
        <input id="<?php echo $this->get_field_id('ssl_true'); ?>" name="<?php echo $this->get_field_name('ssl_true'); ?>" type="checkbox" value="1" <?php checked( '1', $ssl_true ); ?> />
        <label for="<?php echo $this->get_field_id('ssl_true'); ?>"><?php _e('Check if you have an SSL cert', 'dntly'); ?></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('custom_css'); ?>"><?php _e('Custom CSS URL:', 'dntly'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('custom_css'); ?>" name="<?php echo $this->get_field_name('custom_css'); ?>" type="text" value="<?php echo $custom_css; ?>" />
        </p>

        <p>
        <input id="<?php echo $this->get_field_id('embed_css'); ?>" name="<?php echo $this->get_field_name('embed_css'); ?>" type="checkbox" value="1" <?php checked( '1', $embed_css ); ?> />
        <label for="<?php echo $this->get_field_id('embed_css'); ?>"><?php _e('Embed your css (recommended)', 'dntly'); ?></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id('tracking_codes'); ?>"><?php _e('Tracking Codes:', 'dntly'); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id('tracking_codes'); ?>" name="<?php echo $this->get_field_name('tracking_codes'); ?>"><?php echo $tracking_codes; ?></textarea>
        </p>

    <?php
    }

    // widget update
    function update( $new_instance, $old_instance )
    {
        /* ... */
        $instance = $old_instance;
             // Fields
             $instance['title']          = strip_tags( $new_instance['title'] );
             $instance['text']           = strip_tags( $new_instance['text'] );
             $instance['textarea']       = strip_tags( $new_instance['textarea'] );
             $instance['show_address']   = strip_tags( $new_instance['show_address'] );
             $instance['show_phone']     = strip_tags( $new_instance['show_phone'] );
             $instance['show_comments']  = strip_tags( $new_instance['show_comments'] );
             $instance['show_onbehalf']  = strip_tags( $new_instance['show_onbehalf'] );
             $instance['show_anonymous'] = strip_tags( $new_instance['show_anonymous'] );
             $instance['amount']         = strip_tags( $new_instance['amount'] );
             $instance['ssl_true']       = strip_tags( $new_instance['ssl_true'] );
             $instance['custom_css']     = strip_tags( $new_instance['custom_css'] );
             $instance['embed_css']      = strip_tags( $new_instance['embed_css'] );
             $instance['tracking_codes'] = strip_tags( $new_instance['tracking_codes'] );

            return $instance;
    }

    // widget display
    function widget( $args, $instance ) {
        /* ... */
        global $dntly_settings;
        
        extract( $args );
        // these are the widget options
        $title          = apply_filters('widget_title', $instance['title']);
        $text           = $instance['text'];
        $textarea       = $instance['textarea'];
        $show_address   = $instance['show_address'];
        $show_phone     = $instance['show_phone'];
        $show_comments  = $instance['show_comments'];
        $show_onbehalf  = $instance['show_onbehalf'];
        $show_anonymous = $instance['show_anonymous'];
        $ssl_true       = $instance['ssl_true'];
        $amount         = $instance['amount'];
        $embed_css      = $instance['embed_css'];
        $custom_css     = $instance['custom_css'];
        $tracking_codes = $instance['tracking_codes'];

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text dntly_box">';

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
    
        //Set $show_address
        if( isset( $dntly_settings['donately_address'] ) ) {

            // If $dntly_settings is set but $args is not, use $dntly_settings
            $show_address = $dntly_settings['donately_address'];

        }elseif( isset( $instance['show_address'] ) && $instance != 0 ) {

            // If $args is set, this overrides $dntly_settings                   
            $show_address = $instance['show_address'];

        }else{
           
            // If neither are set, set it to false
            $show_address = false;
        }


        //Set $show_phone
        if( isset( $dntly_settings['donately_phone'] ) ) {

            // If $dntly_settings is set but $args is not, use $dntly_settings
            $show_phone = $dntly_settings['donately_phone'];

        }elseif( isset( $instance['show_phone'] ) && $instance != 0 ) {

            // If $args is set, this overrides $dntly_settings                   
            $show_phone = $instance['show_phone'];

        }else{
           
            // If neither are set, set it to false
            $show_phone = false;
        }


        //Set $show_comments
        if( isset( $dntly_settings['donately_comments'] ) ) {

            // If $dntly_settings is set but $args is not, use $dntly_settings
            $show_comments = $dntly_settings['donately_comments'];

        }elseif( isset( $instance['show_comments'] ) && $instance != 0 ) {

            // If $args is set, this overrides $dntly_settings                   
            $show_comments = $instance['show_comments'];

        }else{
           
            // If neither are set, set it to false
            $show_comments = false;
        }


        //Set $show_onbehalf
        if( isset( $dntly_settings['donately_onbehalf'] ) ) {

            // If $dntly_settings is set but $args is not, use $dntly_settings
            $show_onbehalf = $dntly_settings['donately_onbehalf'];

        }elseif( isset( $instance['show_onbehalf'] ) && $instance != 0 ) {

            // If $args is set, this overrides $dntly_settings                   
            $show_onbehalf = $instance['show_onbehalf'];

        }else{
           
            // If neither are set, set it to false
            $show_onbehalf = false;
        }


        //Set $show_anonymous
        if( isset( $dntly_settings['donately_anonymous'] ) ) {

            // If $dntly_settings is set but $args is not, use $dntly_settings
            $show_anonymous = $dntly_settings['donately_anonymous'];

        }elseif( isset( $instance['show_anonymous'] ) && $instance != 0 ) {

            // If $args is set, this overrides $dntly_settings                   
            $show_anonymous = $instance['show_anonymous'];

        }else{
           
            // If neither are set, set it to false
            $show_anonymous = false;
        }


        //Set $amount
        if( isset( $dntly_settings['donately_amount'] ) && !isset( $instance['amount'])) {
            //If $dntly_settings is set but $instance is not, use $dntly_settings
            $amount = $dntly_settings['donately_amount'];
        
        }elseif( !is_null( $instance['amount'] ) ) {
            // If $instance is set, this overrides $dntly_settings
            $amount = $instance['amount'];

        }else{
            //If neither are set, set it to false
            $amount = 0;
        }


        /**
         * Set up the arguments for donately_form();
         */
        $donately_args = array(
            'show_address'   => $show_address,
            'show_phone'     => $show_phone,
            'show_comments'  => $show_comments,
            'show_onbehalf'  => $show_onbehalf,
            'show_anonymous' => $show_anonymous,
            'amount'         => $amount,
        );

        donately_form( $donately_args );

        echo '</div>';
        echo $after_widget;
    }
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("dntly_widget");'));