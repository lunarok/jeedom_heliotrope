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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function heliotrope_install() {
	$cron = cron::byClassAndFunction('heliotrope', 'pull');
	if (is_object($cron)) {
		$cron->remove();
	}
	$cron = cron::byClassAndFunction('heliotrope', 'daily');
	if (is_object($cron)) {
		$cron->remove();
	}
}

function heliotrope_update() {
	$cron = cron::byClassAndFunction('heliotrope', 'pull');
	if (is_object($cron)) {
		$cron->remove();
	}
	$cron = cron::byClassAndFunction('heliotrope', 'daily');
	if (is_object($cron)) {
		$cron->remove();
	}
	foreach (eqLogic::byType('heliotrope') as $heliotrope) {
		$cmd = $heliotrope->getCmd('info', 'aubeciv');
		if (is_object($cmd)) {
			$cmd->setLogicalId('civil_twilight_begin')->save();
		}
		$cmd = $heliotrope->getCmd('info', 'crepciv');
		if (is_object($cmd)) {
			$cmd->setLogicalId('civil_twilight_end')->save();
		}
		$cmd = $heliotrope->getCmd('info', 'aubenau');
		if (is_object($cmd)) {
			$cmd->setLogicalId('nautical_twilight_begin')->save();
		}
		$cmd = $heliotrope->getCmd('info', 'crepnau');
		if (is_object($cmd)) {
			$cmd->setLogicalId('nautical_twilight_end')->save();
		}
		$cmd = $heliotrope->getCmd('info', 'aubeast');
		if (is_object($cmd)) {
			$cmd->setLogicalId('astronomical_twilight_begin')->save();
		}
		$cmd = $heliotrope->getCmd('info', 'crepast');
		if (is_object($cmd)) {
			$cmd->setLogicalId('astronomical_twilight_end')->save();
		}
		$heliotrope->save();
	}
}

function heliotrope_remove() {
	$cron = cron::byClassAndFunction('heliotrope', 'pull');
	if (is_object($cron)) {
		$cron->remove();
	}
	$cron = cron::byClassAndFunction('heliotrope', 'daily');
	if (is_object($cron)) {
		$cron->remove();
	}
}