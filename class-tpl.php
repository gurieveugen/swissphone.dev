<?php
/**
 * Simple template processor
 *
 * @author 	Oleg Dudkin
 * @version 0.2
 *
 */

class FileTemplateProcessor
{
	private $file		=	NULL;
	private $template	=	'';
	private $data		= 	array();
	private $html		=	array();
	private $applied	=	false;
	public	$clean		=	false;

	public function __construct( $sFname = NULL, $data = NULL ) {

		if ( ! is_null( $sFname ) ) $this->loadFile( $sFname );
		if ( ! is_null( $data   ) ) $this->loadData( $data   );

	}

	public function loadFile( $sFname ) {

		$this->file 	= dirname( __FILE__ ). '/'. $sFname;
		$this->applied 	= false;

		if ( file_exists( $this->file ) )
			$this->template = @file_get_contents( $this->file );
		else
			$this->file		= NULL;

		return $this->template;
	}

	public function loadData( $data = array() ) {

		$this->applied 	= false;
		if ( ! is_array($data) ) return false;
		$this->data 	= $data;
		return $this->apply();

	}

	public function apply( $data = NULL ) {

		if ( ! is_null( $data ) ) return $this->loadData( $data );

		if ( true === $this->applied ) return $this->html;
		$result = $this->template;

		foreach ( $this->data as $key => $element ) {
			if ( ! is_scalar($element) ) continue;
			$result = str_replace(
				array(	
					'${' . strtoupper($key) . '}',
					'${' . strtolower($key) . '}'
				)
				, $element
				, $result
			);
		}

		if ( $this->clean ) 
			$result = preg_replace( "/\$\{[^\}]+\}/", "", $result );

		$this->html 	= $result;
		$this->applied 	= true;
		return 	$this->html;
	}

}

function hv_tpl_out( $tpl, $data ) {
	$tp = new FileTemplateProcessor( $tpl, $data );
	return $tp->apply();
}