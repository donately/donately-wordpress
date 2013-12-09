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
             $embedd_css     = '';
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
        <input id="<?php echo $this->get_field_id('embedd_css'); ?>" name="<?php echo $this->get_field_name('embedd_css'); ?>" type="checkbox" value="1" <?php checked( '1', $embedd_css ); ?> />
        <label for="<?php echo $this->get_field_id('embedd_css'); ?>"><?php _e('Embed your css (recommended)', 'dntly'); ?></label>
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
        extract( $args );
        // these are the widget options
        $title      = apply_filters('widget_title', $instance['title']);
        $text       = $instance['text'];
        $textarea   = $instance['textarea'];
        $amount     = $instance['amount'];
        $custom_css = $instance['custom_css'];
        $tracking_codes   = $instance['tracking_codes'];

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text dntly_box">';

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Check if text is set
        if( $text ) {
            echo '<p class="dntly_text">'.$text.'</p>';
        }
        // Check if textarea is set
        if( $textarea ) {
            echo '<p class="dntly_textarea">'.$textarea.'</p>';
        }

        // Check if show_address is checked
        if( $show_address AND $show_address == '1' ) {
            echo '<p>'.__('Show address is checked', 'dntly').'</p>';
        }

        // Check if show_phone is checked
        if( $show_phone AND $show_phone == '1' ) {
            echo '<p>'.__('Show phone is checked', 'dntly').'</p>';
        }

        // Check if show_comments is checked
        if( $show_comments AND $show_comments == '1' ) {
            echo '<p>'.__('Show comments is checked', 'dntly').'</p>';
        }

        // Check if show_onbehalf is checked
        if( $show_onbehalf AND $show_onbehalf == '1' ) {
            echo '<p>'.__('Show "on-behalf-of" field is checked', 'dntly').'</p>';
        }

        // Check if show_anoynmous is checked
        if( $show_anoynmous AND $show_anoynmous == '1' ) {
            echo '<p>'.__('Show anonymous field is checked', 'dntly').'</p>';
        }

        // Check if amount is set
        if( $amount ) {
            echo '<p class="dntly_amount">'.$amount.'</p>';
        }

        // Check if ssl_true is checked
        if( $ssl_true AND $ssl_true == '1' ) {
            echo '<p>'.__('SSL field is checked', 'dntly').'</p>';
        }

        // Check if custom_css is set
        if( $custom_css ) {
            echo '<p class="dntly_custom_css">'.$custom_css.'</p>';
        }

        // Check if embed_css is checked
        if( $embed_css AND $embed_css == '1' ) {
            echo '<p>'.__('Embed CSS field is checked', 'dntly').'</p>';
        }

        // Check if textarea is set
        if( $tracking_codes ) {
            echo '<p class="dntly_tracking_codes">'.$tracking_codes.'</p>';
        }

        echo '</div>';
        echo $after_widget;
    }
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("dntly_widget");'));