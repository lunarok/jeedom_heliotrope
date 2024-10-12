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

  public static function pull() {
    foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
      log::add(__CLASS__, 'debug', 'info ' .__FUNCTION__ .' ' .$heliotrope->getName());
      $heliotrope->getInformations();
    }
  }
  /*
  public static function cron() {
    foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
      log::add(__CLASS__, 'debug', 'info ' .__FUNCTION__ .' ' .$heliotrope->getName());
      $heliotrope->getDaily();
      $heliotrope->getInformations();
    }
  }
   */

  public static function cronHourly() {
    if (date('G')  == 3) {
      foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
        log::add(__CLASS__, 'debug', 'info ' .__FUNCTION__ .' ' .$heliotrope->getName());
        $heliotrope->getDaily();
        $heliotrope->getInformations();
      }
    }
  }

  public static function start() {
    foreach (eqLogic::byType(__CLASS__, true) as $heliotrope) {
      log::add(__CLASS__, 'debug', 'info ' .__FUNCTION__ .' ' .$heliotrope->getName());
      $heliotrope->getDaily();
      $heliotrope->getInformations();
    }
  }

  public function postUpdate() {
    if(is_object($this)) {
        // dimensionnement mini des graphiques suivant dimension des tuiles
      $array = $this->getDisplay('parameters');
      if(!is_array($array)) $array = array();
      if(!isset($array['elevationWidth'])) $w1 = 0;
      else $w1 = $array['elevationWidth'];
      if(!isset($array['elevationHeight'])) $h1 = 0;
      else $h1 = $array['elevationHeight'];
      if(!isset($array['azimuthSize'])) $s1 = 0;
      else $s1 = $array['azimuthSize'];
      if($w1==0 && $h1==0 && $s1==0) {
        $name = $this->getName();
        $w = intval($this->getDisplay('width'));
        $h = intval($this->getDisplay('height'));
        log::add(__CLASS__,'debug',__FUNCTION__ . " [$name] W=$w H=$h\nW1=$w1 H1=$h1 S1=$s1");
        if($h > 400) { // alt on azt
          $s1 = 120; $w1 = $w; $h1 = $h -120 - 30;
        }
        else { // azt to the right of alt
          $s1 = 100; $w1 = $w - 110; $h1 = $h - 30;
        }
        if($w1< 100) $w1=250;
        if($h1< 100) $h1=200;
        log::add(__CLASS__,'debug',"New display W=$w1 H=$h1 S=$s1");
        $array['elevationWidth'] = $w1;
        $array['elevationHeight'] = $h1;
        $array['azimuthSize'] = $s1;
        $this->setDisplay('parameters',$array);
        $this->save();
      }
        // creation/maj des commandes
      $eqLogicId = $this->getId();
      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'azimuth360');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Azimuth 360 du Soleil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('azimuth360');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'position');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'altitude');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Altitude du Soleil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('altitude');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'position');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'sunrise');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Lever du Soleil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('sunrise');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'lever');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'sunset');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Coucher du Soleil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('sunset');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'coucher');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'aubeciv');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Aube Civile', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('aubeciv');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'lever');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'crepciv');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Crépuscule Civil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('crepciv');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'coucher');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'aubenau');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Aube Nautique', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('aubenau');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'lever');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'crepnau');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Crépuscule Nautique', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('crepnau');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'coucher');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'aubeast');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Aube Astronomique', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('aubeast');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'lever');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'crepast');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Crépuscule Astronomique', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('crepast');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'coucher');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'zenith');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Zenith du Soleil', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('zenith');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'time');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'daylen');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Durée du jour en minutes', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('daylen');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'time');
      $heliotropeCmd->setConfiguration('repeatEventManagement', 'always');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'daystatus');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Phase du jour en cours numérique', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('daystatus');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('numeric');
      }
      $heliotropeCmd->setConfiguration('type', 'time');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'daytext');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Phase du jour en cours texte', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('daytext');
        $heliotropeCmd->setType('info');
        $heliotropeCmd->setSubType('string');
      }
      $heliotropeCmd->setConfiguration('type', 'time');
      $heliotropeCmd->save();

      $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($eqLogicId, 'refresh');
      if (!is_object($heliotropeCmd)) {
        $heliotropeCmd = new heliotropeCmd();
        $heliotropeCmd->setName(__('Rafraichir', __FILE__));
        $heliotropeCmd->setEqLogic_id($eqLogicId);
        $heliotropeCmd->setLogicalId('refresh');
        $heliotropeCmd->setType('action');
        $heliotropeCmd->setSubType('other');
        $heliotropeCmd->save();
      }

      heliotrope::getInformations();
      heliotrope::getDaily();
    }
  }

    // Return altitude correction for altitude due to atmospheric refraction.
    // http://en.wikipedia.org/wiki/Atmospheric_refraction
  public static function correctForRefraction($d) {
    if (!($d > -0.5))      $d = -0.5;  // Function goes ballistic when negative.
    return (0.017 / tan(deg2rad($d + 10.3 / ($d + 5.11))));
  }

    // Return the right ascension of the sun at Unix epoch t.
    // http://bodmas.org/kepler/sun.html
  public static function sunAbsolutePositionDeg($t) {
    $dSec = $t - 946728000;
    $meanLongitudeDeg = fmod((280.461 + 0.9856474 * $dSec / 86400), 360);
    $meanAnomalyDeg = fmod((357.528 + 0.9856003 * $dSec / 86400), 360);
    $eclipticLongitudeDeg = $meanLongitudeDeg + 1.915 * sin(deg2rad($meanAnomalyDeg)) + 0.020 * sin(2 * deg2rad($meanAnomalyDeg));
    $eclipticObliquityDeg = 23.439 - 0.0000004 * $dSec / 86400;
    $sunAbsY = cos(deg2rad($eclipticObliquityDeg)) * sin(deg2rad($eclipticLongitudeDeg));
    $sunAbsX = cos(deg2rad($eclipticLongitudeDeg));
    $rightAscensionRad = atan2($sunAbsY, $sunAbsX);
    $declinationRad = asin(sin(deg2rad($eclipticObliquityDeg)) * sin(deg2rad($eclipticLongitudeDeg)));
    return array(rad2deg($rightAscensionRad), rad2deg($declinationRad));
  }

    // Convert an object's RA/Dec to altazimuth coordinates.
    // http://answers.yahoo.com/question/index?qid=20070830185150AAoNT4i
    // http://www.jgiesen.de/astro/astroJS/siderealClock/
  public static function absoluteToRelativeDeg($t, $rightAscensionDeg, $declinationDeg, $latitude, $longitude) {
    $longitude = (float) $longitude;
    $latitude = (float) $latitude;
    $dSec = $t - 946728000;
    $midnightUtc = $dSec - fmod($dSec, 86400);
    $siderialUtcHours = fmod((18.697374558 + 0.06570982441908 * $midnightUtc / 86400 + (1.00273790935 * (fmod($dSec, 86400)) / 3600)), 24);
    $siderialLocalDeg = fmod((($siderialUtcHours * 15) + $longitude), 360);
    $hourAngleDeg = fmod(($siderialLocalDeg - $rightAscensionDeg), 360);
    $altitudeRad = asin(sin(deg2rad($declinationDeg)) * sin(deg2rad($latitude)) + cos(deg2rad($declinationDeg)) * cos(deg2rad($latitude)) * cos(deg2rad($hourAngleDeg)));
    $azimuthY = -cos(deg2rad($declinationDeg)) * cos(deg2rad($latitude)) * sin(deg2rad($hourAngleDeg));
    $azimuthX = sin(deg2rad($declinationDeg)) - sin(deg2rad($latitude)) * sin($altitudeRad);
    $azimuthRad = atan2($azimuthY, $azimuthX);
    return array(rad2deg($azimuthRad), rad2deg($altitudeRad));
  }

  public function getLatitudeLongitude(&$latitude,&$longitude) {
    if ($this->getConfiguration('geoloc') == 'jeedom') {
      $latitude = config::byKey('info::latitude');
      $longitude = config::byKey('info::longitude');
      return(0);
    } else {
      $geotrav = eqLogic::byId($this->getConfiguration('geoloc'));
      if (!(is_object($geotrav) && $geotrav->getEqType_name() == 'geotrav')) {
        log::add(__CLASS__, 'error', 'Localisation invalide, veuillez sélectionner un équipement geotrav valide');
        return(1);
      }
      $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'), 'location:coordinate')->execCmd();
      $geoloctab = explode(',', trim($geolocval));
      $latitude = trim($geoloctab[0]);
      $longitude = trim($geoloctab[1]);
      return(0);
    }
  }

  public function getInformations() {
    if($this->getLatitudeLongitude($latitude,$longitude)) {
      log::add(__CLASS__, 'error', __FUNCTION__ ." Latitude et longitude non connues.");
        return;
      }
    else {
    log::add(__CLASS__, 'debug', __FUNCTION__ ." Latitude: $latitude Longitude: $longitude");
    }

      // MAJ position actuelle du soleil
    self::getAltAzt(time(),$latitude,$longitude,$altitude,$azimuth360);
    $this->checkAndUpdateCmd('azimuth360', round($azimuth360));
    $this->checkAndUpdateCmd('altitude', round($altitude, 1));

      // calcul jour, nuit, aubes crepuscules pour MAJ daystatus et daytext
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'aubeast');
    if (is_object($cmd)) $aubeast = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'crepast');
    if (is_object($cmd)) $crepast = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'aubenau');
    if (is_object($cmd)) $aubenau = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'crepnau');
    if (is_object($cmd)) $crepnau = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'aubeciv');
    if (is_object($cmd)) $aubeciv = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'crepciv');
    if (is_object($cmd)) $crepciv = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'sunrise');
    if (is_object($cmd)) $sunrise = $cmd->execCmd();
    else return;
    $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(), 'sunset');
    if (is_object($cmd)) $sunset = $cmd->execCmd();
    else return;
    $actual =  date('Hi');
    if ($actual > $sunrise && $actual < $sunset) {
        $status = 1;
        $texte = "Jour";
    } elseif ($actual > $aubeciv && $actual <= $sunrise) {
        $status = 2;
        $texte = "Aube Civile";
    } elseif ($actual > $aubenau && $actual <= $aubeciv) {
        $status = 3;
        $texte = "Aube Nautique";
    } elseif ($actual > $aubeast && $actual <= $aubenau) {
        $status = 4;
        $texte = "Aube Astronomique";
    } elseif ($actual >= $sunset && $actual < $crepciv) {
        $status = 5;
        $texte = "Crépuscule Civil";
    } elseif ($actual >= $crepciv && $actual < $crepnau) {
        $status = 6;
        $texte = "Crépuscule Nautique";
    } elseif ($actual >= $crepnau && $actual < $crepast) {
        $status = 7;
        $texte = "Crépuscule Astronomique";
    } else {
        $status = 0;
        $texte = "Nuit";
    }
    $this->checkAndUpdateCmd('daystatus', $status);
    $this->checkAndUpdateCmd('daytext', $texte);

    log::add(__CLASS__, 'debug', 'Statut ' . $status . ' ' . $texte . ' ' . round($azimuth360) . ' ' . round($altitude));
    $this->refreshWidget();
  }

  public function getDaily() {
    if($this->getLatitudeLongitude($latitude,$longitude)) {
      log::add(__CLASS__, 'error', __FUNCTION__ ." Latitude et longitude non connues.");
        return;
      }
    else {
      log::add(__CLASS__, 'debug', __FUNCTION__ ." Latitude: $latitude Longitude: $longitude");
    }

      // Calcul lever, coucher, durée jour
    $sun_info = date_sun_info(time(), $latitude, $longitude);
    $sunrise = date('Gi',$sun_info['sunrise']);
    $this->checkAndUpdateCmd('sunrise', $sunrise);
    if( $sun_info['sunrise'] === true ) { // jour toute la journée
      $daylen =24*60;
      log::add(__CLASS__, 'debug', __FUNCTION__,"Lat: $latitude Sunrise True");
    }
    else if( $sun_info['sunrise'] === false ) { // nuit toute la journée
      $daylen =0;
      log::add(__CLASS__, 'debug', __FUNCTION__,"Lat: $latitude Sunrise False");
    }
    else {
      $daylen = round(($sun_info['sunset'] - $sun_info['sunrise'])/60);
    }
    $this->checkAndUpdateCmd('daylen', $daylen);
    $zenith = date("Gi", $sun_info['transit']);
    $this->checkAndUpdateCmd('zenith', $zenith);
    $sunset = date('Gi',$sun_info['sunset']);
    $this->checkAndUpdateCmd('sunset', $sunset);
      // Calcul aubes et crepsucules civil nautique, astronomique
    $aubeciv = date('Gi',$sun_info['civil_twilight_begin']);
    $this->checkAndUpdateCmd('aubeciv', $aubeciv);
    $crepciv = date('Gi',$sun_info['civil_twilight_end']);
    $this->checkAndUpdateCmd('crepciv', $crepciv);
    $aubenau = date('Gi',$sun_info['nautical_twilight_begin']);
    $this->checkAndUpdateCmd('aubenau', $aubenau);
    $crepnau = date('Gi',$sun_info['nautical_twilight_end']);
    $this->checkAndUpdateCmd('crepnau', $crepnau);
    $aubeast = date('Gi',$sun_info['astronomical_twilight_begin']);
    $this->checkAndUpdateCmd('aubeast', $aubeast);
    $crepast = date('Gi',$sun_info['astronomical_twilight_end']);
    $this->checkAndUpdateCmd('crepast', $crepast);

    $this->refreshWidget();
  }

  public function getGeoloc($_infos = '') {
    $return = array();
    foreach (eqLogic::byType('geoloc') as $geoloc) {
        foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
            if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
                $return[$geoinfo->getId()] = array( 'value' => $geoinfo->getName());
            }
        }
    }
    return $return;
  }

  public static function setupCron() {
    $setting = config::byKey('cron', __CLASS__);
    $cron = cron::byClassAndFunction(__CLASS__, 'pull');
    if (!is_object($cron)) {
      $cron = new cron();
      $cron->setClass(__CLASS__);
      $cron->setFunction('pull');
      $cron->setEnable(1);
      $cron->setDeamon(0);
    }
    if ($setting == '60') {
      $cron->setSchedule('0 * * * *');
    } else {
      $cron->setSchedule('*/' .$setting . ' * * * *');
    }
    $cron->save();
    return true;
  }

  public function toHtml($_version = 'dashboard') {
    $t0 = microtime(true);
    if($this->getConfiguration('useHelioTemplate','1') == '0')
      return parent::toHtml($_version);
    $replace = $this->preToHtml($_version);
    if (!is_array($replace)) {
      return $replace;
    }
    $version = jeedom::versionAlias($_version);
    if ($this->getDisplay('hideOn' . $version) == 1) {
      return '';
    }

    if($this->getLatitudeLongitude($latitude,$longitude)) {
      log::add(__CLASS__, 'warning', __FUNCTION__ ." Latitude et longitude non connues.");
      return;
    }
    else {
      log::add(__CLASS__, 'debug', __FUNCTION__ ." Latitude: $latitude Longitude: $longitude");
    }

    $id = array(); $value = array(); $display = array(); $history = array();
    foreach ($this->getCmd('info') as $cmd) {
      $type_cmd = $cmd->getLogicalId();
      $id[$type_cmd] = $cmd->getId();
      $value[$type_cmd] = $cmd->execCmd();
      $display[$type_cmd] = ($cmd->getIsVisible()) ? "visible" : "none";
      $history[$type_cmd] = ($cmd->getIsHistorized() == 1) ? 'history cursor' : '';
    }
    if(!isset($replace['#elevationWidth#'])) $replace['#elevationWidth#']=320;
    if(!isset($replace['#elevationHeight#'])) $replace['#elevationHeight#']=220;
    if(!isset($replace['#azimuthSize#'])) $replace['#azimuthSize#']=120;

      // latitude longitude dans le titre de la tuile
    $lat = round($latitude,1);
    $replace['#latitude#'] = $lat .(($lat>=0)? "°N" : "°S");
    $lon = round($longitude,1);
    $replace['#longitude#'] = $lon .(($lon>=0)? "°E" : "°W");
    /*
      https://suncalc.org/#/lat,lon,zoom/date/time/objectlevel/maptype
      Format voir:
      https://www.torsten-hoffmann.de/apis/suncalcmooncalc/link_en.html
     */
    $replace['#suncalcUrl#'] = "https://www.suncalc.org/#/$latitude,$longitude,null/null/null/null/null";
    $replace['#azimuth360#'] = $value['azimuth360'];
    $replace['#azimuth360_id#'] = $id['azimuth360'];
    $replace['#azimuth360_display#'] = $display['azimuth360'];
    $replace['#sunAzt_id#'] = $id['azimuth360'];
    $replace['#sunAzt_display#'] = $display['azimuth360'];
    $replace['#sunAzt_history#'] = $history['azimuth360'];
    $replace['#altitude#'] = $value['altitude'];
    $replace['#sunAlt_id#'] = $id['altitude'];
    $replace['#sunAlt_display#'] = $display['altitude'];
    $replace['#sunAlt_history#'] = $history['altitude'];
    $replace['#sunrise#'] = substr_replace($value['sunrise'], ':', -2, 0);
    $replace['#sunset#'] = substr_replace($value['sunset'], ':', -2, 0);
    $replace['#aubeciv#'] = substr_replace($value['aubeciv'], ':', -2, 0);
    $replace['#crepciv#'] = substr_replace($value['crepciv'], ':', -2, 0);
    $replace['#aubenau#'] = substr_replace($value['aubenau'], ':', -2, 0);
    $replace['#crepnau#'] = substr_replace($value['crepnau'], ':', -2, 0);
    $replace['#aubeast#'] = substr_replace($value['aubeast'], ':', -2, 0);
    $replace['#crepast#'] = substr_replace($value['crepast'], ':', -2, 0);
    $replace['#zenith#'] = substr_replace($value['zenith'], ':', -2, 0);
    if($display['daylen'] == "none") $replace['#daylenTxt#'] = '';
    else {
      $valueDaylen = isset($value['daylen'])? intval($value['daylen']) : 0;
      $h = floor($valueDaylen / 60); $mn = $valueDaylen % 60;
      $daylen = (($h)? "{$h}h " : '') .(($mn)? "{$mn}min " : '');
        // Calcul variation durée jour
      $sunInfo = date_sun_info(strtotime('today noon'), $latitude, $longitude);
      $sunInfoHier = date_sun_info(strtotime('yesterday noon'), $latitude, $longitude);
      $daylenToday = $sunInfo['sunset'] - $sunInfo['sunrise'];
      $daylenHier = $sunInfoHier['sunset'] - $sunInfoHier['sunrise'];
      if($daylen == '') $daylenTxt = "Durée nuit: 24h ";
      else $daylenTxt = "Durée jour: $daylen ";
      if($daylenToday - $daylenHier != 0) $daylenTxt .= sprintf('%+ds',$daylenToday - $daylenHier);
      $replace['#daylenTxt#'] = $daylenTxt;
    }

    $replace['#collectDate#'] = $this->getStatus('lastCommunication', date('Y-m-d H:i:s'));

    $inclin = 23+26/60;
    $replace['#paneStart#'] = -180; // -180-$inclin+$latitude;
    $replace['#paneEnd#'] = 0; // $inclin-$latitude;
// log::add(__CLASS__,'error',"AltMinYear: ". $replace['#paneStart#']." AltMaxYear: ". $replace['#paneEnd#']);
    $minElev = max(-90-$inclin+$latitude,90);
    if($minElev < -90) $minElev += 360;
    if($minElev > 90) $minElev -= 360;

    $replace['#minElev#'] = round(max(-90-$inclin+$latitude,-90),2);
    $replace['#maxElev#'] = round(min(90+$inclin-$latitude,90),2);

    if (array_key_exists('daystatus', $value) && $value['daystatus']=="1") {
      $replace['#heliosun#'] = "opacity : 1";
      $replace['#heliomoon#'] = "opacity : 0.3";
    } else {
      $replace['#heliosun#'] = "opacity : 0.3";
      $replace['#heliomoon#'] = "opacity : 1";
    }

      // Courbe élévation du soleil. Azimut dans les tooltips
    $serie = $serie2 = array();
    $date = strtotime('today midnight');
    $now = time();
    // $date = strtotime('2022-06-21 00:00:00'); $now = strtotime('2022-06-21 12:00:00');
    // message::add(__CLASS__, "Sunrise: " .date('H:i:s',$sunrise) ." Zenith: " .date('H:i:s',$transit) ." Sunset: " .date('H:i:s',$sunset));
    for($i=0;$i<49;$i++) {
      $t = $date+$i*1800;
      self::getAltAzt($t,$latitude,$longitude,$alt,$azt);
      $msg = '{ "x":' .$t*1000 .',"y": ' .$alt .',"z":' .$azt .',"msg":"'.date('G:i',$t) .'"}';
      if($t < $now) $serie[] = $msg;
      else $serie2[] = $msg;
    }
      // Ajout Lever, Zenith, Coucher ... sur la courbe
    $types = array();
    $types[] = array("sunI" => 'sunrise', "txtDsp" => 'Lever', "cmdJ"=>'sunrise');
    $types[] = array("sunI" => 'transit', "txtDsp" => 'Zénith', "cmdJ"=>'zenith');
    $types[] = array("sunI" => 'sunset', "txtDsp" => 'Coucher', "cmdJ"=>'sunset');
    $types[] = array("sunI" => 'civil_twilight_begin', "txtDsp" => 'Aube', "cmdJ"=>'aubeciv');
    $types[] = array("sunI" => 'nautical_twilight_begin', "txtDsp" => 'Début aube nautique', "cmdJ"=>'aubenau');
    $types[] = array("sunI" => 'astronomical_twilight_begin', "txtDsp" => 'Début aube astronomique', "cmdJ"=>'aubeast');
    $types[] = array("sunI" => 'civil_twilight_end', "txtDsp" => 'Crépuscule', "cmdJ"=>'crepciv');
    $types[] = array("sunI" => 'nautical_twilight_end', "txtDsp" => 'Fin crépuscule nautique', "cmdJ"=>'crepnau');
    $types[] = array("sunI" => 'astronomical_twilight_end', "txtDsp" => 'Fin crépuscule astronomique', "cmdJ"=>'crepast');
    $sun_info = date_sun_info($now, $latitude, $longitude);
    $nb = count($types);
    $sunriseTxt = ''; $sunsetTxt = ''; $zenithTxt = '';
    for($i=0;$i<$nb;$i++) {
      $type = $types[$i]['sunI'];
      $t = $sun_info[$type];
      if($t === true || $t === false) continue; // Valeur non atteinte de la journee
      self::getAltAzt($t,$latitude,$longitude,$alt,$azt);
      $texte = $types[$i]['txtDsp'];
      if($type == 'sunrise') {
        $aztsunrise = $azt;
        if($display[$types[$i]['cmdJ']] != "none")
          $sunriseTxt = $texte .': '.date('G:i:s',$t) .'<br/>';
      }
      else if($type == 'sunset') {
        $aztsunset = $azt;
        if($display[$types[$i]['cmdJ']] != "none")
          $sunsetTxt = $texte .': '.date('G:i:s',$t) .'<br/>';
      }
      else if($type == 'transit') {
        if($display[$types[$i]['cmdJ']] != "none")
          $zenithTxt = $texte .': '.date('G:i:s',$t) .'<br/>';
      }
      // if($display[$types[$i]['cmdJ']] == "none") continue;
      $msg = '{ "x":' .$t*1000 .',"y":'.$alt .',"z":' .$azt .',"id":"'.$type.'","msg":"' .$texte .': '.date('G:i:s',$t) .'"}';
      if($t > $now) $serie2[] = $msg; else $serie[] = $msg;
    }
    if( $sun_info['sunrise'] === true ) { // jour toute la journée
      $aztsunrise = 0; $aztsunset = 360;
    }
    else if( $sun_info['sunrise'] === false ) { // nuit toute la journée
      $aztsunrise = 180; $aztsunset = 180;
    }
    $replace['#sunriseTxt#'] = $sunriseTxt;
    $replace['#sunsetTxt#'] = $sunsetTxt;
    $replace['#zenithTxt#'] = $zenithTxt;
    $replace['#aztsunrise#'] = round($aztsunrise,3);
    if($aztsunset < $aztsunrise) $aztsunset += 360; 
    $replace['#aztsunset#'] = round($aztsunset,3);
log::add(__CLASS__,'debug',"AztSunrise: $aztsunrise AztSunset: $aztsunset");

    // Heure actuelle dans les 2 courbes
    self::getAltAzt($now,$latitude,$longitude,$alt,$azt);
    $msg = '{ "x":' .$now*1000 .',"y":'.$alt .',"z":' .$azt .',"msg":"Maintenant '.date('G:i',$now) .'"}';
    $msg2 = '{ "x":' .$now*1000 .',"y":'.$alt .',"marker": { "symbol": \'url(plugins/'.__CLASS__ .'/core/template/sun.png)\'},"z":' .$azt .',"msg":"Maintenant '.date('G:i',$now) .'"}';
    $serie[] = $msg; $serie2[] = $msg2;
    sort($serie); sort($serie2);
    $replace['#altSerie#'] = implode(',',$serie);
    $replace['#altSerie2#'] = implode(',',$serie2);

      // inversion couleurs jour/nuit suivant hemisphere
    if($latitude > 0) {
      $replace['#bandColor1#'] = '#3C73A5';
      $replace['#bandColor2#'] = '#8EBEEB';
    }
    else {
      $replace['#bandColor1#'] = 'transparent';
      $replace['#bandColor2#'] = 'transparent';
    }

    $refresh = $this->getCmd(null, 'refresh');
    $replace['#refresh#'] = is_object($refresh) ? $refresh->getId() : '';

    $t1 = microtime(true);
    $duration = round($t1-$t0,3);
    log::add(__CLASS__, 'debug', "END OF toHtml Duration: {$duration}s. Memory_usage: ".memory_get_usage());
    if (file_exists(__DIR__ . "/../template/$_version/custom." .__CLASS__ .".html"))
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'custom.'.__CLASS__, __CLASS__)));
    else
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, __CLASS__, __CLASS__)));
  }

  public static function getAltAzt($time, $lat, $lon, &$alt, &$azt) {
    list($ra,$dec)=self::sunAbsolutePositionDeg($time);
    list($az, $alt) = self::absoluteToRelativeDeg($time, $ra, $dec, $lat, $lon);
    $alt=$alt+self::correctForRefraction($alt);
    if (0 > $az)  $az += 360;
    $azt = $az;
  }
}

class heliotropeCmd extends cmd {
  public function execute($_options = null) {
    if ($this->getLogicalId() == 'refresh') {
      $eqLogic = $this->getEqLogic();
      $eqLogic->getInformations();
    }
  }
}
