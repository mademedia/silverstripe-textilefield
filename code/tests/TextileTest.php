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
echo '<hr /><hr />';
var_dump( $k );
				$field->setValue( $v[ 'input' ] );
				$strippedCache = strtr( $field->getCache(), array( ' ' => '', '	' => '') );
				$strippedExpect = strtr( $v[ 'expect' ], array( ' ' => '', '	' => '') );
				if( array_key_exists( 'note', $v ) ) {
// var_dump( $field->getCache() );
					$ret = $this->assertEquals( $strippedExpect, $strippedCache, $v[ 'note' ] );
				} else {
var_dump( $field->getCache() );
var_dump( $v[ 'expect' ] );
var_dump( $strippedCache );
var_dump( $strippedExpect );
					$ret = $this->assertEquals( $strippedExpect, $strippedCache );
				}
			}
		}
// Debug::dump( $fix );



		// for($i = 0; property_exists( $this, 'test' . $i; i++)) {
		// 	$test = 'test' . $i;
		// 	$field = new TextileField( 'Test1' );
		// 	$field->setValue( $this->$test[ 'textile' ] );
		// 	$this->assertEquals( $this->$test[ 'markup' ], $field->getCache() );
		// }
	}




// /** Test content **/
// 	private $test1 = array(
// 		'textile' => '<hr>

//     <hr/>

//     <hr >

//     <hr />

//     <hr >

//     <hr />

//     <hr></hr>

//     <hr class="whatnot"></hr >

//     <hr ></hr >

//     <HR>

//     <HR/>

//     <HR >

//     <HR />

//     <HR >

//     <HR />

//     <img src="blahblah.jpg" />

//     <p>Hello World!</p>

//     <P>Hello World!</P>

//     <P>Hello World!</p>

//     <p>Hello World!</P>',
// 		'markup' => '<hr>

//     <hr/>

//     <hr >

//     <hr />

//     <hr >

//     <hr />

//     <hr></hr>

//     <hr class="whatnot"></hr >

//     <hr ></hr >

//     <HR>

//     <HR/>

//     <HR >

//     <HR />

//     <HR >

//     <HR />

//     <img src="blahblah.jpg" />

//     <p>Hello World!</p>

//     <P>Hello World!</P>

//     <P>Hello World!</p>

//     <p>Hello World!</P>'
// 	);

// 	private $test2 = array(
// 		'textile' => 'I spoke.
//     And none replied.

//     I spoke.<br>
//     And none replied.

//     I spoke.<br/>
//     And none replied.

//     I spoke.<br />
//     And none replied.

//     I spoke.<BR>
//     And none replied.

//     I spoke.<BR/>
//     And none replied.

//     I spoke.<BR />
//     And none replied.',
// 		'markup' => '<p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>

//     <p>I spoke.<br />
//     And none replied.</p>'
// 	);

// 	private $test3 = array(
// 		'textile' => '<p>*foo*</p>

//     <p>*foo*[^2^]</p>',
// 		'markup' => '<p><strong>foo</strong></p>

//     <p><strong>foo</strong><sup>2</sup></p>'
// 	);

// 	private $test4 = array(
// 		'textile' => '_(class1 class2#id1)1_ *[en]2* **(#id)3** ??4?? __5__ %6% +7+ -(foobarbaz#boom bang)8- ~9~ ^10^

//     1[^st^], 2[^nd^], 3[^rd^]',
// 		'markup' => '<p><em class="class1 class2" id="id1">1</em> <strong lang="en">2</strong> <b id="id">3</b> <cite>4</cite> <i>5</i> <span>6</span> <ins>7</ins> <del class="foobarbaz">8</del> <sub>9</sub> <sup>10</sup></p>

//     <p>1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup></p>'
// 	);

// 	private $test5 = array(
// 		'textile' => '0 %1% _2_ 45% 6.7% %89.0',
// 		'markup' => '<p>0 <span>1</span> <em>2</em> 45% 6.7% %89.0</p>'
// 	);

