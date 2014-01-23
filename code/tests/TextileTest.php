<?php

class TextileTest extends SapphireTest {

	public function testTextile() {
		require_once 'thirdparty/spyc/spyc.php';
		$fix = spyc_load_file( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'textile.yaml' );
		foreach( $fix As $k => $v) {
			if(
				is_array( $v ) &&
				array_key_exists( 'input', $v) &&
				array_key_exists( 'expect', $v)
			) {
				if( array_key_exists('assert', $v ) && $v[ 'assert' ] === 'skip' )
					continue;
				$field = new TextileField( 'Test1' );
				$field->setValue( $v[ 'input' ] );
				$strippedCache = trim( strtr( $field->getCache(), array( ' ' => '', '	' => '') ) );
				$strippedExpect = trim( strtr( $v[ 'expect' ], array( ' ' => '', '	' => '') ) );
				if( array_key_exists( 'note', $v ) ) {
					$ret = $this->assertEquals( $strippedExpect, $strippedCache, $v[ 'note' ] );
				} else {
					$ret = $this->assertEquals( $strippedExpect, $strippedCache );
				}
			}
		}
	}

}