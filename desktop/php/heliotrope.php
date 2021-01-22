<?php

if (!isConnect('admin')) {
	throw new \Exception('{{401 - Accès non autorisé}}');
}

$pluginName = init('m');
$plugin = plugin::byId($pluginName);
sendVarToJS('eqType', $plugin->getId());
$eqLogicList = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span>{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
		</div>
		<legend><img style="width:40px" src="<?= $plugin->getPathImgIcon() ?>"/> {{Mes équipements}}</legend>
		<?php if (count($eqLogicList) == 0) { ?>
			<center>
				<span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n’avez pas encore d’appareil, cliquez sur Ajouter pour commencer}}</span>
			</center>
		<?php } else { ?>
			<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
			<div class="eqLogicThumbnailContainer">
				<?php
				foreach ($eqLogicList as $eqLogic) {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					?>
					<div class="eqLogicDisplayCard cursor <?= $opacity ?>" data-eqLogic_id="<?= $eqLogic->getId() ?>">
						<img src="<?= $eqLogic->getImage() ?>" />
						<br/>
						<span class="name"><?= $eqLogic->getHumanName(true, true) ?></span>
					</div>
			<?php } ?>
			</div>
		<?php } ?>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save">
			<i class="fas fa-check-circle"></i> {{Sauvegarder}}
		</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove">
			<i class="fas fa-minus-circle"></i> {{Supprimer}}
		</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="configure">
			<i class="fas fa-cogs"></i> {{Configuration avancée}}
		</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab"
				   data-action="returnToThumbnailDisplay">
					<i class="fas fa-arrow-circle-left"></i>
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="fas fa-tachometer-alt"></i> {{Equipement}}</a>
			</li>
			<li role="presentation">
				<a href="#infotab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fas fa-list-alt"></i> {{Position du Soleil}}
				</a></li>
			<li role="presentation">
				<a href="#levertab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fas fa-sun"></i> {{Lever Soleil}}
				</a>
			</li>
			<li role="presentation">
				<a href="#couchertab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fas fa-moon"></i> {{Coucher Soleil}}
				</a>
			</li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="name">{{Héliotrope}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id"
										style="display : none;"/>
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" id="name"
									placeholder="{{Nom de l’équipement heliotrope}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="sel_object">{{Objet parent}}</label>
							<div class="col-sm-3">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php foreach (jeeObject::all() as $object) { ?>
									<option value="<?= $object->getId() ?>"><?= $object->getName() ?></option>';
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Catégorie}}</label>
							<div class="col-sm-9">
								<?php foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) { ?>
									<label class="checkbox-inline">
										<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="<?= $key ?>" /><?= $value['name'] ?>
									</label>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" ></label>
							<div class="col-sm-9">
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
												$none = 1; ?>
												<option value="<?= $geoloc->getId() ?>"><?= $geoloc->getName() ?></option>
											<?php 
											}
										}
									}
									if ((config::byKey('info::latitude', 'core', '91') != '91') && (config::byKey('info::longitude', 'core', '361') != '361')) {
										$none = 1; ?>
										<option value="jeedom">Configuration Jeedom</option>
									<?php }
									if ($none == 0) { ?>
										<option value="">Pas de localisation disponible</option>
									<?php } ?>
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
				<fieldset>
					<div class="alert alert-info">
						<p>{{Les informations de position du soleil permettent de créer des scénarios ayant besoin de se déclencher sur une orientation ou une altitude du soleil.}}</p>
						<p>{{Une utilisation classique est la gestion des volets par rapport au soleil. Si la direction du soleil est dans un angle de 180 du store par exemple (-90 et +90) on peut le fermer pour éviter que le soleil chauffe en été ou inversement pour les périodes froides. On peut affiner en utilisant la hauteur du soleil.}}</p>
						<p>{{La position est recalculée à la fréquence sélectionnée sur la page de configuration du plugin. Pour utiliser la direction dans un scénario, il faut utiliser le mode déclenché avec la direction et utiliser une condition SI #Direction# > Orientation baie - 90}}</p>
					</div>
				</fieldset>
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

				<fieldset>
					<div class="alert alert-info">
						<p>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</p>
						<p>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se lève on ouvre les volets par exemple.}}</p>
						<p>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</p>
						<ul>
							<li>{{sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Aube Civile#}}</li>
							<li>{{ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Aube Civile#}}</li>
							<li>{{si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</li>
						</ul>
					</div>
				</fieldset>
			</div>

			<div role="tabpanel" class="tab-pane" id="couchertab">
				<br/>
				<table id="table_coucher" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th style="width: 50px;">#</th>
					<a href="../../core/template/dashboard/heliotrope.html"></a>
							<th style="width: 300px;">{{Nom}}</th>
							<th style="width: 200px;">{{Paramètres}}</th>
							<th style="width: 100px;"></th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>

				<fieldset>
					<div class="alert alert-info">
						<p>{{Les heures de lever et coucher du soleil permettent de créer des scénarios ayant besoin de se déclencher en fonction de si il fait jour ou non.}}</p>
						<p>{{Une utilisation classique est la gestion des volets par rapport au soleil. Quand le soleil se couche on ferme les volets par exemple.}}</p>
						<p>{{Les horaires sont calculés de façon journalière. Pour utiliser une heure dans un scénario : }}</p>
						<ul>
							<li>{{sélectionner le mode déclenché et ajouter un déclencheur avec la commande voulue, ex : #Objet##Equipement##Coucher du Soleil#}}</li>
							<li>{{ajouter un bloc avec et choisir le type A, là on peut choisir son heure pour activation par exemple avec une commande info : #Objet##Equipement##Coucher du Soleil#}}</li>
							<li>{{si on souhaite modifier la valeur de la commande il existe la commande time_op qui permet par exemple de faire +10}}</li>
						</ul>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>

<?php
include_file('desktop', $pluginName, 'js', $pluginName);
include_file('core', 'plugin.template', 'js');