// 	private $test6 = array(
// 		'textile' => 'h2{color:red}. Some redding',
// 		'markup' => '<h2 style="color:red;">Some redding</h2>'
// 	);

// 	private $test7 = array(
// 		'textile' => '"Textile(c)" is a registered(r) \'trademark\' of Textpattern(tm) -- or TXP(That\'s textpattern!) -- at least it was - back in \'88 when 2x4 was (+/-)5(o)C ... QED!

//     p{font-size: 200%;}. 2(1/4) 3(1/2) 4(3/4)

//     "." ".." "..." '.' '..' '...' Allow quoted periods.',
// 		'markup' => '<p>&#8220;Textile&#169;&#8221; is a registered&#174; &#8216;trademark&#8217; of Textpattern&#8482; &#8212; or <acronym title="That&#8217;s textpattern!"><span class="caps">TXP</span></acronym> &#8212; at least it was &#8211; back in &#8217;88 when 2&#215;4 was &#177;5&#176;C &#8230; <span class="caps">QED</span>!</p>

//     <p style="font-size: 200%;">2&#188; 3&#189; 4&#190;</p>

//     <p>&#8220;.&#8221; &#8220;..&#8221; &#8220;&#8230;&#8221; &#8216;.&#8217; &#8216;..&#8217; &#8216;&#8230;&#8217; Allow quoted periods.</p>'
// 	);

// 	private $test8 = array(
// 		'textile' => 'p(c). Valid class

//     p(#id). Valid ID

//     p(c#id). Valid class and ID

//     p(c9). Valid class

//     p(c4.2). Valid class

//     p(c1 c.2 c-3). Valid class',
// 		'markup' => '<p class="c">Valid class</p>

//     <p id="id">Valid ID</p>

//     <p class="c" id="id">Valid class and ID</p>

//     <p class="c9">Valid class</p>

//     <p class="c4.2">Valid class</p>

//     <p class="c1 c.2 c-3">Valid class</p>'
// 	);

// 	private $test9 = array(
// 		'textile' => '[1/2] x [1/4] and (1/2)" x [1/4]" and (1/2)' x (1/4)'

//     (2 x 10) X (3 / 4) x (200 + 64)

//     1 x 1 = 1

//     1 x1 = 1

//     1x 1 = 1

//     1x1 = 1

//     1 X 1 = 1

//     1 X1 = 1

//     1X 1 = 1

//     1X1 = 1

//     What is 1 x 1?

//     What is 1x1?

//     What is 1 X 1?

//     What is 1X1?

//     1 x 2 x 3 = 6

//     1x2x3=6

//     1x2 x 1x3 = 6

//     2' x 2' = 4 sqft.

//     2'x 2' = 4 sqft.

//     2' x2' = 4 sqft.

//     2'x2' = 4 sqft.

//     2' X 2' = 4 sqft.

//     2'X 2' = 4 sqft.

//     2' X2' = 4 sqft.

//     2'X2' = 4 sqft.

//     2" x 2" = 4 sqin.

//     2"x 2" = 4 sqin.

//     2" x2" = 4 sqin.

//     2"x2" = 4 sqin.

//     2" X 2" = 4 sqin.

//     2"X 2" = 4 sqin.

//     2" X2" = 4 sqin.

//     2"X2" = 4in[^2^].

//     What is 1.2 x 3.5?

//     What is .2 x .5?

//     What is 1.2x3.5?

//     What is .2x.5?

//     What is 1.2' x3.5'?

//     What is .2"x .5"?

//     1 x $10.00 x -£ 1.23 x ¥20,000 x -¤120.00 x ฿1,000,000 x -€110,00',
// 		'markup' => '<p>&#189; &#215; &#188; and &#189;&#8221; &#215; &#188;&#8221; and &#189;&#8217; &#215; &#188;&#8217;</p>

//     <p>(2 &#215; 10) &#215; (3 / 4) &#215; (200 + 64)</p>

//     <p>1 &#215; 1 = 1</p>

