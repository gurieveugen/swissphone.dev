<?php
//if ( ! current_user_can('administrator') ) return;

class Web360Generator {
	
	const	DATA_BASE_URL = '/wp-content/web360data/';
	
	public 	$table	=	'wp_web360';
	private $loaded_attachments = array();
	private $loaded_rotators	= array(); 
	
	public function __construct() {
		$this->dbDelta();
		foreach( array( 'wp_enqueue_scripts') as $hook ) 
			add_action( $hook, array( $this, $hook ) );
	}
	
	public function wp_enqueue_scripts() {
		wp_register_script( 'webrotate360' , get_bloginfo( 'template_url' ). '/inc/web360/imagerotator.js', array('jquery','swfobject') );
		//wp_register_script( 'jquery-detect' , get_bloginfo( 'template_url' ). '/inc/web360/jquery.detect.js', array('jquery') );
		wp_enqueue_script( 'webrotate360' );
	}
		
	public function dbDelta() {
			
		$sql 	= 
			"CREATE TABLE $this->table (  
				id			mediumint(9) NOT NULL AUTO_INCREMENT,
				slug		varchar(200) DEFAULT '' NOT NULL,
				images		TEXT DEFAULT '' NOT NULL,
				status		varchar(10)	 DEFAULT 'new' NOT NULL,
				torder		mediumint(9) DEFAULT '0' NOT NULL,
				UNIQUE KEY id (id)
			);"; 	
		$dbver 	= md5( $sql );
		if ( $dbver == get_option( 'table_ver_' . $this->table ) ) return false;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		global $wpdb;
		update_option( 'table_ver_' . $this->table, $dbver );
		
	}
	
	public function create() {
		global $wpdb;
		$wpdb->insert( $this->table, array( 'torder' => 1 ) );
		$id = $wpdb->insert_id;
		$wpdb->update( $this->table, array( 'slug' => 'data_' . $id ) , array( 'id' => $id ) );
		return $this->get( $id );
	}
	
	public function save( $rot ) {
		global $wpdb;
		$rot->images = array_filter(array_unique( $rot->images ));
		sort($rot->images);
		$rot->generate_xml();
		$wpdb->update( 
			$this->table, 
			array( 	'slug' 	=> $rot->slug, 
					'status'=> $rot->status, 
					'images'=> implode( ',', $rot->images )
				 ) , 
			array( 'id' => $rot->id ) 
		);
		$this->loaded_rotators[$rot->id] = $rot;
	}
	
	public function get( $id ) {
		
		if ( $id && isset( $this->loaded_rotators[$id] ) ) return $this->loaded_rotators[$id];
		
		global $wpdb;
		$data = $wpdb->get_row( 'SELECT * FROM '.$this->table.' WHERE id = ' . $id, ARRAY_A );
		$res  = new Web360Rotator();
		$res->id 	 = $id;
		$res->images = array_filter(explode(',', $data['images']));
		$res->slug	 = $data['slug'];
		$res->status = $data['status'];
		$this->loaded_rotators[$id] = $res;
		return $res;
		
	}
	
	public function delete( $id ) {
		global $wpdb;
		$rot = $this->get($id);
		$wpdb->query( 'DELETE FROM '.$this->table.' WHERE id = '.$id );
		$path = $rot->path();
		@unlink( $path . '/images.xml' );
		$path .= '/images/'; 
		foreach( $rot->images as $img ) {
			@unlink( $path . $img );
		}
		@unlink($path);
	}
	
	/**
	 * @return Web360Attachment
	 */
	
	public function get_attachment( $pid = 0 ) {
		$att = '';
		if ( $pid && isset( $this->loaded_attachments[$pid] ) ) return $this->loaded_attachments[$pid];
		if ( $pid ) $att = get_post_meta( $pid, 'web_360_attachment', true );
		if ( is_serialized( $att ) ) {
			$att = unserialize($att);
		} else {
			$att = new Web360Attachment( $pid );
		}
		if ( $pid && $att ) $this->loaded_attachments[$pid] = $att;
		return $att;
	}
	
}

