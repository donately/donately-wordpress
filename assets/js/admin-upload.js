jQuery(function($) {  

    var DNTLY_UPLOADS = {
        debug : true
    }

    $('.dntly_upload_button').click(function (event) {  

        if ( DNTLY_UPLOADS.debug) console.log('.dntly_upload_button click event fired');

        // set vars
        var formfield         = $(this).siblings('.dntly_upload_field'),
            iframe            = $('#TB_iframeContent') ,
            iframe_upload_url = iframe.find('.text.urlfield'),
            iframe_insert_btn = iframe.find('.savesend input[type="submit"]'),
            iframe_attach_id  = iframe_insert_btn.attr('id');

        if ( DNTLY_UPLOADS.debug) console.log(iframe, iframe_upload_url, iframe_insert_btn);

        iframe_insert_btn.on('click', function (event) {
            console.log(iframe_upload_url);
            console.log('test');
        });

        //preview = $(this).siblings('.custom_preview_image');  
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');  
        
        window.send_to_editor = function(html) {  
            imgurl = $('img',html).attr('value'); 
            //classes = $('img', html).attr('class');  
            //id = classes.replace(/(.*?)wp-image-/, '');  
            formfield.val(imgurl);  
            //preview.attr('src', imgurl);  
            tb_remove();

        }  
        return false;  
    });  
      
    $('.custom_clear_image_button').click(function() {  
        var defaultImage = $(this).parent().siblings('.custom_default_image').text();  
        $(this).parent().siblings('.custom_upload_image').val('');  
        //jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);  
        return false;  
    });  
  
});  