<?php
/**
 * TabberNeue
 * Tabber Class
 * Implement <tabber> tag
 *
 * @package TabberNeue
 * @author  alistair3149, Eric Fortin, Alexia E. Smith, Ciencia Al Poder
 * @license GPL-3.0-or-later
 * @link    https://www.mediawiki.org/wiki/Extension:TabberNeue
 */

declare( strict_types=1 );

namespace TabberNeue;

use Parser;
use PPFrame;

class Tabber {
	/**
	 * Renders the necessary HTML for a <tabber> tag.
	 *
	 * @param string $input The input URL between the beginning and ending tags.
	 * @param array $args Array of attribute arguments on that beginning tag.
	 * @param Parser $parser Mediawiki Parser Object
	 * @param PPFrame $frame Mediawiki PPFrame Object
	 *
	 * @return string HTML
	 */
	public static function renderTabber( $input, array $args, Parser $parser, PPFrame $frame ) {
		$parser->getOutput()->addModules( [ 'ext.tabberNeue' ] );
		if ( $input === null ) {
			return;
		}
		$arr = explode( "|-|", $input );
		$htmlTabs = '';
		foreach ( $arr as $tab ) {
			$htmlTabs .= self::buildTab( $tab, $parser, $frame );
		}

		$html = '<div class="tabber">' .
			'<section class="tabber__section">' . $htmlTabs . "</section></div>";

		return $html;
	}

	/**
	 * Build individual tab.
	 *
	 * @param string $tab Tab information
	 * @param Parser $parser Mediawiki Parser Object
	 * @param PPFrame $frame Mediawiki PPFrame Object
	 *
	 * @return string HTML
	 */
	private static function buildTab( $tab, Parser $parser, PPFrame $frame ) {
		$tab = trim( $tab );
		if ( empty( $tab ) ) {
			return $tab;
		}

		// Use array_pad to make sure at least 2 array values are always returned
		list( $tabName, $tabBody ) = array_pad( array_map( 'trim', explode( '=', $tab, 2 ) ), 2, '' );

		$tabBody = $parser->recursiveTagParseFully( $tabBody, $frame );

		$tab = '<article class="tabber__panel" title="' . htmlspecialchars( $tabName ) .
			'">' . $tabBody . '</article>';

		return $tab;
	}
}