global $Rotator360;
$Rotator360 = new Web360Generator(); 

class Web360Rotator {
	
	public $id		=	0;
	public $slug	=	'';
	public $images	=	array();
	
	function generate_xml() {
		$last_image = count($this->images) - 1;
		$multi 		= $last_image?'true':'false';
		ob_start();
		echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<config>
  <settings>
  	<preloader image="images/<?php echo $this->images[$last_image]; ?>" />
    <userInterface showZoomButtons="true" showToolTips="true" showHotspotsButton="false" showFullScreenButton="true" showTogglePlayButton="<?php echo $multi; ?>" showArrows="<?php echo $multi; ?>" toolbarAlign="center" toolbarBackColor="#ffffff" toolbarHoverColor="#808285" toolbarForeColor="#A7A9AE" progressLoopColor="#E8E8E8" progressNumColor="#949494" toolbarBackAlpha="0.68" toolbarAlpha="1" flashEmbedFonts="true" />
    <control dragSpeed="0.15" maxZoom="223" maxZoomFullScreen="200" fullScreenStretch="100" doubleClickZooms="true" disableMouseControl="false" reverseScroll="false" />
    <rotation firstImage="<?php echo $last_image; ?>" rotate="false" rotatePeriod="6" bounce="false" />
  </settings>
  <hotspots />
  <images>
  	<?php foreach( $this->images as $fname ): ?>
    	<image src="images/<?php echo $fname; ?>" />
    <?php endforeach; ?>
  </images>
</config>
<?php		
		$xml = ob_get_clean();
		$xh  = @fopen( ABSPATH . Web360Generator::DATA_BASE_URL . $this->slug . '/images.xml', 'w+' );
		@fwrite($xh, $xml);
		@fclose($xh); 
	}
	
	function source() {
		return home_url( Web360Generator::DATA_BASE_URL . $this->slug );
	}
	
	function image_url( $img ) {
		if ( is_numeric( $img ) ) $img = $this->images[$img];
		return $this->source() . '/images/' . $img;
	}
	
	function path() {
		return ABSPATH . Web360Generator::DATA_BASE_URL . $this->slug;
	}
	
	function xml_url() {
		return home_url( Web360Generator::DATA_BASE_URL . $this->slug . '/images.xml' );
	}
	
}

class Web360Attachment {
	
	public	$post_id	 =	0;
	public 	$rotator_ids =	array();
	public	$rotators	 =	array();
	
	public function __construct( $pid ) {
		$this->post_id = $pid;
	}
	
	public function load() {
		global $Rotator360;
		foreach( $this->rotator_ids as $rid ) {
			$this->rotators[] = $Rotator360->get( $rid );
		}
	}
	
	function __sleep() {
		return array( 'post_id', 'rotator_ids' );
	}
	
	function __wakeup() {
		$this->load();
	}
	
	function add( $rot ) {
		global $Rotator360;
		if ( is_numeric($rot) ) {
			$id 	= $rot;
			$rot 	= $Rotator360->get( $id );
		} elseif( $rot instanceof Web360Rotator ) {
			$id		= $rot->id;
		} else {
			return false;
		}
		$this->rotator_ids[] = $id;
		$this->rotators[] 	 = $rot;
		return true; 
	} 
	
	function save() {
		update_post_meta($this->post_id, 'web_360_attachment', serialize($this) );
	}
	
	function run() {
		include 'show-rotator.php';
	}
	
}

class Web360Admin {
	
	public function __construct() {
		foreach( array( 'admin_init', 'admin_enqueue_scripts', 'save_post', 'admin_print_styles' ) as $hook ) 
			add_action( $hook, array( $this, $hook ) );
	}
	
