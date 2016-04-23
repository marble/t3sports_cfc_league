<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cfc_league".
 *
 * Auto generated 06-01-2016 17:12
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'T3sports',
	'description' => 'Umfangreiche Extension zur Verwaltung von Sportvereinen und -wettbewerben. Funktioniert nur mit PHP5! Extensive extension to manage sports clubs and competitions. Requires PHP5! http://cfcleague.sf.net/',
	'category' => 'module',
	'version' => '1.0.2',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => 'uploads/tx_cfcleague/',
	'clearcacheonload' => true,
	'author' => 'Rene Nitzsche',
	'author_email' => 'rene@system25.de',
	'author_company' => 'System 25',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '4.3.0-6.2.99',
			'php' => '5.3.0-0.0.0',
			'rn_base' => '0.14.1-0.0.0',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
);

