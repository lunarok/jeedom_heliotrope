
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

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }

  if (init(_cmd.type) == 'info') {
    var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="configuration" data-l2key="value"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
      tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');

}

if (init(_cmd.type) == 'action') {
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id"></span>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom de la commande}}"></td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="configuration" data-l2key="type"></span>';
  tr += '</td>';
  tr += '<td>';
  tr += '<a class="btn btn-info btn-sm cmdAction" data-action="add">{{Editer les conditions}}</a><br/><br/>';
  tr += '</td>';
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
  }
  tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
  tr += '</td>';
  tr += '</tr>';
  $('#action_cmd tbody').append(tr);
  $('#action_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
}

}

$('#addEvent').on('click',function(){
    $('#md_modal').dialog({title: "{{Configuration du scénario}}"});
    $('#md_modal').load('index.php?v=d&plugin=heliotrope&modal=event&cmd=new&id='+$('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#addCondition').on('click',function(){
    $('#md_modal').dialog({title: "{{Configuration du scénario}}"});
    $('#md_modal').load('index.php?v=d&plugin=heliotrope&modal=condition&cmd=new&id='+$('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});


$('body').on('heliotrope::includeDevice', function (_event,_options) {
    if (modifyWithoutSave) {
        $('#div_inclusionAlert').showAlert({message: '{{Une commande vient d\'être ajoutée. Veuillez réactualiser la page}}', level: 'warning'});
    } else {
        if (_options == '') {
            window.location.reload();
        } else {
            window.location.href = 'index.php?v=d&p=heliotrope&m=heliotrope&id=' + _options + '#commandtab';
        }
    }
});
