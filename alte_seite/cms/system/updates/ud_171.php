<?php
/*
=====================================================
 ExpressionEngine - by EllisLab
-----------------------------------------------------
 http://expressionengine.com/
-----------------------------------------------------
 Copyright (c) 2003 - 2011 EllisLab, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
 http://expressionengine.com/docs/license.html
=====================================================
 File: ud_171.php
-----------------------------------------------------
 Purpose: Performs version 1.7.1 update
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}

class Updater {

	function do_update()
	{
		global $DB;

		// MySQL 5.5 no longer accepts a display width on timestamp fields- so we need to ditch them!
		$Q = array();

		$Q[] = "ALTER TABLE `exp_comments` CHANGE edit_date edit_date timestamp";

		
		/** -------------------------------
		/**  Is the Gallery module installed?
		/** -------------------------------*/
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_modules WHERE module_name = 'Gallery'");
        
        if ($query->row['count'] > 0)
        {
			$Q[] = "ALTER TABLE exp_gallery_entries CHANGE edit_date edit_date timestamp";
			$Q[] = "ALTER TABLE exp_gallery_comments CHANGE edit_date edit_date timestamp";
		}

		// run our queries
		foreach ($Q as $sql)
		{
			$DB->query($sql);
		}

		return TRUE;
	}
	/* END */
}	
/* END CLASS */


?>