<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

class UpSolutionJsMinifier {
	protected $keywordsReserved = array(
		'do',
		'if',
		'in',
		'for',
		'let',
		'new',
		'try',
		'var',
		'case',
		'else',
		'enum',
		'eval',
		'null',
		'this',
		'true',
		'void',
		'with',
		'break',
		'catch',
		'class',
		'const',
		'false',
		'super',
		'throw',
		'while',
		'yield',
		'delete',
		'export',
		'import',
		'public',
		'return',
		'static',
		'switch',
		'typeof',
		'default',
		'extends',
		'finally',
		'package',
		'private',
		'continue',
		'debugger',
		'function',
		'arguments',
		'interface',
		'protected',
		'implements',
		'instanceof',
		'abstract',
		'boolean',
		'byte',
		'char',
		'double',
		'final',
		'float',
		'goto',
		'int',
		'long',
		'native',
		'short',
		'synchronized',
		'throws',
		'transient',
		'volatile',
	);

	protected $keywordsBefore = array(
		'do',
		'in',
		'let',
		'new',
		'var',
		'case',
		'else',
		'enum',
		'void',
		'with',
		'class',
		'const',
		'yield',
		'delete',
		'export',
		'import',
		'public',
		'static',
		'typeof',
		'extends',
		'package',
		'private',
		'continue',
		'function',
		'protected',
		'implements',
		'instanceof',
	);

	protected $keywordsAfter = array(
		'in',
		'public',
		'extends',
		'private',
		'protected',
		'implements',
		'instanceof',
	);

	protected $operators = array(
		'+',
		'-',
		'*',
		'/',
		'%',
		'=',
		'+=',
		'-=',
		'*=',
		'/=',
		'%=',
		'<<=',
		'>>=',
		'>>>=',
		'&=',
		'^=',
		'|=',
		'&',
		'|',
		'^',
		'~',
		'<<',
		'>>',
		'>>>',
		'==',
		'===',
		'!=',
		'!==',
		'>',
		'<',
		'>=',
		'<=',
		'&&',
		'||',
		'!',
		'[',
		']',
		'?',
		':',
		',',
		';',
		'(',
		')',
		'{',
		'}',
	);

	protected $operatorsBefore = array(
		'+',
		'-',
		'*',
		'/',
		'%',
		'=',
		'+=',
		'-=',
		'*=',
		'/=',
		'%=',
		'<<=',
		'>>=',
		'>>>=',
		'&=',
		'^=',
		'|=',
		'&',
		'|',
		'^',
		'~',
		'<<',
		'>>',
		'>>>',
		'==',
		'===',
		'!=',
		'!==',
		'>',
		'<',
		'>=',
		'<=',
		'&&',
		'||',
		'!',
		'[',
		'?',
		':',
		',',
		';',
		'(',
		'{',
	);

	protected $operatorsAfter = array(
		'+',
		'-',
		'*',
		'/',
		'%',
		'=',
		'+=',
		'-=',
		'*=',
		'/=',
		'%=',
		'<<=',
		'>>=',
		'>>>=',
		'&=',
		'^=',
		'|=',
		'&',
		'|',
		'^',
		'~',
		'<<',
		'>>',
		'>>>',
		'==',
		'===',
		'!=',
		'!==',
		'>',
		'<',
		'>=',
		'<=',
		'&&',
		'||',
		'[',
		']',
		'?',
		':',
		',',
		';',
		'(',
		')',
		'}',
	);

