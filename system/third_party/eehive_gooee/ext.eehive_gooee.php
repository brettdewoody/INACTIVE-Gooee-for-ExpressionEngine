<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eehive_gooee_ext { 
	
	var $name		= 'EE Hive - Gooee';
	var $version 		= '1.0';
	var $description	= 'Gooee binds your channel entries together with a string of your choice. The glue is only added between your entries, but absent from the last entry.';
	var $settings_exist	= 'n';
	var $docs_url		= 'http://www.ee-hive.com/add-ons/gooee';

	var $settings        = array();

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	function Eehive_gooee_ext($settings='')
	{
		$this->EE =& get_instance();
		
		$this->settings = $settings;
	}
	
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	function activate_extension()
	{
		$this->settings = array();
		
		
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'gooee',
			'hook'		=> 'channel_entries_tagdata_end',
			'settings'	=> serialize($this->settings),
			'priority'	=> 1000045,
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$this->EE->db->insert('extensions', $data);
	}
	
	
	
	/**
	 * Append the GLUE to each entry, but not the last
	 * 
	 */
	function gooee($tagdata, $row, $channel) {
		
			$this->EE =& get_instance();
			
			// Fetch the gooee parameter from the exp:channel:entries tag
			$glue = $this->EE->TMPL->fetch_param('gooee', '');
		
			// if Gooee is in use
			if ($glue != '') {
			
			$limit = $this->EE->TMPL->fetch_param('limit');
			$real_limit = (empty($limit) ? 100 : $limit);
			$total_results = $row['total_results'];
			$count = $row['count'];
			
			$entries = ($real_limit > $total_results ? $total_results : $real_limit);
				
			$glued_content = '';
				
			if ($count < $entries) {
				$glued_content = $tagdata . $glue;
					return $glued_content;
			} else {
				$glued_content = $tagdata;
					return $glued_content;
			}
			  					
		} 
		// Otherwise just return the entries
		else {
			return $tagdata;
		}
	}

	
	
	
	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		// init data array
		$data = array();

		// Add version to data array
		$data['version'] = $this->version;

		// Update records using data array
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('exp_extensions', $data);
	}
	
	
	
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}





}
// END CLASS

/* End of file ext.eehive_gooee.php */