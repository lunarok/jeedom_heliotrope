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
        foreach (eqLogic::byType('heliotrope', true) as $heliotrope) {
                log::add(__CLASS__, 'debug', 'info daily');
                $heliotrope->getInformations();
        }
    }

    public static function cronHourly() {
        if (date('G')  == 3) {
            foreach (eqLogic::byType('heliotrope', true) as $heliotrope) {
                    log::add(__CLASS__, 'debug', 'info daily');
                    $heliotrope->getDaily();
                    $heliotrope->getInformations();
            }
        }
    }

    public static function start() {
        foreach (eqLogic::byType('heliotrope', true) as $heliotrope) {
                log::add(__CLASS__, 'debug', 'info daily');
                $heliotrope->getDaily();
                $heliotrope->getInformations();
        }
    }

    public function postUpdate() {
        foreach (eqLogic::byType('heliotrope', true) as $heliotrope) {
            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'azimuth360');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Azimuth 360 du Soleil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('azimuth360');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'position');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'altitude');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Altitude du Soleil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('altitude');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'position');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();


            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'sunrise');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Lever du Soleil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('sunrise');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'lever');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'sunset');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Coucher du Soleil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('sunset');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'coucher');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'aubeciv');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Aube Civile', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('aubeciv');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'lever');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'crepciv');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Crépuscule Civil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('crepciv');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'coucher');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'aubenau');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Aube Nautique', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('aubenau');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'lever');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'crepnau');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Crépuscule Nautique', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('crepnau');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'coucher');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'aubeast');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Aube Astronomique', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('aubeast');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'lever');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'crepast');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Crépuscule Astronomique', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('crepast');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'coucher');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'zenith');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Zenith du Soleil', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('zenith');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'time');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'daylen');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Durée du Jour', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('daylen');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('numeric');
            }
            $heliotropeCmd->setConfiguration('type', 'time');
            $heliotropeCmd->setConfiguration('repeatEventManagement','always');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'daystatus');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Phase du jour en cours numérique', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('daystatus');
                $heliotropeCmd->setType('info');
            }
            $heliotropeCmd->setSubType('numeric');
            $heliotropeCmd->setConfiguration('type', 'time');
            $heliotropeCmd->save();

            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($heliotrope->getId(),'daytext');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Phase du jour en cours texte', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->id);
                $heliotropeCmd->setLogicalId('daytext');
                $heliotropeCmd->setType('info');
                $heliotropeCmd->setSubType('string');
            }
            $heliotropeCmd->setConfiguration('type', 'time');
            $heliotropeCmd->save();
            
            $heliotropeCmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'refresh');
            if (!is_object($heliotropeCmd)) {
                $heliotropeCmd = new heliotropeCmd();
                $heliotropeCmd->setName(__('Rafraichir', __FILE__));
                $heliotropeCmd->setEqLogic_id($this->getId());
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
    public function correctForRefraction($d) {
        if (!($d > -0.5))      $d = -0.5;  // Function goes ballistic when negative.
        return (0.017/tan(deg2rad($d + 10.3/($d+5.11))));
    }

    // Return the right ascension of the sun at Unix epoch t.
    // http://bodmas.org/kepler/sun.html
    public function sunAbsolutePositionDeg($t) {
        $dSec = $t - 946728000;
        $meanLongitudeDeg = fmod((280.461 + 0.9856474 * $dSec/86400),360);
        $meanAnomalyDeg = fmod((357.528 + 0.9856003 * $dSec/86400),360);
        $eclipticLongitudeDeg = $meanLongitudeDeg + 1.915 * sin(deg2rad($meanAnomalyDeg)) + 0.020 * sin(2*deg2rad($meanAnomalyDeg));
        $eclipticObliquityDeg = 23.439 - 0.0000004 * $dSec/86400;
        $sunAbsY = cos(deg2rad($eclipticObliquityDeg)) * sin(deg2rad($eclipticLongitudeDeg));
        $sunAbsX = cos(deg2rad($eclipticLongitudeDeg));
        $rightAscensionRad = atan2($sunAbsY, $sunAbsX);
        $declinationRad = asin(sin(deg2rad($eclipticObliquityDeg))*sin(deg2rad($eclipticLongitudeDeg)));
        return array(rad2deg($rightAscensionRad), rad2deg($declinationRad));
    }

    // Convert an object's RA/Dec to altazimuth coordinates.
    // http://answers.yahoo.com/question/index?qid=20070830185150AAoNT4i
    // http://www.jgiesen.de/astro/astroJS/siderealClock/

    public function absoluteToRelativeDeg($t, $rightAscensionDeg, $declinationDeg, $latitude, $longitude){
      	$longitude = (float) $longitude;
        $latitude = (float) $latitude;
        $dSec = $t - 946728000;
        $midnightUtc = $dSec - fmod($dSec,86400);
        $siderialUtcHours = fmod((18.697374558 + 0.06570982441908*$midnightUtc/86400 + (1.00273790935*(fmod($dSec,86400))/3600)),24);
        $siderialLocalDeg = fmod((($siderialUtcHours * 15) + $longitude),360);
        $hourAngleDeg = fmod(($siderialLocalDeg - $rightAscensionDeg),360);
        $altitudeRad = asin(sin(deg2rad($declinationDeg))*sin(deg2rad($latitude)) + cos(deg2rad($declinationDeg)) * cos(deg2rad($latitude)) * cos(deg2rad($hourAngleDeg)));
        $azimuthY = -cos(deg2rad($declinationDeg)) * cos(deg2rad($latitude)) * sin(deg2rad($hourAngleDeg));
        $azimuthX = sin(deg2rad($declinationDeg)) - sin(deg2rad($latitude)) * sin($altitudeRad);
        $azimuthRad = atan2($azimuthY, $azimuthX);
        return array(rad2deg($azimuthRad), rad2deg($altitudeRad));
    }

    public function getInformations() {
        if ($this->getConfiguration('geoloc') == 'jeedom') {
            $latitude = config::byKey('info::latitude');
            $longitude = config::byKey('info::longitude');
        } else {
            $geotrav = eqLogic::byId($this->getConfiguration('geoloc'));
            if (!(is_object($geotrav) && $geotrav->getEqType_name() == 'geotrav')) {
                return;
            }
            $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
            $geoloctab = explode(',', trim($geolocval));
            $latitude = trim($geoloctab[0]);
            $longitude = trim($geoloctab[1]);
        }
        if (!$this->getConfiguration('zenith', '')) {
            $zenith = '90.58';
        } else {
            $zenith = $this->getConfiguration('zenith', '');
        }
        $timezone =  config::byKey('timezone');

        $this_tz = new DateTimeZone($timezone);
        $now = new DateTime("now", $this_tz);
        $offset = $this_tz->getOffset($now) / 3600;

        $t = time();
        list($ra,$dec)=heliotrope::sunAbsolutePositionDeg($t);
        list($az, $alt) = heliotrope::absoluteToRelativeDeg($t, $ra, $dec, $latitude, $longitude);
        $alt=$alt+heliotrope::correctForRefraction($alt);
        $az360=$az;
        if (0 > $az360)  $az360 = $az360 + 360;

        $azimuth360 = $az360;
        $altitude = $alt;
        //date_default_timezone_set("GMT");

        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'aubeast');
        if(is_object($cmd)) $aubeast = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'crepast');
        if(is_object($cmd)) $crepast = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'aubenau');
        if(is_object($cmd)) $aubenau = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'crepnau');
        if(is_object($cmd)) $crepnau = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'aubeciv');
        if(is_object($cmd)) $aubeciv = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'crepciv');
        if(is_object($cmd)) $crepciv = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'sunrise');
        if(is_object($cmd)) $sunrise = $cmd->execCmd();
        $cmd = heliotropeCmd::byEqLogicIdAndLogicalId($this->getId(),'sunset');
        if(is_object($cmd)) $sunset = $cmd->execCmd();

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

        $this->checkAndUpdateCmd('azimuth360',round($azimuth360));
        $this->checkAndUpdateCmd('altitude',round($altitude,1));
        $this->checkAndUpdateCmd('daystatus',$status);
        $this->checkAndUpdateCmd('daytext',$texte);
        log::add(__CLASS__, 'debug', 'Statut ' . $status . ' ' . $texte . ' ' . round($azimuth360) . ' ' . round($altitude));
        $this->refreshWidget();
    }

    public function getDaily() {
        if ($this->getConfiguration('geoloc') == 'jeedom') {
            $latitude = config::byKey('info::latitude');
            $longitude = config::byKey('info::longitude');
        } else {
            $geotrav = eqLogic::byId($this->getConfiguration('geoloc'));
            if (!(is_object($geotrav) && $geotrav->getEqType_name() == 'geotrav')) {
              log::add(__CLASS__, 'error', 'localisation invalide, veuillez sélectionner un équipement geotrav valide');
                return;
            }
            $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
            $geoloctab = explode(',', trim($geolocval));
            $latitude = trim($geoloctab[0]);
            $longitude = trim($geoloctab[1]);
        }
        if (!$this->getConfiguration('zenith', '')) {
            $zenith = '90.58';
        } else {
            $zenith = $this->getConfiguration('zenith', '');
        }
        $timezone =  config::byKey('timezone');

        $this_tz = new DateTimeZone($timezone);
        $now = new DateTime("now", $this_tz);
        $offset = $this_tz->getOffset($now) / 3600;

        log::add(__CLASS__, 'debug', 'Configuration : timezone ' . $timezone . ' offset ' . $offset);
        log::add(__CLASS__, 'debug', 'Configuration : latitude ' . $latitude . ' longitude ' . $longitude . ' zenith ' . $zenith);

        $t = time();
        $sunrisef = date_sunrise($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $sunrise = str_replace(':','',$sunrisef);
        $sunsetf = date_sunset($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $sunset = str_replace(':','',$sunsetf);

        $zenith = 96;
        $aubecivf = date_sunrise($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $aubeciv = str_replace(':','',$aubecivf);
        $crepcivf = date_sunset($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $crepciv = str_replace(':','',$crepcivf);

        $zenith = 102;
        $aubenauf = date_sunrise($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $aubenau = str_replace(':','',$aubenauf);
        $crepnauf = date_sunset($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $crepnau = str_replace(':','',$crepnauf);

        $zenith = 108;
        $aubeastf = date_sunrise($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $aubeast = str_replace(':','',$aubeastf);
        $crepastf = date_sunset($t, SUNFUNCS_RET_STRING, $latitude, $longitude, $zenith, $offset);
        $crepast = str_replace(':','',$crepastf);

        $sunrisef = new DateTime($sunrisef);
        $sunsetf = new DateTime($sunsetf);
        $interval = $sunrisef->diff($sunsetf);
        $minutes = $interval->format('%i');
        $hours = $interval->format('%h	');
        $daylen = (int)$hours*60 + (int)$minutes;
        $sun_info = date_sun_info(time(), $latitude, $longitude);
        $zenithf = date("H:i", $sun_info['transit']);
        $zenith = str_replace(':','',$zenithf);

        log::add(__CLASS__, 'info', 'getDaily');

        $this->checkAndUpdateCmd('sunrise',$sunrise);
        $this->checkAndUpdateCmd('sunset',$sunset);
        $this->checkAndUpdateCmd('aubenau',$aubenau);
        $this->checkAndUpdateCmd('crepnau',$crepnau);
        $this->checkAndUpdateCmd('aubeciv',$aubeciv);
        $this->checkAndUpdateCmd('crepciv',$crepciv);
        $this->checkAndUpdateCmd('aubeast',$aubeast);
        $this->checkAndUpdateCmd('crepast',$crepast);
        $this->checkAndUpdateCmd('zenith',$zenith);
        $this->checkAndUpdateCmd('daylen',$daylen);
        $this->refreshWidget();
    }

    public function getGeoloc($_infos = '') {
        $return = array();
        foreach (eqLogic::byType('geoloc') as $geoloc) {
            foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
                if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
                    $return[$geoinfo->getId()] = array(
                        'value' => $geoinfo->getName(),
                    );
                }
            }
        }
        return $return;
    }

    public function setupCron() {
        $setting = config::byKey('cron','heliotrope');
        $cron = cron::byClassAndFunction('heliotrope', 'pull');
        if (!is_object($cron)) {
            $cron = new cron();
            $cron->setClass('heliotrope');
            $cron->setFunction('pull');
            $cron->setEnable(1);
            $cron->setDeamon(0);
        }
        if ($setting == '60') {
            $cron->setSchedule('0 * * * *');
        } else {
            $cron->setSchedule('*/'. $setting . ' * * * *');
        }
        $cron->save();
        return true;
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

        $id=array();
        $value=array();
        foreach($this->getCmd('info') as $cmd){
            $type_cmd=$cmd->getLogicalId();
            $id[$type_cmd]=$cmd->getId();
            $value[$type_cmd]=$cmd->execCmd();
        }

        $replace['#azimuth360#'] = $value['azimuth360'];
        $replace['#azimuth360_id#'] = $id['azimuth360'];
        $replace['#altitude#'] = $value['altitude'];
        $replace['#sunrise#'] = substr_replace($value['sunrise'],':',-2,0);
        $replace['#sunset#'] = substr_replace($value['sunset'],':',-2,0);
        $replace['#aubeciv#'] = substr_replace($value['aubeciv'],':',-2,0);
        $replace['#crepciv#'] = substr_replace($value['crepciv'],':',-2,0);
        $replace['#aubenau#'] = substr_replace($value['aubenau'],':',-2,0);
        $replace['#crepnau#'] = substr_replace($value['crepnau'],':',-2,0);
        $replace['#aubeast#'] = substr_replace($value['aubeast'],':',-2,0);
        $replace['#crepast#'] = substr_replace($value['crepast'],':',-2,0);
        $replace['#zenith#'] = substr_replace($value['zenith'],':',-2,0);
        $replace['#daylen#'] = floor($value['daylen']/60) .'h ' .$value['daylen']%60 .'min';

        $replace['#collectDate#'] = $this->getStatus('lastCommunication', date('Y-m-d H:i:s'));

        if (array_key_exists('daystatus', $value) && $value['daystatus']=="1") {
            $replace['#heliosun#'] = "opacity : 1";
            $replace['#heliomoon#'] = "opacity : 0.3";
        } else {
            $replace['#heliosun#'] = "opacity : 0.3";
            $replace['#heliomoon#'] = "opacity : 1";
        }
        
        $refresh = $this->getCmd(null, 'refresh');
        $replace['#refresh#'] = is_object($refresh) ? $refresh->getId() : '';
        
        if (file_exists( __DIR__ ."/../template/$_version/custom.heliotrope.html"))
          return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'custom.heliotrope', 'heliotrope')));
        else
        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'heliotrope', 'heliotrope')));
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

?>
