<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('heliotrope');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
  <!-- Page d'accueil du plugin -->
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <!-- Boutons de gestion du plugin -->
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Mes Héliotropes}}</legend>
    <?php
      if (count($eqLogics) == 0) {
        echo '<br/><div class="text-left" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Héliotrope n\'est paramétré, cliquer sur "Ajouter" pour commencer}}</div>';
      } else {
        // Champ de recherche
        echo '<div class="input-group" style="margin:5px;">';
        echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>';
        echo '<div class="input-group-btn">';
        echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
        echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
        echo '</div>';
        echo '</div>';
        // Liste des équipements du plugin
        echo '<div class="eqLogicThumbnailContainer">';
        foreach ($eqLogics as $eqLogic) {
          $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
          echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
          echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
          echo '<br>';
          echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
          echo '</div>';
        }
        echo '</div>';
      }
    ?>
  </div> <!-- /.eqLogicThumbnailDisplay -->

  <!-- Page de présentation de l'équipement -->
  <div class="col-xs-12 eqLogic" style="display: none;">
    <!-- barre de gestion de l'équipement -->
    <div class="input-group pull-right" style="display:inline-flex">
      <span class="input-group-btn">
        <!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
        <a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
        </a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
        </a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
        </a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
        </a>
      </span>
    </div>
    <!-- Onglets -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#infotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Position du Soleil}}</a></li>
      <li role="presentation"><a href="#levertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-sun"></i> {{Lever Soleil}}</a></li>
      <li role="presentation"><a href="#couchertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-moon"></i> {{Coucher Soleil}}</a></li>
    </ul>
    <div class="tab-content">
      <!-- Onglet de configuration de l'équipement -->
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <!-- Partie gauche de l'onglet "Equipements" -->
        <!-- Paramètres généraux de l'équipement -->
        <form class="form-horizontal">
          <fieldset>
            <div class="col-lg-6">
              <legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Héliotrope}}</label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                  <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Héliotrope}}"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Objet parent}}</label>
                <div class="col-sm-7">
                  <select class="eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                      $options = '';
                      foreach ((jeeObject::buildTree(null, false)) as $object) {
                        $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                      }
                      echo $options;
                      ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-7">
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
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-7">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
              </div>
            </div>

            <legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Géolocalisation}}</label>
              <div class="col-sm-7">
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
                  if ((config::byKey('info::latitude', 'core', '91') != '91') && (config::byKey('info::longitude', 'core', '361') != '361')) {
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
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-7">
                  <label class="checkbox-inline">
                  <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="useHelioTemplate" checked/>{{Utiliser la template du plugin}}</label>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div><!-- /.tabpanel #eqLogictab-->

      <!-- Onglet des commandes Position soleil -->
      <div role="tabpanel" class="tab-pane" id="infotab">
        <div class="table-responsive">
          <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 300px;">{{Nom}}</th>
                <th style="width: 200px;">{{Paramètres}}</th>
                <th style="width: 100px;">{{Actions}}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <span>{{Les informations de position du soleil permettent de créer des scénarios ayant besoin de se déclencher sur une orientation ou une altitude du soleil.}}</span>
        <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Si la direction du soleil est dans un angle de 180 du store par exemple (-90 et +90) on peut le fermer pour éviter que le soleil chauffe en été ou inversement pour les périodes froides. On peut affiner en utilisant la hauteur du soleil.}}</span>
        <br><span>{{La position est recalculée à la fréquence sélectionnée sur la page de configuration du plugin. Pour utiliser la direction dans un scénario, il faut utiliser le mode déclenché avec la direction et utiliser une condition SI #Direction# > Orientation baie - 90}}</span>
      </div><!-- /.tabpanel #infotab-->

      <!-- Onglet des commandes Lever de soleil -->
      <div role="tabpanel" class="tab-pane" id="levertab">
        <div class="table-responsive">
          <table id="table_lever" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 300px;">{{Nom}}</th>
                <th style="width: 200px;">{{Paramètres}}</th>
                <th style="width: 100px;">{{Actions}}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <span>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</span>
        <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se lève on ouvre les volets par exemple.}}</span>
        <br><span>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</span>
        <br><span>{{ - sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Aube Civile#}}</span>
        <br><span>{{ - ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Aube Civile#}}</span>
        <br><span>{{ - si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</span>
      </div><!-- /.tabpanel #levertab-->

          <!-- Onglet des commandes Coucher de soleil -->
      <div role="tabpanel" class="tab-pane" id="couchertab">
        <div class="table-responsive">
          <table id="table_coucher" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 300px;">{{Nom}}</th>
                <th style="width: 200px;">{{Paramètres}}</th>
                <th style="width: 100px;">{{Actions}}</th>
              </tr>
            </thead>
              <tbody>
              </tbody>
          </table>
        </div>
        <span>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</span>
        <br><span>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se couche on ferme les volets par exemple.}}</span>
        <br><span>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</span>
        <br><span>{{ - sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Coucher du Soleil#}}</span>
        <br><span>{{ - ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Coucher du Soleil#}}</span>
        <br><span>{{ - si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</span>
      </div><!-- /.tabpanel #couchertab-->
    </div><!-- /.tab-content -->
  </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'heliotrope', 'js', 'heliotrope'); ?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