	public function admin_init() {
		foreach ( array( 'product', 'solution' ) as $screen ) 
			add_meta_box( 'web360metabox',  __('Web 360'), array( $this, 'metabox' ), $screen, 'normal', 'high' ); 
	}
	
	public function admin_enqueue_scripts() {
		wp_register_script( 'sp_upload' , get_bloginfo('template_url') . '/inc/web360/upload.js', array( 'jquery', 'swfupload' ), time() );
		wp_enqueue_script( 'sp_upload' );
	}
	
	public function metabox( $entry ) {
		global $Rotator360;
		$use360 = 'yes' == get_post_meta( $entry->ID, 'use_web360', true );
		$atc360 = $Rotator360->get_attachment( $entry->ID );
		wp_nonce_field('web360settings', 'web360settings_nonce');
		include 'admin-metabox.php';
	}
	
	public function admin_print_styles() {
		wp_register_style( 'web360_admin', get_bloginfo('template_url') .'/inc/web360/admin.css' );
		wp_enqueue_style( 'web360_admin' );
	}
	
	public function save_post( $entry_id ) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $entry_id;
		if ( in_array($_POST['post_type'], array('solution', 'product')) ) {
			//if ( ! wp_verify_nonce( $_POST['web360settings_nonce'], 'web360settings' ) ) return $entry_id;
			update_post_meta($entry_id, 'use_web360', $_POST['use_web360'] );
			$list = array();
			global $Rotator360;
			foreach ( $_POST['rotator_ids'] as $id ) {
				if ( isset($_POST['delete_ids'][$id]) && 'yes' == $_POST['delete_ids'][$id] ) {
					$Rotator360->delete( $id );
				} else {
					$list[]= $id;
				}
			}
			
			$atc = $Rotator360->get_attachment( $entry_id );
			$atc->rotator_ids = $list;
			$atc->save();
		}
	}
	
} 

if ( is_admin() ) {
	global $Web360Admin;
	$Web360Admin = new Web360Admin;
}

if ( isset($_GET['web360']) ) {
	$ff = @fopen( dirname(__FILE__) . '/log.txt', 'a+' );
	switch ( $_GET['web360'] ) {
		
		case 'new': 
			$newr = $Rotator360->create();
			header('Content-Type: application/json');
			echo json_encode($newr->id);
			break;
			
		case 'upload' :
			ob_start();
			error_reporting(E_ALL);
			echo "\r\n";
			echo 'Upload started';
			if ( /*isset($_POST['upload_web360_nonce']) && wp_verify_nonce($_POST['upload_web360_nonce'], 'upload_web360' ) && */ isset($_POST['web360id']) && count($_FILES) ) {
				if ( ! $id = intval($_POST['web360id']) ) break;
				$rot = $Rotator360->get($id);
				var_dump($rot);				
				$file = array_shift($_FILES);
				if ($file['error'] > 0) {
			    	echo 'Return Code: ' . $file['error'];
			    } else {
			    	//ob_start();
					$fname = $rot->path();
					if ( ! @file_exists($fname) ) @mkdir( $fname, 0755 );
					$fname .= '/images' ;
					if ( ! @file_exists($fname) ) @mkdir( $fname, 0755 );
					$fname .= '/' . $file['name'];
					if ( @file_exists($fname) ) @unlink($fname);
					@move_uploaded_file( $file['tmp_name'], $fname );
					//$res = ob_get_clean();
					$rot->status = 'saved';
					$rot->images[] = $file['name'];
					$Rotator360->save($rot);
			    }
				var_dump($rot);
			}			
			$res = ob_get_clean();
			@fwrite($ff, date( 'd-m-Y h:i' ) . ' ' . $res );
			break;
	}
	fclose($ff);
	exit;
	
}

function product_run_rotator( $id ) {
	if ( 'yes' != get_post_meta($id, 'use_web360', true) ) return false;
	global $Rotator360;
	$atc = $Rotator360->get_attachment( $id );
	if ( ! count($atc->rotators) ) return false;
	$atc->run();
	return true;
}

