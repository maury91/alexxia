<?php
/**
 *	Users Management for ALExxia
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
if (PERMISSION::has('users_access')) {
	if (isset($external['groups'])) {
		if (PERMISSION::has('groups_access')) {
		} else 
			$content['error'] = 'no perms';
	} else {
		if (isset($external['config'])) {
			switch ($external['config']['act']) {
				case 'ban' :
					$usr = @DB::assoc(DB::select('id,level,nick','users',' WHERE id = ',$external['config']['id']));
					$content = array('r' => 'n');
					if (USER::level()<$usr['level']) {
						if (DB::update('users',array('banned'=>1),' WHERE id = ',$usr['id']))
							$content['r']='y';
						DB2::update('users',array('banned'=>1),' WHERE nick = ',$usr['nick']);
					}
				break;
				case 'unban' :
					$usr = @DB::assoc(DB::select('id,level,nick','users',' WHERE id = ',$external['config']['id']));
					$content = array('r' => 'n');
					if (USER::level()<$usr['level']) {
						if (DB::update('users',array('banned'=>0),' WHERE id = ',$usr['id']))
							$content['r']='y';
						DB2::update('users',array('banned'=>0),' WHERE nick = ',$usr['nick']);
					}
				break;
				case 'approve' :
					$usr = @DB::assoc(DB::select('id,level,nick','users',' WHERE id = ',$external['config']['id']));
					$content = array('r' => 'n');
					if (USER::level()<$usr['level']) {
						if (DB::update('users',array('actived'=>1),' WHERE id = ',$usr['id']))
							$content['r']='y';
					}
				break;
				case 'groups' :
				
				break;
				case 'delete' :
					$usr = @DB::assoc(DB::select('id,level,nick','users',' WHERE id = ',$external['config']['id']));
					$content = array('r' => 'n');
					if (USER::level()<$usr['level']) {
						if (DB::delete('users',' WHERE id = ',$usr['id']))
							$content['r']='y';
						//@DB2::delete('users',' WHERE nick = ',$usr['nick']);
					}
				break;
				case 'edit' :
				
				break;
				case 'info' :
				
				break;
				case 'list' :
				
				break;
			}
		} else {
			include(LANG::path('a_users.php'));
			$users = DB::select('*','users',' WHERE level >= ',USER::level(),' LIMIT 201');
			$content=array('lang' => array('_new' => $__new),'self' => array('level' => USER::level()),'users' => array());
			$i=0;
			while ($user = DB::assoc($users)) {
				$user['password'] = '';
				if ($i<201)
					$content['users'][] = $user;
				$i++;
			}
			$content['continue'] = $i>200;
			if (PERMISSION::has('groups_access')) {
				include(LANG::path('group.php'));
				include(__base_path.'admin/config/groups.php');
				foreach ($_group_ext as $k => $v) 
					if (is_int($k)&&(!isset($__group_base[$k])))
						$__group_base[$k]=$v;
				for ($i=USER::level();$i>=0;$i--)
					unset($__group_base[$i]);
				unset($__group_base[10]);
				unset($__group_base[11]);
				$content['groups'] = array('e'=>$__group_base);
			} else
				$content['groups'] = 'no';
		}
	}
} else $content['error'] = 'no perms';
?>