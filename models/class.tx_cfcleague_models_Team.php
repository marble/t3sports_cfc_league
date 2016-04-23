<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007-2009 Rene Nitzsche (rene@system25.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_rnbase_model_base');

/**
 * Model für ein Team.
 */
class tx_cfcleague_models_Team extends tx_rnbase_model_base {
	private static $instances = array();

	function getTableName(){return 'tx_cfcleague_teams';}

	public function getName() {
		return $this->record['name'];
	}
	public function getNameShort() {
		return $this->record['short_name'];
	}
	/**
	 * Liefert true, wenn für das Team eine Einzelansicht verlinkt werden kann.
	 */
	public function hasReport() {
		return intval($this->record['link_report']);
	}
	/**
	 * Returns the url of the first stadium logo.
	 *
	 * @return string
	 */
	public function getLogoPath() {
		if(t3lib_extMgm::isLoaded('dam')) {
			if($this->record['logo']) {
				// LogoFeld
				$media = tx_rnbase::makeInstance('tx_rnbase_model_media', $this->record['logo']);
				return $media->record['file'];
			}
			elseif($this->record['club']) {
				$club = tx_rnbase::makeInstance('tx_cfcleague_models_Club', $this->record['club']);
				return $club->getFirstLogo();
			}
		}
		return '';
	}
	public function getGroupUid() {
		return $this->record['agegroup'];
	}
	/**
	 * Liefert den Verein des Teams als Objekt
	 * @return tx_cfcleague_models_Club Verein als Objekt oder null
	 */
	public function getClub() {
		if(!$this->record['club']) return null;
		return tx_rnbase::makeInstance('tx_cfcleague_models_Club', $this->record['club']);
	}

	public function getClubUid() {
		return $this->record['club'];
	}
	/**
	 * Returns an instance of tx_cfcleague_models_Team
	 * @param int $uid
	 * @return tx_cfcleague_models_Team or null
	 */
	public static function &getInstance($uid, $record = 0) {
		$uid = intval($uid);
		if(!array_key_exists($uid, self::$instances)) {
			$item = new tx_cfcleague_models_Team(is_array($record) ? $record : $uid);
			self::$instances[$uid] = $item->isValid() ? $item : null;
		}
		return self::$instances[$uid];
	}
	public static function addInstance($team) {
		self::$instances[$team->uid] = $team;
	}
	/**
	 * Check if team is a dummy for free_of_match.
	 * @return boolean
	 */
	public function isDummy(){
		return intval($this->record['dummy']) != 0;
	}

	/**
	 * Liefert die Spieler des Teams in der vorgegebenen Reihenfolge als Profile. Der
	 * Key ist die laufende Nummer und nicht die UID!
	 * @return array[tx_cfcleague_models_Profile]
	 */
	public function getPlayers() {
		return $this->getTeamMember('players');
	}
	/**
	 * Liefert die Trainer des Teams in der vorgegebenen Reihenfolge als Profile. Der
	 * Key ist die laufende Nummer und nicht die UID!
	 * @return array[tx_cfcleague_models_Profile]
	 */
	public function getCoaches() {
		return $this->getTeamMember('coaches');
	}

	/**
	 * Liefert Mitglieder des Teams als Array. Teammitglieder sind Spieler, Trainer und Betreuer.
	 * Die gefundenen Profile werden sortiert in der Reihenfolge im Team geliefert.
	 * @column Name der DB-Spalte mit den gesuchten Team-Mitgliedern
	 * @return array[tx_cfcleague_models_Profile]
	 */
	private function getTeamMember($column) {
		if(strlen(trim($this->record[$column])) > 0 ) {
			$what = '*';
			$from = 'tx_cfcleague_profiles';
			$options['where'] = 'uid IN (' .$this->record[$column] . ')';
			$options['wrapperclass'] = 'tx_cfcleague_models_Profile';

			$rows = tx_rnbase_util_DB::doSelect($what,$from,$options,0);
			return $this->sortPlayer($rows, $column);
		}
		return array();
	}

	/**
	 * Sortiert die Personen (Spieler/Trainer) entsprechend der Reihenfolge im Team
	 * @param $profiles array[tx_cfcleague_models_Profile]
	 */
	private function sortProfiles($profiles, $recordKey = 'players') {
		$ret = array();
		if(strlen(trim($this->record[$recordKey])) > 0 ) {
			if(count($profiles)) {
				// Jetzt die Spieler in die richtige Reihenfolge bringen
				$uids = t3lib_div::intExplode(',', $this->record[$recordKey]);
				$uids = array_flip($uids);
				foreach($profiles as $player) {
					$ret[$uids[$player->uid]] = $player;
				}
			}
		}
		else {
			// Wenn keine Spieler im Team geladen sind, dann wird das Array unverändert zurückgegeben
			return $profiles;
		}
		return $ret;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cfc_league/models/class.tx_cfcleague_models_Team.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cfc_league/models/class.tx_cfcleague_models_Team.php']);
}

?>