	protected function stripWhitespace( $content ) {
		// uniform line endings, make them all line feed
		$content = str_replace( array( "\r\n", "\r" ), "\n", $content );

		// collapse all non-line feed whitespace into a single space
		$content = preg_replace( '/[^\S\n]+/', ' ', $content );

		// strip leading & trailing whitespace
		$content = str_replace( array( " \n", "\n " ), "\n", $content );

		// collapse consecutive line feeds into just 1
		$content = preg_replace( '/\n+/', "\n", $content );

		$operatorsBefore = $this->getOperatorsForRegex( $this->operatorsBefore, '/' );
		$operatorsAfter = $this->getOperatorsForRegex( $this->operatorsAfter, '/' );
		$operators = $this->getOperatorsForRegex( $this->operators, '/' );
		$keywordsBefore = $this->getKeywordsForRegex( $this->keywordsBefore, '/' );
		$keywordsAfter = $this->getKeywordsForRegex( $this->keywordsAfter, '/' );

		// strip whitespace that ends in (or next line begin with) an operator
		// that allows statements to be broken up over multiple lines
		unset( $operatorsBefore['+'], $operatorsBefore['-'], $operatorsAfter['+'], $operatorsAfter['-'] );
		$content = preg_replace(
			array(
				'/(' . implode( '|', $operatorsBefore ) . ')\s+/',
				'/\s+(' . implode( '|', $operatorsAfter ) . ')/',
			), '\\1', $content
		);

		// make sure + and - can't be mistaken for, or joined into ++ and --
		$content = preg_replace(
			array(
				'/(?<![\+\-])\s*([\+\-])(?![\+\-])/',
				'/(?<![\+\-])([\+\-])\s*(?![\+\-])/',
			), '\\1', $content
		);

		// collapse whitespace around reserved words into single space
		$content = preg_replace( '/(^|[;\}\s])\K(' . implode( '|', $keywordsBefore ) . ')\s+/', '\\2 ', $content );
		$content = preg_replace( '/\s+(' . implode( '|', $keywordsAfter ) . ')(?=([;\{\s]|$))/', ' \\1', $content );

		/*
		 * We didn't strip whitespace after a couple of operators because they
		 * could be used in different contexts and we can't be sure it's ok to
		 * strip the newlines. However, we can safely strip any non-line feed
		 * whitespace that follows them.
		 */
		$operatorsDiffBefore = array_diff( $operators, $operatorsBefore );
		$operatorsDiffAfter = array_diff( $operators, $operatorsAfter );
		$content = preg_replace( '/(' . implode( '|', $operatorsDiffBefore ) . ')[^\S\n]+/', '\\1', $content );
		$content = preg_replace( '/[^\S\n]+(' . implode( '|', $operatorsDiffAfter ) . ')/', '\\1', $content );

		/*
		 * Get rid of double semicolons, except where they can be used like:
		 * "for(v=1,_=b;;)", "for(v=1;;v++)" or "for(;;ja||(ja=true))".
		 * I'll safeguard these double semicolons inside for-loops by
		 * temporarily replacing them with an invalid condition: they won't have
		 * a double semicolon and will be easy to spot to restore afterwards.
		 */
		$content = preg_replace( '/\bfor\(([^;]*);;([^;]*)\)/', 'for(\\1;-;\\2)', $content );
		$content = preg_replace( '/;+/', ';', $content );
		$content = preg_replace( '/\bfor\(([^;]*);-;([^;]*)\)/', 'for(\\1;;\\2)', $content );

		/*
		 * Next, we'll be removing all semicolons where ASI kicks in.
		 * for-loops however, can have an empty body (ending in only a
		 * semicolon), like: `for(i=1;i<3;i++);`
		 * Here, nothing happens during the loop; it's just used to keep
		 * increasing `i`. With that ; omitted, the next line would be expected
		 * to be the for-loop's body...
		 * I'm going to double that semicolon (if any) so after the next line,
		 * which strips semicolons here & there, we're still left with this one.
		 */
		$content = preg_replace( '/(for\([^;]*;[^;]*;[^;\{]*\));(\}|$)/s', '\\1;;\\2', $content );

		/*
		 * We also can't strip empty else-statements. Even though they're
		 * useless and probably shouldn't be in the code in the first place, we
		 * shouldn't be stripping the `;` that follows it as it breaks the code.
		 * We can just remove those useless else-statements completely.
		 *
		 * @see https://github.com/matthiasmullie/minify/issues/91
		 */
		$content = preg_replace( '/else;/s', '', $content );

		/*
		 * We also don't really want to terminate statements followed by closing
		 * curly braces (which we've ignored completely up until now) or end-of-
		 * script: ASI will kick in here & we're all about minifying.
		 * Semicolons at beginning of the file don't make any sense either.
		 */
		$content = preg_replace( '/;(\}|$)/s', '\\1', $content );
		$content = ltrim( $content, ';' );

		// get rid of remaining whitespace af beginning/end
		return trim( $content );
	}

	protected function getOperatorsForRegex( array $operators, $delimiter = '/' ) {
		// escape operators for use in regex
		$delimiter = array_fill( 0, count( $operators ), $delimiter );
		$escaped = array_map( 'preg_quote', $operators, $delimiter );

		$operators = array_combine( $operators, $escaped );

		// ignore + & - for now, they'll get special treatment
		unset( $operators['+'], $operators['-'] );

		// dot can not just immediately follow a number; it can be confused for
		// decimal point, or calling a method on it, e.g. 42 .toString()
		//		$operators['.'] = '(?<![0-9]\s)\.';

		// don't confuse = with other assignment shortcuts (e.g. +=)
		$chars = preg_quote( '+-*\=<>%&|' );
		$operators['='] = '(?<![' . $chars . '])\=';

		return $operators;
	}

	protected function getKeywordsForRegex( array $keywords, $delimiter = '/' ) {
		// escape keywords for use in regex
		$delimiter = array_fill( 0, count( $keywords ), $delimiter );
		$escaped = array_map( 'preg_quote', $keywords, $delimiter );

		// add word boundaries
		array_walk(
			$keywords, function ( $value ) {
			return '\b' . $value . '\b';
		}
		);

		$keywords = array_combine( $keywords, $escaped );

		return $keywords;
	}

	public function minify( $content ) {
		return $this->stripWhitespace( $content );
	}
}
