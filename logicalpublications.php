<?php

	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';


	/**
	 * Show the tablelist of an pub;ication
	 */
	function dotablelist($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('database');
		$misc->printTabs('database','logicalpublications');

//		$misc->printTitle($lang['strtablelist'],'pg.aggregate');
		$misc->printMsg($msg);

		$publication = $data->getPublicationDetails($_REQUEST['publication']);

		if($publication->recordCount() > 0 ) {

			$columns = array(
				'oid' => array(
					'title' => $lang['stroids'],
					'field' => field('oid'),
				),
				'nspname' => array(
					'title' => $lang['strschemaname'],
					'field' => field('nspname'),
				),
				'relname' => array(
					'title' => $lang['strtablename'],
					'field' => field('relname'),
				),
				'reltuples' => array(
					'title' => $lang['strestimatedrowcount'],
					'field' => field('reltuples'),
				),
				'relkind' => array(
					'title' => $lang['strtargettype'],
					'field' => field('relkind'),
				),
				'relreplident' => array(
					'title' => $lang['relreplident'],
					'field' => field('relreplident'),
				),
				'owner' => array(
					'title' => $lang['strowner'],
					'field' => field('owner'),
				),
			);
			$actions = array();
			$misc->printTable($publication, $columns, $actions, 'logicalpublications-logicalpublications', $lang['strnocasts']);
			/*
			echo "<table>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['strname']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($_REQUEST['aggrname']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['straggrbasetype']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($_REQUEST['aggrtype']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['straggrsfunc']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['aggtransfn']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['straggrstype']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['aggstype']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['straggrffunc']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['aggfinalfn']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['straggrinitcond']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['agginitval']), "</td>\n</tr>\n";
			if($data->hasAggregateSortOp()) {
				echo "<tr>\n\t<th class=\"data left\">{$lang['straggrsortop']}</th>\n";
				echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['aggsortop']), "</td>\n</tr>\n";
			}
			echo "<tr>\n\t<th class=\"data left\">{$lang['strowner']}</th>\n";
			echo "\t<td class=\"data1\">", htmlspecialchars($publication->fields['usename']), "</td>\n</tr>\n";
			echo "<tr>\n\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
			echo "\t<td class=\"data1\">", $misc->printVal($publication->fields['aggrcomment']), "</td>\n</tr>\n";
			echo "</table>\n";*/
		}
		else echo "<p>{$lang['strnodata']}</p>\n";

		$navlinks = array (
			'showall' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'logicalpublications.php',
						'urlvars' => array (
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database']
						)
					)
				),
				'content' => $lang['straggrshowall']
			)
		);

		// if ($data->hasAlterAggregate()) {
			$navlinks['alter'] = array (
				'attr'=> array (
					'href' => array (
						'url' => 'logicalpublications.php',
						'urlvars' => array (
							'action' => 'alter',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'publication' => $_REQUEST['publication']
						)
					)
				),
				'content' => $lang['stralter']
			);
		// }

		$navlinks['drop'] = array (
			'attr'=> array (
				'href' => array (
					'url' => 'logicalpublications.php',
					'urlvars' => array (
						'action' => 'confirm_drop',
						'server' => $_REQUEST['server'],
						'database' => $_REQUEST['database'],
						'publication' => $_REQUEST['publication']
					)
				)
			),
			'content' => $lang['strdrop']
		);

		$misc->printNavLinks($navlinks, 'logicalpublications-tablelist', get_defined_vars());
	}


	/**
	 * Show default list of casts in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc, $database;
		global $lang;

		$misc->printTrail('database');
		$misc->printTabs('database','logicalpublications');
		$misc->printMsg($msg);

		$casts = $data->getPublications(null);

		$columns = array(
			'pubname' => array(
				'title' => $lang['strsourcetype'],
				'field' => field('pubname'),
				'url'   => "logicalpublications.php?subject=logicalpublications&amp;action=tablelist&amp;{$misc->href}&amp;",
				'vars'  => array('publication' => 'pubname'),
			),
			'owner' => array(
				'title' => $lang['strtargettype'],
				'field' => field('owner'),
			),
			'puballtables' => array(
				'title' => 'puballtables',
				'field' => field('puballtables'),
				'params'=> array('align' => 'center'),
			),
			'pubinsert' => array(
				'title' => 'pubinsert',
				'field' => field('pubinsert'),
				'params'=> array('align' => 'center'),
			),
			'pubupdate' => array(
				'title' => 'pubupdate',
				'field' => field('pubupdate'),
				'params'=> array('align' => 'center'),
			),
			'pubdelete' => array(
				'title' => 'pubdelete',
				'field' => field('pubdelete'),
				'params'=> array('align' => 'center'),
			),
			'pubtruncate' => array(
				'title' => 'pubtruncate',
				'field' => field('pubtruncate'),
				'params'=> array('align' => 'center'),
			)
		);

		$actions = array();

		$misc->printTable($casts, $columns, $actions, 'logicalpublications-logicalpublications', $lang['strnocasts']);
	}

	function doTree() {
		global $misc, $data, $lang;

		$reqvars = $misc->getRequestVars('publication');
		$casts = $data->getPublications(null);

		$attrs = array(
			'text'   => field('pubname'),
			'icon'   => 'Replication',
			'toolTip'=> field('nspcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array(
								'action' => 'tablelist',
								'subject' => 'database',
								'publication'  => field('pubname')
							)
						),
				/*
			'branch' => url('logicalpublications.php',
							$reqvars,
							array(
								'action'  => 'subtree',
								'publication'  => field('pubname')
							)
						),*/
		);

		$misc->printTree($casts, $attrs, 'logicalpublications');
		exit;
	}

	function doSubTree() {
		global $misc, $data, $lang;

		$tabs = $misc->getNavTabs('logicalpublications');

		$casts = $data->getPublications(null);

		$items = $misc->adjustTabsForTree($tabs);

		$reqvars = $misc->getRequestVars('publication');

		$attrs = array(
			'text'   => field('title'),
			'icon'   => field('icon'),
			'action' => url(field('url'),
							$reqvars,
							field('urlvars', array())
						),
			'branch' => url(field('url'),
							$reqvars,
							field('urlvars'),
							array('action' => 'tree')
						)
		);

		$misc->printTree($items, $attrs, 'pubtable');
		exit;
	}

	if ($action == 'tree') doTree();
	if ($action == 'subtree') doSubTree();

	$misc->printHeader($lang['logicalpublications']);
	$misc->printBody();

	switch ($action) {
		case 'tree':
			doTree();
			break;
		case 'tablelist':
			dotablelist();
			break;
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>