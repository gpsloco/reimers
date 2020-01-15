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
 File: mod.pages.php
-----------------------------------------------------
 Purpose: Pages class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Pages {
	
	var $return_data	= '';
	
    /** ----------------------------------------
    /**  Constructor
    /** ----------------------------------------*/
    
	function Pages()
	{
	}
	
	function load_site_pages()
	{
       global $TMPL, $DB, $REGX, $PREFS;
        
		$this->return_data = '';

        $sites	= ( ! $TMPL->fetch_param('site')) ? '' : $TMPL->fetch_param('site');
		$current_site = $PREFS->ini('site_short_name');

        // Always include the current site

		if ($sites == '')
        {
        	return $this->return_data;
        }		
		
		$site_names = explode('|', $sites);
		
		if ( ! in_array($current_site, $site_names))
		{
			$site_names[] = $current_site;
		}

		$names = '(';
				
		foreach ($site_names as $name)
		{
			$names .= "'".$DB->escape_str($name)."', ";
		}
				
		$names = substr($names, 0, -2).')';
		
		$query = $DB->query("SELECT site_pages, site_name, site_id 
								 FROM exp_sites AS es
								 WHERE es.site_name IN ".$names);

		if ($query->num_rows > 0)
		{
			$PREFS->core_ini['site_pages'] = array();
			
			foreach($query->result as $row)
			{
				$out = $REGX->array_stripslashes(unserialize($row['site_pages']));
				$PREFS->core_ini['site_pages'][$row['site_id']] = $out[$row['site_id']];
			}
		}
		
		return $this->return_data;
	}

}
// End Pages Class
?>