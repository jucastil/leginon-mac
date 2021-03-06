<?php
/*
 * This script is going to add applications to the database 
 * base on the leginon/applications xml files.
 * also, it will create a demo project and redirect
 * to myamiweb homepage.
 * 
 * THIS SCRIPT ONLY run with centosAutoInstallation.py
 * 
 */

require_once('template.inc');
require_once("../config.php");
require_once("../inc/leginon.inc");
require_once("../project/inc/project.inc.php");

//php command options
$longopts = array("password:","myamidir:","appv:","uploadsample:");
$options = getopt('',$longopts);

$uploadsample = (is_null($_GET['uploadsample'])) ? $options['uploadsample']: $_GET['uploadsample'];
if ($uploadsample) {
	require_once("inc/ssh.inc");
}

/*
$template = new template;
$template->wizardHeader("", SETUP_CONFIG);
?>
<center>
<br /><br /><br /><br />
<img src="../img/wait.gif" alt="Angry face" />
<br /><br /><br /><br />
<h3>System updating...</h3></center>
<?php 
$template->wizardFooter();
*/

/*
 * make sure the xml files are in the myami download location.
 */

$password = (is_null($_GET['password'])) ? ((is_null($options['password'])) ? '':$options['password']): $_GET['password'];
$dir = (is_null($_GET['myamidir'])) ? ((is_null($options['myamidir'])) ? '/tmp/myami/':$options['myamidir']): $_GET['myamidir'];
$app_version = (is_null($_GET['appv'])) ? ((is_null($options['appv'])) ? 1:$options['appv']): $_GET['appv'];
$app_version = (int) $app_version;
$dir .= 'leginon/applications/';

if(is_dir($dir)){
	
	// make sure it is an directory
	if($dh = opendir($dir)){
		
		// loop through the file in this folder
		while (($filename = readdir($dh)) !== false){
			
			//not xml file, skip
			if(!preg_match("/.xml/", $filename))
				continue;

			//don't import 'Advanced', 'Robot' or 'SimuTomography', 'J-', 'Typhone', 'Section3step'
			if(preg_match("/Advanced/", $filename) || preg_match("/Robot/", $filename) || preg_match("/SimuTomography/", $filename) || preg_match("/J-/", $filename) || preg_match("/Typhon/", $filename) || preg_match("/Section3step/", $filename))
				continue;
			else {
				if ($app_version == 2 && !preg_match("/2/", $filename)) continue;
				if ($app_version == 1 && preg_match("/2/", $filename)) continue;
				// import application to database.
				$leginondata->importApplication($dir.$filename);
			}
		}
	}
}

/* Check if applications got imported
*/
$apps = $leginondata->getApplications();
if (empty($apps)) {
	echo "Failed to import applications";
	echo "Check files in ".$dir."for version ".$app_version;
	exit(1);
}
/*
* create projects
*/
$project = new project();

/*
* Now we need to create a new project for Demo.
*/
if ($uploadsample) {

	$project->addProject('GroEL Demo', 'GroEL Demo Project', 'GroEL Demo Project: Created by auto installation script.', 'None', 'This is a free project.');

	/*
	* Now we need to create a processing database for this demo project
	*/
	$dbname = 'ap1';
	$selectedprojectId = '1';
	$q='create database `'.$dbname.'`';
	$r=$project->mysql->SQLQuery($q);

	// --- created default tables --- //
	$filename = DEF_PROCESSING_TABLES_FILE;
	$leginondata->mysql->setSQLHost( array('db'=>$dbname) );
	$leginondata->importTables($filename);

	/* basic appion tables and symmetry record
	*/
	$filename = "../project/defaultprocessingtables.xml";
	$leginondata->mysql->setSQLHost( array('db'=>$dbname) );
	$leginondata->importTables($filename);

	/* appion_extra.xml is created by sinedon/maketables.py
	* based on a database without importing the existing appion_extra.xml 
	* Since sinedon/maketables.py does not create table definition if
	* the table exists in the designated database,
	* DEF_PROCESSING_TABLES_FILE set type
	* varchar is retained that makes it indexable and faster */
	$filename = "../xml/appion_extra.xml";
	$leginondata->mysql->setSQLHost( array('db'=>$dbname) );
	$leginondata->importTables($filename);

	$data=array();
	$data['REF|projects|project']=$selectedprojectId;
	$data['appiondb']=$dbname;
	$project->mysql->SQLInsertIfNotExists('processingdb', $data);

	/*
	* Upload an sample session from downloaded images
	* the images location from centoautoinstallation script 
	* is in /tmp/images
	* TODO: need to change the username and password for the release.
	* Ask user instead of hard code
	*/
	$command = 'imageloader.py --projectid='.$selectedprojectId.' --session=sample --dir=/tmp/images --filetype=mrc --apix=0.82 --binx=1 --biny=1 --df=-1.5 --mag=100000 --kv=120 --description="Sample Session" --jobtype=uploadimage';
	exec_over_ssh("localhost", "root", $password, $command, TRUE);

	// wait 5 seconds for uploadimage to run
	sleep(5);
}

/*
* Now we need to create a new project for Testing.
*/
$project->addProject('Tests', 'Testing Project', 'Leginon Testing Project: Created by auto installation script.', 'None', 'This is a free project.');

/*
 * Redirect to the myamiweb homepage.
 */
// Using the HTTP_HOST is not always working for autoinstaller.
// Try localhost.
$host  = $_SERVER['HTTP_HOST'];
$uri = '/myamiweb';
header("Location: http://localhost$uri");
exit;
?>
