<?php
require_once "inc/leginon.inc";
require_once "inc/viewer.inc";
require_once "inc/project.inc";
require_once "inc/cachedb.inc";
if (defined('PROCESSING')) {
	$ptcl = (require_once "inc/particledata.inc") ? true : false;
}

// --- get Predefined Variables form GET or POST method --- //
list($projectId, $sessionId, $imageId, $preset, $runId, $scopeId) = getPredefinedVars();

if (is_null($sessionId)){
    $_SESSION['unlimited_images'] = false;
    $limit = 100;
}
elseif ($sessionId=='-1' || !empty($_SESSION['unlimited_images'])){
    $limit = 0;
    $_SESSION['unlimited_images'] = true;
}
else  $limit = 100;
// --- Set sessionId
$lastId = $leginondata->getLastSessionId();
$sessionId = (empty($sessionId)) ? $lastId : $sessionId;

$sessioninfo=$leginondata->getSessionInfo($sessionId);
$session=$sessioninfo['Name'];

$scopes = $leginondata->getScopesForSelection();
$scopeId = (empty($scopeId)) ? false:$scopeId;

if(!$sessions) {
	$sessions = $leginondata->getSessions('description', $projectId, '', $scopeId);
}

$projectdata = new project();
$projectdb = $projectdata->checkDBConnection();

if(!$sessions) {
	$sessions = $leginondata->getSessions('description', $projectId);
}

if($projectdb) {
	$projects = $projectdata->getProjects('all');
	$sessionexists = $projectdata->sessionExists($projectId, $sessionId);
	if (!$sessionexists) {
		$sessionId = $sessions[0]['id'];
	}
}

if ( is_numeric(SESSION_LIMIT) && count($sessions) > SESSION_LIMIT) $sessions=array_slice($sessions,0,SESSION_LIMIT);

$jsdata='';
if ($ptcl) {
	list ($jsdata, $particleruns) = getParticleInfo($sessionId);
	$particle = new particledata();
	$filenames = $particle->getFilenamesFromLabel($runId, $preset);
	$aceruns = $particle-> getCtfRunIds($sessionId);
}

// --- update SessionId while a project is selected
$sessionId_exists = $leginondata->sessionIdExists($sessions, $sessionId);
if (!$sessionId_exists) {
	$sessionId=$sessions[0]['id'];
}

if (!$filenames) {
	$filenames = $leginondata->getFilenames($sessionId, $preset);
}

// --- Get data type list
$datatypes = $leginondata->getAllDatatypes($sessionId);

$viewer = new viewer();
if($projectdb && !empty($sessions)) {
	foreach($sessions as $k=>$s) {
		if (SAMPLE_TRACK) {
			$tag=$projectdata->getSample(array('Id'=>$s['id'], 'Purpose'=>$s['comment']));
			$tag = ($tag)? " - $tag" : "";
			$sessions[$k]['name'].=$tag;
		}
		if ($s['id']==$sessionId) {
			$sessionname = $s['name_org'];
			// if name need to be modified by sample tag, it should not break
			// breaking is only to save query time
			if (!SAMPLE_TRACK)
				break;
		}
	}
	$currentproject = $projectdata->getProjectFromSession($sessionname);

	$viewer->setProjectId($projectId);
	$viewer->addProjectSelector($projects, $currentproject);
}
$viewer->setSessionId($sessionId);
$viewer->setImageId($imageId);
$viewer->addSessionSelector($sessions, $limit);
$viewer->setScopeId($scopeId);
$viewer->addScopeSelector($scopes);
//$viewer->addFileSelector($filenames);
$viewer->setNbViewPerRow('1');
$viewer->addjs($jsdata);
$pl_refresh_time=".5";
$viewer->addPlaybackControl($pl_refresh_time);
$playbackcontrol=$viewer->getPlaybackControl();
$javascript = $viewer->getJavascript();


$view1 = new fileview('Data Selection', 'v1');
$view1->setControl();
$view1->setDataTypes($datatypes);
$view1->setSize(100);

$viewer->add($view1);


$javascript .= $viewer->getJavascriptInit();
login_header('file listing', $javascript, 'initviewer()');
viewer_menu($sessionId);
$viewer->display();

foreach ($filenames as $f) {
	echo "<p>".$f['name']."</p>";
}

login_footer();
?>
