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
$tasker = tasker::byId(init('id'));

if (!is_object($tasker)) {
	throw new Exception(__('Objet tasker non trouvé : ' . init('id')));
}
$config = tasker::sceneParameters(init('scene'));
if (count($config) == 0) {
	throw new Exception(__('Impossible de trouver le fichier de config : ', __FILE__) . init('scene'));
}
echo '<div id="div_alertConfigureScene"></div>';
echo '<div id="div_configureParam">';
echo '<input class="eqLogicAttr" data-l1key="id" style="display:none;" value="' . init('id') . '" />';
echo '<legend>' . $config['name'];
echo '<a class="btn btn-success btn-xs pull-right" id="bt_configureParamSave"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>';
echo '</legend>';
echo '<form class="form-horizontal">';
echo '<fieldset>';
foreach ($config['configuration'] as $key => $parameter) {
	$default = '';
	if (isset($parameter['default'])) {
		$default = $parameter['default'];
	}
	$default = $tasker->getConfiguration('tasker::' . $key, $default);
	echo '<div class="form-group">';
	echo '<label class="col-sm-2 control-label">' . $parameter['name'] . '</label>';
	echo '<div class="col-sm-3">';
	switch ($parameter['type']) {
		case 'color':
			echo '<input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tasker::' . $key . '" value="' . $default . '" />';
			break;
		case 'input':
			echo '<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tasker::' . $key . '" value="' . $default . '" />';
			break;
		case 'number':
			echo '<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tasker::' . $key . '" value="' . $default . '" />';
			break;
	}
	echo '</div>';
	if (isset($parameter['description'])) {
		echo '<div class="col-sm-3 alert alert-info">';
		echo $parameter['description'];
		echo '</div>';
	}
	echo '</div>';
}
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
