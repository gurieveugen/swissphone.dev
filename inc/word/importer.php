<?php
//add_action( 'init', 'init_word_importer' );
add_action( 'admin_menu', 'init_word_importer' );

function init_word_importer() {
    add_management_page( 'Import Documents', 'Import Documents', 'manage_options', 'import_docx', 'word_import_admin' );
}

function word_import_admin() {
    include 'admin-page.php';
}

function log_msg( $msg ) {
    $lg = @fopen( dirname(__FILE__) . '/dlog.txt', 'a+' );
    @fwrite( $lg, date('c ') . $msg . "\r\n" );
    @fclose($lg);
}

function prepare_docx_file( $filename ) { 
    require 'dUnzip2.inc.php';
    $targetDir = dirname(__FILE__)."/zp_contents/unzipped";
    if(is_dir($targetDir)){
       $i = 1;
        while(is_dir($targetDir.$i)) $i++;
        $targetDir .= $i-1;
    }
    $tempDir = $targetDir;
    $baseDir = "";
    $maintainStructure = true;
    $unzip = new dUnzip2($filename);
    $unzip->unzipAll($targetDir, $baseDir, $maintainStructure);
    unset($unzip);
    if(is_file($tempDir."/[Content_Types].xml")){
        return $tempDir;
    }
    return false;
}


if ( isset($_GET['uploaddocument']) && ($_doc_num = $_GET['uploaddocument']) && isset($_POST['s_code']) ) {
    log_msg( 'Session ' . $_POST['s_code'] );
    //session_destroy();
    session_id( $_POST['s_code'] );
    session_start();
    //echo $_SESSION['upload_num'], ' ', $_doc_num;
    //if ( ! isset($_SESSION[$_doc_num]) || $_SESSION[$_doc_num] != true ) die( 'Auth Error');
    $file = array_shift($_FILES);
    if ( ! isset($file) ) die('File was not uploaded');
    log_msg( 'Uploading ' . $file['name'] );
    $filepath = dirname(__FILE__) . '/uploaded/' . $file['name'];
    if ( !@move_uploaded_file( $file['tmp_name'], $filepath ) ) die('File relocation failed');
    if ( ! ($tdir = prepare_docx_file( $filepath )) ) die( 'File exctracting failed or wrong file format' );
    error_reporting(E_ALL);
    require 'class.DOCXtoHTML.php';
    $path_info = pathinfo($filepath);
    $docx = new DOCXtoHTML();
    $docx->docxPath = $filepath;
    $docx->tempDir = $tdir;
    $docx->content_folder = strtolower(str_replace("." . $path_info['extension'], "", str_replace(" ", "-", $path_info['basename'])));
    $docx->image_max_width = 640;
    $docx->imagePathPrefix = get_bloginfo('url');
    $docx->keepOriginalImage = true;
    $docx->split = false;
    $docx->allowColor = true;
    $docx->Init();
    $post_data  = $docx->output[0];
    preg_match_all( '|<p[^>]*>(.*?)</p>|im', $post_data, $post_chunks);
    //var_dump($post_chunks);
    $ttl    = $post_chunks[1][0];
    $ptitle = strip_tags($ttl);
    $pcont  = str_ireplace( $post_chunks[0][0], '', $post_data);
    $plain_content = strip_tags($pcont);
    $post_chunks = array_count_values( explode( ' ', str_ireplace( array(',','.',':','/',';','"') , ' ', $plain_content ) ) );
    arsort($post_chunks);
    $keywords = array();
    $size = count($post_chunks);
    $total = 0;
    foreach( $post_chunks as $word => $key ) {
        if ( $key < 3 ) break;
        if ( strlen($word) < 5 ) continue;
        $keywords[] = strtolower( $word ); 
        if ( ++$total > 20 ) break; 
    }
    $short = short_content($plain_content, 300, '');
    ?>
    <div class="docxpost_update">
        <form>
            <dl>
                <dt>Title </dt><dd><input class="send_data" name="post_title" type="text" value="<?php echo esc_attr($ptitle); ?>" /></dd>
                <dt>Category </dt><dd class="cat_value"></dd>
                <!--<dt>Language </dt><dd class="lang_val"></dd>-->
                <dt>Content</dt><dd><div class="isolated"><?php echo $pcont; ?></div></dd>
                <dt>Excerpt </dt><dd><textarea name="excerpt" class="send_data"><?php echo htmlspecialchars($short); ?></textarea></dd>
                <dt>SEO Title </dt><dd><input class="send_data" name="seo_title" type="text" value="<?php echo esc_attr($ptitle); ?>" /></dd>
                <dt>SEO Description </dt><dd><textarea class="send_data" name="seo_description"><?php echo htmlspecialchars($short); ?></textarea></dd>
                <dt>SEO Keywords <br /><?php if (count($keywords)) : ?><small>Sugested: <?php echo implode( ', ', $keywords ); ?></small><?php endif; ?></dt>
                <dd><textarea class="send_data" name="seo_keywords"></textarea></dd>
            </dl>
            <input class="send_data" name="action" type="hidden" value="insert_docx_post" />
            <input class="send_data" name="pcontent" type="hidden" value="<?php echo esc_attr($pcont); ?>" />
            <br style="clear: both;" />
            <a class="button save_imported" href="#save" >Save</a>
        </form>        
    </div>
    <?php exit;
    //die( 'Uploaded - ' . htmlentities(var_export($post_data, true)) );   
}

if ( isset($_POST['action']) && ('insert_docx_post' == $_POST['action']) && is_user_logged_in() && current_user_can('administrator')) {
    $pid = wp_insert_post(array(
        'post_author'   => get_current_user_id(),
        'post_category' => array(intval($_POST['doc_category'])),
        'post_content'  => stripslashes($_POST['pcontent']),
        'post_excerpt'  => stripslashes($_POST['excerpt']), //For all your post excerpt needs.
        'post_status'   => 'publish',
        'post_title'    => stripslashes($_POST['post_title']),
        'post_type'     => 'post',
        //'icl_post_language' => strtolower($_POST['doc_lang']),
    ));
    if ($pid) {
        update_post_meta( $pid, '_aioseop_description', stripslashes($_POST['seo_description']) );
        update_post_meta( $pid, '_aioseop_title', stripslashes($_POST['seo_title']) );
        update_post_meta( $pid, '_aioseop_keywords', stripslashes($_POST['seo_keywords']) );
        status_header('200');
        die( "$pid" );
    } else {
        status_header('404');
        die( 'Error inserting post' );
    }
}
