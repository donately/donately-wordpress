<?php

// Add the Meta Box
function dntly_add_custom_meta_box() {
    global $post;
    add_meta_box(
        'dntly_custom_meta_box', // $id
        'Donately - '. get_the_title( $post->ID) . ' ' . dntly_campaigns_get_label_plural() .' Information', // $title 
        'dntly_show_custom_meta_box', // $callback
        'dntly_campaigns', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'dntly_add_custom_meta_box');

// Field Array
$prefix = 'dntly_';
global $dntly_custom_meta_fields, $dntly_settings;

$dntly_custom_meta_fields = array(
    array(
        'label' => 'Donately Account ID',
        'desc'  => 'This campaign is associated with this account ID.<em><strong>You cannot change this.</strong></em>',
        'id'    => $prefix.'account_id',
        'type'  => 'disabled'
    ),
    array(
        'label' => 'Donately Campaign ID',
        'desc'  => 'This is your campaign  ID.<em><strong>You cannot change this.</strong></em>',
        'id'    => $prefix.'campaign_id',
        'type'  => 'disabled'
    ),
    array(
        'label' => 'Campaign Goal',
        'desc'  => 'This is your campaign goal. <em><strong>You can change this on <a href="http://'. $dntly_settings["donately_subdomain"] .'.dntly.com">Donately</a>.</strong></em>',
        'id'    => $prefix.'campaign_goal',
        'type'  => 'disabled_dollar'
    ),
    array(
        'label' => 'Amount Raised',
        'desc'  => 'This is the amount your campaign has raised.<strong>You cannot change this.</strong>',
        'id'    => $prefix.'amount_raised',
        'type'  => 'disabled_dollar'
    ),
    array(
        'label' => 'Percent Funded',
        'desc'  => 'This is the percentage you have raised.<strong>You cannot change this.</strong>',
        'id'    => $prefix.'percent_funded',
        'type'  => 'disabled_percent'
    ),
    array(
        'label' => 'Donations Count',
        'desc'  => 'This is the total number of donations you\'ve received.<em><strong>You cannot change this.</strong></em>',
        'id'    => $prefix.'donations_count',
        'type'  => 'disabled'
    ),
    array(
        'label' => 'Number of Donors',
        'desc'  => 'This is the total number of people who have donated to this campaign.<em><strong>You cannot change this.</strong></em>',
        'id'    => $prefix.'donors_count',
        'type'  => 'disabled'
    ),
    /*  array(
            'label' => 'Text Input',
            'desc'  => 'A description for the field.',
            'id'    => $prefix.'text',
            'type'  => 'text'
        ),
        array(
            'label' => 'Textarea',
            'desc'  => 'A description for the field.',
            'id'    => $prefix.'textarea',
            'type'  => 'textarea'
        ),

         array(
        'label' => 'Gender',
        'desc'  => 'Which gender is racing?',
        'id'    => $prefix.'select',
        'type'  => 'select',
        'options' => array(
            'one' => array(
                'label' => 'Male',
                'value' => 'male'
            ),
            'two' => array(
                'label' => 'Female',
                'value' => 'female'
            )
        )
    ),
    array(
        'label' => 'Radio Group',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'radio',
        'type'  => 'radio',
        'options' => array(
            'one' => array(
                'label' => 'Option One',
                'value' => 'one'
            ),
            'two' => array(
                'label' => 'Option Two',
                'value' => 'two'
            ),
            'three' => array(
                'label' => 'Option Three',
                'value' => 'three'
            )
        )
    ),
    array(
        'label' => 'Category',
        'id'    => 'category',
        'type'  => 'tax_select'
    ),
    array(
        'label' => 'Opponent',
        'desc'  => 'Select your oppoenent?.',
        'id'    =>  $prefix.'post_id',
        'type'  => 'post_list',
        'post_type' => array('swr_team')
    ),
    array(
        'label' => 'Meet Date',
        'desc'  => 'When is the meet?',
        'id'    => $prefix.'meet_date',
        'type'  => 'date'
    ),
    array(
        'label' => 'Meet Time',
        'desc'  => 'What time is the event?',
        'id'    => $prefix.'meet_time',
        'type'  => 'text_small'
    ),

    array(
        'label' => 'Oppoenent Score',
        'desc'  => 'Enter the total points from the opponent',
        'id'    => $prefix.'meet_time',
        'type'  => 'text_small'
    ),
    array(
        'label' => 'Your Score',
        'desc'  => 'Enter the total points you scored',
        'id'    => $prefix.'meet_time',
        'type'  => 'text_small'
    ),
    array(
        'label' => 'Result',
        'desc'  => 'Did ya win?',
        'id'    => $prefix.'result',
        'type'  => 'select',
        'options' => array(
            'one' => array(
                'label' => 'Win',
                'value' => 'win'
            ),
            'two' => array(
                'label' => 'Lost',
                'value' => 'lost'
            ),
            'thee' => array(
                'label' => 'Tie',
                'value' => 'tie'
            )
        )
    ),
    array(
        'label' => 'Slider',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'slider',
        'type'  => 'slider',
        'min'   => '0',
        'max'   => '100',
        'step'  => '5'
    ),
    array(
        'label' => 'Image',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'image',
        'type'  => 'image'
    ),
    array(
        'label' => 'Repeatable',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'repeatable',
        'type'  => 'repeatable'
    )*/
);


function dntly_enqueue_meta_scripts() 
{
    // enqueue scripts and styles, but only if is_admin
    if(is_admin()) {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('custom-js', DNTLY_PLUGIN_URL .'assets/js/custom-js.js');
        wp_enqueue_style('jquery-ui-custom', DNTLY_PLUGIN_URL .'assets/css/jquery-ui-custom.css');
    }
}
add_action( 'wp_enqueue_scripts', 'dntly_enqueue_meta_scripts');


// The Callback
function dntly_show_custom_meta_box() {
    global $dntly_custom_meta_fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
    
    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ( $dntly_custom_meta_fields as $field ) {
        // get value of this field if it exists for this post
        $meta = get_post_meta( $post->ID, $field['id'], true );
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch( $field['type'] ) {
                    // disableddollar
                    case 'disabled_percent':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'. $meta .'%" size="10" disabled />
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // disableddollar
                    case 'disabled_dollar':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="$'.number_format( $meta ).'" size="10" disabled />
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // disabled
                    case 'disabled':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="10" disabled />
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // text
                    case 'text':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // text_small
                    case 'text_small':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="6" />
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // textarea
                    case 'textarea':
                        echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
                                <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                                <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;
                    // select
                    case 'select':
                        echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                        }
                        echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    // radio
                    case 'radio':
                        foreach ( $field['options'] as $option ) {
                            echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
                                    <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                        echo '<span class="description">'.$field['desc'].'</span>';
                    break;
                    // checkbox_group
                    case 'checkbox_group':
                        foreach ($field['options'] as $option) {
                            echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' /> 
                                    <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                        echo '<span class="description">'.$field['desc'].'</span>';
                    break;
                    // post_list

                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}


// Save the Data
function dntly_save_custom_meta($post_id) {
    global $dntly_custom_meta_fields;
    
    // verify nonce
    if (!wp_verify_nonce( isset( $_POST['custom_meta_box_nonce'] ) ? $_POST['custom_meta_box_nonce'] : null, basename( __FILE__ ) ) ) { 
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
    }
    
   
}
add_action( 'save_post', 'dntly_save_custom_meta' );