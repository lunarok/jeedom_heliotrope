<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once __DIR__ . '/../../../../core/php/core.inc.php';

class heliotrope extends eqLogic {
	public static $_widgetPossibility = array('custom' => true);

	public static function cronDaily() {
		log::add(__CLASS__, 'debug', 'call daily');
		foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
			$heliotrope->getDaily();
		}
	}

	public static function cron() {
		log::add(__CLASS__, 'debug', 'call cron');
		foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
			$heliotrope->getInformations();
		}
	}

	// Return altitude correction for altitude due to atmospheric refraction.
	// http://en.wikipedia.org/wiki/Atmospheric_refraction
	public static function correctForRefraction($d) {
		if (!($d > -0.5)) {
			$d = -0.5; // Function goes ballistic when negative.
		}
		return (0.017 / tan(deg2rad($d + 10.3 / ($d + 5.11))));
	}

	// Return the right ascension of the sun at Unix epoch t.
	// http://bodmas.org/kepler/sun.html
	public static function sunAbsolutePositionDeg($t) {
		$dSec = $t - 946728000;
		$meanLongitudeDeg = fmod((280.461 + 0.9856474 * $dSec / 86400), 360);
		$meanAnomalyDeg = fmod((357.528 + 0.9856003 * $dSec / 86400), 360);
		$deg2radMeanAnomalyDeg = deg2rad($meanAnomalyDeg);
		$eclipticLongitudeDeg = $meanLongitudeDeg + 1.915 * sin($deg2radMeanAnomalyDeg) + 0.020 * sin(2 * $deg2radMeanAnomalyDeg);
		$eclipticObliquityDeg = 23.439 - 0.0000004 * $dSec / 86400;
		$deg2radEclipticObliquityDeg = deg2rad($eclipticObliquityDeg);
		$deg2radEclipticLongitudeDeg = deg2rad($eclipticLongitudeDeg);
		$deg2radEclipticLongitudeDegSin = sin($deg2radEclipticLongitudeDeg);
		$sunAbsY = cos($deg2radEclipticObliquityDeg) * $deg2radEclipticLongitudeDegSin;
		$sunAbsX = cos($deg2radEclipticLongitudeDeg);
		$rightAscensionRad = atan2($sunAbsY, $sunAbsX);
		$declinationRad = asin(sin($deg2radEclipticObliquityDeg) * $deg2radEclipticLongitudeDegSin);
		return array(rad2deg($rightAscensionRad), rad2deg($declinationRad));
	}

	// Convert an object's RA/Dec to altazimuth coordinates.
	// http://answers.yahoo.com/question/index?qid=20070830185150AAoNT4i
	// http://www.jgiesen.de/astro/astroJS/siderealClock/

	public static function absoluteToRelativeDeg($t, $rightAscensionDeg, $declinationDeg, float $latitude, float $longitude) {
		$dSec = $t - 946728000;
		$fmodDSec86400 = fmod($dSec, 86400);
		$midnightUtc = $dSec - $fmodDSec86400;
		$siderialUtcHours = fmod((18.697374558 + 0.06570982441908 * $midnightUtc / 86400 + (1.00273790935 * $fmodDSec86400 / 3600)), 24);
		$siderialLocalDeg = fmod((($siderialUtcHours * 15) + $longitude), 360);
		$hourAngleDeg = fmod(($siderialLocalDeg - $rightAscensionDeg), 360);
		$deg2radHourAngleDeg = deg2rad($hourAngleDeg);
		$deg2radDeclinationDeg = deg2rad($declinationDeg);
		$deg2radDeclinationDegSin = sin($deg2radDeclinationDeg);
		$deg2radDeclinationDegCos = cos($deg2radDeclinationDeg);
		$deg2radLatitude = deg2rad($latitude);
		$deg2radLatitudeSin = sin($deg2radLatitude);
		$deg2radLatitudeCos = cos($deg2radLatitude);
		$altitudeRad = asin($deg2radDeclinationDegSin * $deg2radLatitudeSin + $deg2radDeclinationDegCos * $deg2radLatitudeCos * cos($deg2radHourAngleDeg));
		$azimuthY = -$deg2radDeclinationDegCos * $deg2radLatitudeCos * sin($deg2radHourAngleDeg);
		$azimuthX = $deg2radDeclinationDegSin - $deg2radLatitudeSin * sin($altitudeRad);
		$azimuthRad = atan2($azimuthY, $azimuthX);
		return array(rad2deg($azimuthRad), rad2deg($altitudeRad));
	}

	public function createCmd($logicalId, $name, $type = 'info', $subtype = 'string', $icon = false, $generic_type = null, $configurationList = [], $placeholderList = []) {
		$cmd = $this->getCmd(null, $logicalId);
		if (!is_object($cmd)) {
			$cmd = new AndroidRemoteControlCmd();
			$cmd->setLogicalId($logicalId);
			$cmd->setName(__($name, __FILE__));
		}
		$cmd->setType($type);
		$cmd->setSubType($subtype);
		$cmd->setGeneric_type($generic_type);
		if($icon) {
			$cmd->setDisplay('icon',$icon);
		}
		foreach ($configurationList as $key => $value){
			$cmd->setConfiguration($key, $value);
		}
		foreach ($placeholderList as $value){
			$cmd->setDisplay($value . '_placeholder', __('placeholder.'.$value, __FILE__));
		}
		$cmd->setEqLogic_id($this->getId());
		return $cmd;
	}

	public function postUpdate() {
		$this->createCmd('refresh', __('Rafraichir', __FILE__), 'action', 'other', false)->save();

		$configurationList['type'] = 'time';
		$this->createCmd('daytext', __('Phase du jour en cours texte', __FILE__), 'info', 'string', false, null, $configurationList)->save();
		$configurationList['repeatEventManagement'] = 'always';
		$this->createCmd('zenith', __('Zenith du Soleil', __FILE__), 'info', 'time', false, null, $configurationList)->save();
		$this->createCmd('daylen', __('Durée du Jour', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('daystatus', __('Phase du jour en cours numérique', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		
		$configurationList['type'] = 'position';
		$this->createCmd('azimuth360', __('Azimuth 360 du Soleil', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('altitude', __('Altitude du Soleil', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();

		$configurationList['type'] = 'lever';
		$this->createCmd('sunrise', __('Lever du Soleil', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('civil_twilight_begin', __('Aube Civile', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('nautical_twilight_begin', __('Aube Nautique', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('astronomical_twilight_begin', __('Aube Astronomique', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();

		$configurationList['type'] = 'coucher';
		$this->createCmd('sunset', __('Coucher du Soleil', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('civil_twilight_end', __('Crépuscule Civil', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('nautical_twilight_end', __('Crépuscule Nautique', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();
		$this->createCmd('astronomical_twilight_end', __('Crépuscule Astronomique', __FILE__), 'info', 'numeric', false, null, $configurationList)->save();

		$this->getInformations();
		$this->getDaily();
	}

	public function getLatitudeLongitude() {
		$geoloc = $this->getConfiguration('geoloc');
		if ($geoloc == 'jeedom') {
			return array(config::byKey('info::latitude'), config::byKey('info::longitude'));
		} else {
			$geotrav = eqLogic::byId($geoloc);
			if (!(is_object($geotrav) && $geotrav->getEqType_name() == 'geotrav')) {
				return;
			}
			$geolocval = geotravCmd::byEqLogicIdAndLogicalId($geoloc, 'location:coordinate')->execCmd();
			return explode(',', trim($geolocval));
		}
	}

	public function getInformations() {
		list($latitude, $longitude) = $this->getLatitudeLongitude();
		$time = time();
		$time_Hi = date('Hi', $time);
		list($ra, $dec) = heliotrope::sunAbsolutePositionDeg($time);
		list($az, $alt) = heliotrope::absoluteToRelativeDeg($time, $ra, $dec, $latitude, $longitude);
		$alt += heliotrope::correctForRefraction($alt);
		$az360 = $az;
		if (0 > $az360) {
			$az360 += 360;
		}

		$azimuth360 = $az360;
		$altitude = $alt;

		$astronomical_twilight_begin = $this->getCmd('info', 'astronomical_twilight_begin')->execCmd();
		$astronomical_twilight_end = $this->getCmd('info', 'astronomical_twilight_end')->execCmd();
		$nautical_twilight_begin = $this->getCmd('info', 'nautical_twilight_begin')->execCmd();
		$nautical_twilight_end = $this->getCmd('info', 'nautical_twilight_end')->execCmd();
		$civil_twilight_begin = $this->getCmd('info', 'civil_twilight_begin')->execCmd();
		$civil_twilight_end = $this->getCmd('info', 'civil_twilight_end')->execCmd();
		$sunrise = $this->getCmd('info', 'sunrise')->execCmd();
		$sunset = $this->getCmd('info', 'sunset')->execCmd();

		if($time_Hi < $astronomical_twilight_begin || $time_Hi > $astronomical_twilight_end) {
			$status = 0;
			$texte = "Nuit";
		} elseif ($time_Hi < $nautical_twilight_begin) {
			$status = 4;
			$texte = "Aube Astronomique";
		} elseif ($time_Hi < $civil_twilight_begin) {
			$status = 3;
			$texte = "Aube Nautique";
		} elseif ($time_Hi < $sunrise) {
			$status = 2;
			$texte = "Aube Civile";
		} elseif ($time_Hi < $sunset) {
			$status = 1;
			$texte = "Jour";
		} elseif ($time_Hi < $civil_twilight_end) {
			$status = 5;
			$texte = "Crépuscule Civile";
		} elseif ($time_Hi < $nautical_twilight_end) {
			$status = 6;
			$texte = "Crépuscule Nautique";
		} elseif ($time_Hi < $astronomical_twilight_end) {
			$status = 7;
			$texte = "Crépuscule Astronomique";
		}

		$this->checkAndUpdateCmd('azimuth360', round($azimuth360));
		$this->checkAndUpdateCmd('altitude', round($altitude, 1));
		$this->checkAndUpdateCmd('daystatus', $status);
		$this->checkAndUpdateCmd('daytext', $texte);
		log::add(__CLASS__, 'debug', 'Statut ' . $status . ' ' . $texte . ' ' . round($azimuth360) . ' ' . round($altitude, 1));
	}

	public function getDaily() {
		list($latitude, $longitude) = $this->getLatitudeLongitude();
		$zenith = $this->getConfiguration('zenith', '90.58');
		$timezone = config::byKey('timezone');

		$dateTimeZone = new DateTimeZone($timezone);
		$nowAt4 = date('Y-m-d 04:00:00');
		$now = new DateTime($nowAt4 , $dateTimeZone);
		$offset = $dateTimeZone->getOffset($now);

		log::add(__CLASS__, 'debug', 'Configuration : timezone ' . $timezone . ' offset ' . $offset);
		log::add(__CLASS__, 'debug', 'Configuration : latitude ' . $latitude . ' longitude ' . $longitude . ' zenith ' . $zenith);

		$sun_info = date_sun_info(strtotime($nowAt4), $latitude, $longitude);

		$this->checkAndUpdateCmd('sunrise', date("Hi", $sun_info['sunrise']));
		$this->checkAndUpdateCmd('sunset', date("Hi", $sun_info['sunset']));
		$this->checkAndUpdateCmd('nautical_twilight_begin', date("Hi", $sun_info['nautical_twilight_begin']));
		$this->checkAndUpdateCmd('nautical_twilight_end', date("Hi", $sun_info['nautical_twilight_end']));
		$this->checkAndUpdateCmd('civil_twilight_begin', date("Hi", $sun_info['civil_twilight_begin']));
		$this->checkAndUpdateCmd('civil_twilight_end', date("Hi", $sun_info['civil_twilight_end']));
		$this->checkAndUpdateCmd('astronomical_twilight_begin', date("Hi", $sun_info['astronomical_twilight_begin']));
		$this->checkAndUpdateCmd('astronomical_twilight_end', date("Hi", $sun_info['astronomical_twilight_end']));
		$this->checkAndUpdateCmd('zenith', date("Hi", $sun_info['transit']));
		$daylen = $sun_info['sunset'] - $sun_info['sunrise'];
		$this->checkAndUpdateCmd('daylen', ($daylen / 3600 % 24) . ($daylen / 60 % 60));
	}

	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		/* ------------ Ajouter votre code ici ------------ */
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = str_replace(array("\'", "'"), array("'", "\'"), $cmd->execCmd());
			$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
			if ($cmd->getLogicalId() == 'encours') {
				$replace['#thumbnail#'] = $cmd->getDisplay('icon');
			}
			if ($cmd->getIsHistorized() == 1) {
				$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
			}
		}

		$fileTemplate = __CLASS__;
		if (file_exists(__DIR__ . "/../template/$_version/custom.heliotrope.html")) {
			$fileTemplate = 'custom.heliotrope';
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, $fileTemplate, __CLASS__)));
	}
}

class heliotropeCmd extends cmd {

	public function execute($_options = null) {
		if ($this->getType() == 'info') {
			return;
		}

		$eqLogic = $this->getEqLogic();
		switch ($this->getLogicalId()) {
			case 'refresh' :
			$eqLogic->getInformations();
			break;
		default :
			log::add(__CLASS__, 'info', 'TODO: Créer la commande ' . $this->getLogicalId() . ' - ' . print_r($_options, true));
		}
	}

}