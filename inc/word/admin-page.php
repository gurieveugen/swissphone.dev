<style type="text/css">
    .importer-wrap dl dt {
        float: left; 
        clear: left;
        width: 150px;
        padding: 2px;
        margin: 0px;
    }
    .importer-wrap dl dd {
        float: left; 
        clear: right;
        padding: 2px;
        margin: 0px;
    }
    .importer-wrap dl dd textarea {
        width: 300px;
        height: 100px;
    }
    .importer-wrap dl dd input {
        width: 300px;
    }         
    .importer-wrap dl dd div.isolated {
        width: 300px;
        height: 100px;
        overflow: scroll; 
    } 
    .importer-wrap .docx_uploader, .docxpost_update {
        clear: both;
    }
    a.save_all_posts {
        float: left; 
        width: 103px;
        height: 40px;  
        text-indent: -9999px;
        background: transparent url(<?php bloginfo('template_url'); ?>/images/saveall_green.png) no-repeat; 
        margin-right: 10px;     
    }
</style>
<div class="wrap importer-wrap">
    <h2><?php _e('Import Word Document', 'SwissPhoneAdmin' ); ?></h2>
    <dl class="docx_options">
        <!--<dt><?php _e('Post Title', 'SwissPhoneAdmin' ); ?></dt> 
        <dd>
            <input type="radio" name="doc_title" value="doc_line" checked="checked" /> <?php _e('First line of document', 'SwissPhoneAdmin' ); ?> <br />
            <input type="radio" name="doc_title" value="filename" /> <?php _e('File name', 'SwissPhoneAdmin' ); ?> <br />
        </dd>-->
        <!--<dt><?php _e('Language', 'SwissPhoneAdmin' ); ?></dt>
        <dd>
            <select name="doc_lang" class="send_data">
                <option value="en">EN</option>
                <option value="de">DE</option>
                <option value="fr">FR</option>
            </select>
        </dd>-->
        <dt><?php _e('Category', 'SwissPhoneAdmin' ); ?></dt>
        <dd>
            <select name="doc_category" class="send_data">
                <!--<option value="autofetch"><?php _e('Fetch automatically from file', 'SwissPhoneAdmin' ); ?></option>-->
                <?php foreach( get_categories(array( 'hide_empty' => 0 )) as $term ) : ?>
                   <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                <?php endforeach; ?>
            </select>
            <br />
        </dd>
        <dt><?php _e('SEO Keywords', 'SwissPhoneAdmin' ); ?></dt><dd><textarea name="seo_keys" ></textarea></dd>
    </dl>
    <div class="docx_uploader">
        <a href="#saveall" class="save_all_posts" style="display: none;" >Save All</a>
        <div style="float: left;"><div id="word_upload_button"></div></div>
        <div id="word_upload_status" style="float: left; padding: 0px 10px;"></div>
        <div id="word_upload_result" style="clear: both;"></div>
        <br style="clear: both" />
       
    </div>
</div>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/inc/word/importer.js"></script>
<script type="text/javascript">
    var doc_upload_code = '<?php echo ($_SESSION['upload_num'] = md5($_SERVER['REQUEST_TIME'])); ?>';
    var home_url        = '<?php bloginfo('url'); ?>';
    var ssid             = '<?php echo session_id(); ?>';   
    jQuery(function($){
        var DocUploader =  new SWFUpload({ 
            upload_url      : home_url + '/?uploaddocument=' + doc_upload_code, 
            flash_url       : home_url + '/wp-includes/js/swfupload/swfupload.swf',
            post_params     : {"s_code" : ssid }, 
            file_size_limit : "32 MB",
            file_types      : "*.docx",
            
            button_placeholder_id : "word_upload_button",
            button_width    : 103, 
            button_height   : 40, 
            button_image_url: '<?php bloginfo('template_url'); ?>/images/upload_red.png',
            button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
           
            file_dialog_start_handler   : sp_fileDialogStart,
            file_queued_handler         : sp_fileQueued,
            file_queue_error_handler    : sp_fileQueueError,
            file_dialog_complete_handler: sp_fileDialogComplete,
            upload_start_handler        : sp_uploadStart,
            upload_progress_handler     : sp_uploadProgress,
            upload_error_handler        : sp_uploadError,
            upload_success_handler      : sp_uploadSuccess,
            upload_complete_handler     : sp_uploadComplete,
            
            custom_settings : {
                progressTarget : "word_upload_status",
                cancelButtonId : "word_cancel_button",
                resultTarget   : "word_upload_result"
            }
        });
        $('div.docxpost_update .save_imported').live('click',function(){
            var sdata = new Array();
            var $this = $(this);
            $this.closest('form').find('.send_data').each(function(){
                sdata.push($(this).attr('name') + '=' + $(this).val());
            });
            sdata = sdata.join('&');
            $.ajax({
                type: "POST",
                url: home_url + "/?debug=true",
                data: sdata,
                complete: function( result, data){
                    if ( data.length < 10 ) {
                        $this.closest('.docxpost_update').remove();
                    } else {
                        $this.closest('div.docxpost_update').append(data);
                    }
                }
            });
            return false;
        });
        $('a.save_all_posts').live('click',function(){
            $('a.save_imported').trigger('click');
            return false;
        });
    });
</script>