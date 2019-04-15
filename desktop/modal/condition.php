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

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$selsunrise = '';
$selzenith = '';
$selsunset = '';
$selnone = '';
$selplus = '';
$selminus = '';
$minutes = '0';

$cmdlogic = heliotropeCmd::byId(init('cmd'));
if (is_object($cmdlogic)) {
	if ($cmdlogic->getConfiguration('event') == 'sunrise') {
		$selsunrise = 'selected ';
	}
	if ($cmdlogic->getConfiguration('event') == 'zenith') {
		$selzenith = 'selected ';
	}
	if ($cmdlogic->getConfiguration('event') == 'sunset') {
		$selsunset = 'selected ';
	}
	if ($cmdlogic->getConfiguration('adjust') == 'none') {
		$selnone = 'selected ';
	}
	if ($cmdlogic->getConfiguration('adjust') == 'plus') {
		$selplus = 'selected ';
	}
	if ($cmdlogic->getConfiguration('adjust') == 'minus') {
		$selminus = 'selected ';
	}
	if ($cmdlogic->getConfiguration('minutes') != '') {
		$minutes = $cmdlogic->getConfiguration('minutes');
	}
	$name = $cmdlogic->getName();
} else {
	$eqLogic = eqLogic::byId(init('id'));
	$cmds = $eqLogic->getCmd();
	$name = 'Event ' . count($cmds);
}



echo '<div id="div_alertConfigureScene"></div>';
echo '<div id="div_configureParam">';
echo '<input class="eqLogicAttr" id="id" style="display:none;" value="' . init('id') . '" />';
echo '<input class="eqLogicAttr" id="cmd" style="display:none;" value="' . init('cmd') . '" />';
echo '<legend>Configuration de la condition';
echo '<a class="btn btn-success btn-xs pull-right" id="bt_configureParamSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>';
echo '</legend>';
echo '<form class="form-horizontal">';
echo '<fieldset>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Nom</label>';
echo '<div class="col-sm-3">';
echo '<input type="text" class="eqLogicAttr form-control" id="name" style="height : 33px; width : 60%;display : inline-block;" value="' . $name . '" />';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Evènement</label>';
echo '<div class="col-sm-3">';
echo '<select class="cmdAttr" id="event" style="height : 33px; width : 60%;display : inline-block;">';
echo '<option ' . $selsunrise . 'value="sunrise">Lever du soleil</option>';
echo '<option ' . $selzenith . 'value="zenith">Zenith du soleil</option>';
echo '<option ' . $selsunset . 'value="sunset">Coucher du soleil</option>';
echo '</select>';
echo '</div>';
echo '<div class="col-sm-3 alert alert-info">';
echo 'Choisir lequel des évènements solaire va être utilisé en déclencheur';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Ajustement</label>';
echo '<div class="col-sm-3">';
echo '<select class="cmdAttr" id="adjust" style="height : 33px; width : 60%;display : inline-block;">';
echo '<option  ' . $selnone . 'value="none">Aucun</option>';
echo '<option  ' . $selplus . 'value="plus">Après le déclencheur</option>';
echo '<option  ' . $selminus . 'value="minus">Avant le déclencheur</option>';
echo '</select>';
echo '</div>';
echo '<div class="col-sm-3 alert alert-info">';
echo 'Si on doit déclencher les actions à heure identique ou avec un décalage';
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Minutes de décalage</label>';
echo '<div class="col-sm-3">';
echo '<input type="number" class="eqLogicAttr form-control" id="minutes" style="height : 33px; width : 60%;display : inline-block;" value="' . $minutes . '" />';
echo '</div>';
echo '<div class="col-sm-3 alert alert-info">';
echo "Combien d'heure(s)";
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Commande à éxécuter</label>';
echo '<div class="col-sm-3">';
echo '<input type="text"  class="eqLogicAttr configuration form-control" id="command" />';
echo '<span class="input-group-btn">';
echo '<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectCmd"><i class="fas fa-list-alt"></i></a>';
echo '</span>';
echo '</div>';
echo '<div class="col-sm-3 alert alert-info">';
echo "Combien d'heure(s)";
echo '</div>';
echo '</div>';

echo '</fieldset>';
echo '</form>';
?>

<script>
$('#bt_selectCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('#command').atCaret('insert', result.human);
    });
});

$('#bt_configureParamSave').off('click').on('click',function(){
	var id = $('#id').val();
	var cmd = $('#cmd').val();
	var name = $('#name').val();
	var event = $('#event').val();
	var adjust = $('#adjust').val();
	var minutes = $('#minutes').val();
	var command = $('#command').val();
	$.ajax({// fonction permettant de faire de l'ajax
	type: "POST", // methode de transmission des données au fichier php
	url: "plugins/heliotrope/core/ajax/heliotrope.ajax.php", // url du fichier php
	data: {
		action: "setEvent",
		id: id,
		cmd: cmd,
		name: name,
		adjust: adjust,
		minutes: minutes,
		command: command,
	},
	dataType: 'json',
	error: function (request, status, error) {
		handleAjaxError(request, status, error);
	},
	success: function (data) { // si l'appel a bien fonctionné
	if (data.state != 'ok') {
		$('#div_alert').showAlert({message: data.result, level: 'danger'});
		return;
	} else {
		window.location.href = 'index.php?v=d&p=heliotrope&m=heliotrope&id=' + id + '#commandtab';
	}
}
});
});
</script>
