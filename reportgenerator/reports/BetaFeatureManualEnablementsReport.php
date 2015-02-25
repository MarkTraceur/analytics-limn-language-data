<?php

class BetaFeatureManualEnablementsReport implements ReportInterface {
	public function run( $config ) {
		$languages = $config['languages'];

		$wikis = array();
		foreach ( $languages as $language ) {
			$wikis[$language] = $language . 'wiki';
		}

		$sql = "select current_date() as date,\n\t";
		$sql .= implode( ",\n\t", $wikis );
		$sql .= "\n\tfrom\n";

		$languageFirst = array_shift( $languages );
		$wikiFirst = array_shift( $wikis );
		$sql .= "( select count(*) as $wikiFirst from $wikiFirst.user_properties where up_property = 'cx' and up_value = 1 ";
		$sql .= "and up_user not in ( select up_user from $wikiFirst.user_properties where up_property = 'betafeatures-auto-enroll' and up_value = 1 ) ) $languageFirst\nleft join\n";

		$lines = array();
		foreach( $languages as $language ) {
			$wiki = $wikis[$language];
			$main = "( select count(*) as $wiki from $wiki.user_properties where up_property = 'cx' and up_value = 1 ";
			$sub = "and up_user not in ( select up_user from $wiki.user_properties where up_property = 'betafeatures-auto-enroll' and up_value = 1 ) ) $language on 1=1\n";
			$lines[] = $main . $sub;
		}
		$sql .= implode( "left join\n", $lines ) .  ";\n";

		return $sql;
	}
}