//     <p>1 &#215;1 = 1</p>

//     <p>1&#215; 1 = 1</p>

//     <p>1&#215;1 = 1</p>

//     <p>1 &#215; 1 = 1</p>

//     <p>1 &#215;1 = 1</p>

//     <p>1&#215; 1 = 1</p>

//     <p>1&#215;1 = 1</p>

//     <p>What is 1 &#215; 1?</p>

//     <p>What is 1&#215;1?</p>

//     <p>What is 1 &#215; 1?</p>

//     <p>What is 1&#215;1?</p>

//     <p>1 &#215; 2 &#215; 3 = 6</p>

//     <p>1&#215;2&#215;3=6</p>

//     <p>1&#215;2 &#215; 1&#215;3 = 6</p>

//     <p>2&#8217; &#215; 2&#8217; = 4 sqft.</p>

//     <p>2&#8217;&#215; 2&#8217; = 4 sqft.</p>

//     <p>2&#8217; &#215;2&#8217; = 4 sqft.</p>

//     <p>2&#8217;&#215;2&#8217; = 4 sqft.</p>

//     <p>2&#8217; &#215; 2&#8217; = 4 sqft.</p>

//     <p>2&#8217;&#215; 2&#8217; = 4 sqft.</p>

//     <p>2&#8217; &#215;2&#8217; = 4 sqft.</p>

//     <p>2&#8217;&#215;2&#8217; = 4 sqft.</p>

//     <p>2&#8221; &#215; 2&#8221; = 4 sqin.</p>

//     <p>2&#8221;&#215; 2&#8221; = 4 sqin.</p>

//     <p>2&#8221; &#215;2&#8221; = 4 sqin.</p>

//     <p>2&#8221;&#215;2&#8221; = 4 sqin.</p>

//     <p>2&#8221; &#215; 2&#8221; = 4 sqin.</p>

//     <p>2&#8221;&#215; 2&#8221; = 4 sqin.</p>

//     <p>2&#8221; &#215;2&#8221; = 4 sqin.</p>

//     <p>2&#8221;&#215;2&#8221; = 4in<sup>2</sup>.</p>

//     <p>What is 1.2 &#215; 3.5?</p>

//     <p>What is .2 &#215; .5?</p>

//     <p>What is 1.2&#215;3.5?</p>

//     <p>What is .2&#215;.5?</p>

//     <p>What is 1.2&#8217; &#215;3.5&#8217;?</p>

//     <p>What is .2&#8221;&#215; .5&#8221;?</p>

//     <p>1 &#215; $10.00 &#215; -£ 1.23 &#215; ¥20,000 &#215; -¤120.00 &#215; ฿1,000,000 &#215; -€110,00</p>'
// 	);

// 	private $test10 = array(
// 		'textile' => '??*text*??

//     _person_\'s

//     *person*\'s

//     "text":http://url/"

//     "text":http://url...

//     *CAPS "text":http://url*

//     "_text_"

//     *- text -*

//     <text *text*',
// 		'markup' => '<p><cite><strong>text</strong></cite></p>

//     <p><em>person</em>&#8217;s</p>

//     <p><strong>person</strong>&#8217;s</p>

//     <p><a href="http://url/">text</a>&#8221;</p>

//     <p><a href="http://url">text</a>&#8230;</p>

//     <p><strong><span class="caps">CAPS</span> <a href="http://url">text</a></strong></p>

//     <p>&#8220;<em>text</em>&#8221;</p>

//     <p><strong>&#8211; text &#8211;</strong></p>

//     <p>&lt;text <strong>text</strong></p>'
// 	);

// 	private $test11 = array(
// 		'textile' => '*

//     **

//     * item1',
// 		'markup' => '<p>*</p>

//     <p>**</p>

//     <ul>
//             <li>item1</li>
//     </ul>'
// 	);

// 	private $test12 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test13 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test14 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test15 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test16 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test17 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);

// 	private $test11 = array(
// 		'textile' => '',
// 		'markup' => ''
// 	);
}