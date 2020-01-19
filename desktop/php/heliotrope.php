<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'heliotrope');
$eqLogics = eqLogic::byType('heliotrope');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-sm-3 col-sm-4" id="hidCol" style="display: none;">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
        foreach ($eqLogics as $eqLogic) {
          echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>

  <div class="col-lg-12 eqLogicThumbnailDisplay" id="listCol">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">

      <div class="cursor eqLogicAction" data-action="add">
          <i class="fas fa-plus-circle"></i>
          <br/>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br/>
        <span>{{Configuration}}</span>
      </div>

    </div>

    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />

    <legend><i class="fas fa-home" id="butCol"></i> {{Mes Equipements}}</legend>
    <div class="eqLogicThumbnailContainer">
      <?php
      foreach ($eqLogics as $eqLogic) {
        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        echo '<img src="plugins/heliotrope/plugin_info/heliotrope_icon.png" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
      }
      ?>
    </div>
  </div>



    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#infotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Position du Soleil}}</a></li>
            <li role="presentation"><a href="#levertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-sun-o"></i> {{Lever Soleil}}</a></li>
            <li role="presentation"><a href="#couchertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-moon-o"></i> {{Coucher Soleil}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Héliotrope}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement heliotrope}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select class="form-control eqLogicAttr" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (jeeObject::all() as $object) {
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-8">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                    echo '</label>';
                                }
                                ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" ></label>
                            <div class="col-sm-8">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Géolocalisation}}</label>
                            <div class="col-sm-3">
                                <select class="form-control eqLogicAttr configuration" id="geoloc" data-l1key="configuration" data-l2key="geoloc">
                                    <?php
                                    $none = 0;
                                    if (class_exists('geotravCmd')) {
                                        foreach (eqLogic::byType('geotrav') as $geoloc) {
                                            if ($geoloc->getConfiguration('type') == 'location') {
                                                $none = 1;
                                                echo '<option value="' . $geoloc->getId() . '">' . $geoloc->getName() . '</option>';
                                            }
                                        }
                                    } 
                                    if ((config::byKey('info::latitude','core','91') != '91') && (config::byKey('info::longitude','core','361') != '361')) {
                                        echo '<option value="jeedom">Configuration Jeedom</option>';
                                        $none = 1;
                                    }
                                    if ($none == 0) {
                                        echo '<option value="">Pas de localisation disponible</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Angle de calcul}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="zenith" placeholder="90.58"/>
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="infotab">
                <br/>
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 300px;">{{Nom}}</th>
                            <th style="width: 200px;">{{Paramètres}}</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <span>{{Les informations de position du soleil permettent de créer des scénarios ayant besoin de se déclencher sur une orientation ou une altitude du soleil.}}</span>
                <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Si la direction du soleil est dans un angle de 180 du store par exemple (-90 et +90) on peut le fermer pour éviter que le soleil chauffe en été ou inversement pour les périodes froides. On peut affiner en utilisant la hauteur du soleil.}}</span>
                <br><span>{{La position est recalculée à la fréquence sélectionnée sur la page de configuration du plugin. Pour utiliser la direction dans un scénario, il faut utiliser le mode déclenché avec la direction et utiliser une condition SI #Direction# > Orientation baie - 90}}</span>

            </div>

            <div role="tabpanel" class="tab-pane" id="levertab">
                <br/>
                <table id="table_lever" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 300px;">{{Nom}}</th>
                            <th style="width: 200px;">{{Paramètres}}</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <span>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</span>
                <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se lève on ouvre les volets par exemple.}}</span>
                <br><span>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</span>
                <br><span>{{ - sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Aube Civile#}}</span>
                <br><span>{{ - ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Aube Civile#}}</span>
                <br><span>{{ - si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</span>

            </div>

            <div role="tabpanel" class="tab-pane" id="couchertab">
                <br/>
                <table id="table_coucher" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 300px;">{{Nom}}</th>
                            <th style="width: 200px;">{{Paramètres}}</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <span>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</span>
                <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se couche on ferme les volets par exemple.}}</span>
                <br><span>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</span>
                <br><span>{{ - sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Coucher du Soleil#}}</span>
                <br><span>{{ - ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Coucher du Soleil#}}</span>
                <br><span>{{ - si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</span>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'heliotrope', 'js', 'heliotrope'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
