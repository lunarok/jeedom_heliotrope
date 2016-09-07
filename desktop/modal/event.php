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
$selsunset = 'selected ';
$selnone = '';
$selplus = '';
$selminus = 'selected ';
$minutes = '17';


echo '<div id="div_alertConfigureScene"></div>';
echo '<div id="div_configureParam">';
echo '<input class="eqLogicAttr" data-l1key="id" style="display:none;" value="' . init('id') . '" />';
echo '<legend>Configuration de la condition';
echo '<a class="btn btn-success btn-xs pull-right" id="bt_configureParamSave"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>';
echo '</legend>';
echo '<form class="form-horizontal">';
echo '<fieldset>';

echo '<div class="form-group">';
echo '<label class="col-sm-2 control-label">Evènement</label>';
echo '<div class="col-sm-3">';
echo '<select class="cmdAttr" data-l1key="configuration" data-l2key="event" style="height : 33px; width : 60%;display : inline-block;">';
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
echo '<select class="cmdAttr" data-l1key="configuration" data-l2key="adjust" style="height : 33px; width : 60%;display : inline-block;">';
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
echo '<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="minutes" style="height : 33px; width : 60%;display : inline-block;" value="' . $minutes . '" />';
echo '</div>';
echo '<div class="col-sm-3 alert alert-info">';
echo "Combien d'heure(s)";
echo '</div>';
echo '</div>';

echo '</fieldset>';
echo '</form>';
?>

<script>
$('#bt_configureParamSave').off('click').on('click',function(){
	eqLogic = $('#div_configureParam').getValues('.eqLogicAttr')[0];
	jeedom.eqLogic.simpleSave({
		eqLogic: eqLogic,
		error: function (error) {
			$('#div_alertConfigureScene').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			modifyWithoutSave = false;
			$('#div_alertConfigureScene').showAlert({message: '{{Sauvegarde effectuée avec succes}}', level: 'success'});
		}
	});
});
</script>
