<?php

	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of casts in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc, $database;
		global $lang;

		$misc->printTrail('database');
		$misc->printTabs('database','logicalsubscriptions');
		$misc->printMsg($msg);

		$casts = $data->getSubscriptions(null);

		$columns = array(
			'pubname' => array(
				'title' => $lang['strsourcetype'],
				'field' => field('pubname'),
        'url'   => "redirect.php?subject=database&amp;{$misc->href}&amp;",
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
		
		$misc->printTable($casts, $columns, $actions, 'logicalsubscriptions-logicalsubscriptions', $lang['strnocasts']);
	}

	function doTree() {
		global $misc, $data;

		$reqvars = $misc->getRequestVars('publication');
		$casts = $data->getSubscriptions(null);

		$attrs = array(
			'text'   => field('pubname'),
			'icon'   => 'Replication',
			'toolTip'=> field('nspcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array(
								'subject' => 'database',
								'publication'  => field('pubname')
							)
						),
            /*
			'branch' => url('logicalsubscriptions.php',
							$reqvars,
							array(
								'action'  => 'subtree',
								'publication'  => field('pubname')
							)
						),*/
		);

		$misc->printTree($casts, $attrs, 'logicalsubscriptions');
		exit;
	}

	function doSubTree() {
		global $misc, $data, $lang;

		$tabs = $misc->getNavTabs('logicalsubscriptions');

    $casts = $data->getSubscriptions(null);
    
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

	$misc->printHeader($lang['logicalsubscriptions']);
	$misc->printBody();

	switch ($action) {
		case 'tree':
			doTree();
			break;
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
