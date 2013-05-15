<?php
/**
 *	Installation (lang file) for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
	$req = array(
		'version' => 'PHP Version >= 5.1',
		'rglobals' => 'Register Globals OFF',
		'zlib' => 'Zlib Compression Support',
		'db' => 'Database Support: (mysql, mysqli)',
		'json' => 'JSON Support',
		'write' => 'config/ and cache/ writables',
		'http' => 'Curl or allow_url_fopen enabled',
		'output' => 'Output Buffering',
		'bcrypt' => 'CRYPT_BLOWFISH enabled'
	);
	$__req = 'Requirements';
	$__req_d = 'To install ALExxia all requirements listed here must be indicated by an YES, if some condition is referred as NO please correct it to begin the installation';
	$rac = array(
		'magic' => 'Magic Quotes GPC OFF',
		'safe' => 'Safe Mode OFF',
		'error' => 'Display Errors OFF',
		'upload' => 'Upload Files',
		'double' => 'Double database support'
	);
	$__rac = 'Recommended Settings';
	$__rac_d = 'These settings are recommended, however ALExxia is able to function well.';
	$__y = 'Yes';
	$__n = 'No';
	$__lang_sel = 'Select Language';
	$__continue = 'Next';
	$__configuration = 'General Configuration';
	$__site_with = 'My new web-site with ALExxia';
	$__name = 'Site Name';
	$__name_d = 'Enter the name of your site';
	$__desc = 'Description';
	$__desc_d = 'Enter a description of the site that will be used by the search engines. Generally, optimal is a maximum of 20 words.';
	$__email = 'Founder Email';
	$__email_d = 'Please enter your email address, this will be the email address of the founder of the site.';
	$__nick = 'Founder Nick';
	$__nick_d = 'You can change the nickname of the founder, is normally <b>admin</b>';
	$__pass = 'Founder Password';
	$__pass_d = 'Set the password of the founder of the site and confirm it in the field below';
	$__pass2 = 'Confirm Password';
	$__not_nick = 'The nickname must be at least 4 alphanumeric characters';
	$__not_pass = 'The password must be at least 6 characters';
	$__not_pass2 = 'The password confirmation does not match';
	$__not_email = 'Invalid Email';
	$__not_sname = 'The site name is too short';
	$__prev = 'Back';
	$__database = 'Database Configuration';
	$__dbt = 'Database Type';
	$__dbt_d = 'MySQLi is faster than MySQL. <br/> SQLite and SQLite3 databases are stored on local files.';
	$__host = 'Host Name';
	$__host_d = 'Usually is localhost';
	$__dbfile = 'Filename';
	$__dbfile_d = 'Select the name by which to call (or you called) your database';
	$__dbuser = 'Username';
	$__dbuser_d = 'Usually is root';
	$__dbpass = 'Password';
	$__dbpass_d = 'Password to access the database';
	$__dbname = 'Database Name';
	$__dbname_d = 'Name of the database in which to install ALExxia, this database will be used for the storage of the extensions and users of the site';
	$__dbpref = 'Prefix Database';
	$__dbpref_d = 'If you are not available to more than one database you can use a prefix to install more than one site with Alexxia';
	$__db2 = 'Secondary database';
	$__db2_d = 'If all you have more than one database by filling out the following fields ALExxia will use the second database for sensitive data. <br/> For a more effective security is not recommended a database accessible to the primary database';
	$__not_db = 'Unable to connect to the database, verify that the host, username and password are correct';
	$__not_dbname = 'The database name is not valid, check that you have spelled correctly and the user have permission to access';
	$__not_db = 'Unable to connect to the database, verify host, username and password are correct';
	$__not_dblite = 'Failed to create the database file, check write permissions in config /';
	$__not_db2 = 'Unable to connect to the secondary database, if you do not want to use leave this field blank';
	$__not_dbname2 = 'The name of the secondary database is invalid';
	$__sum = 'Summary';
	$__end = 'Installation Complete';
	$__end_d = 'The installation was completed successfully, click on the button to finish below';
	$__fine = 'Go to your web site';
	$__secure = 'The connection is secure';
	$__loading = 'Loading...';
?>