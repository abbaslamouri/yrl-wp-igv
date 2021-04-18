<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if (!class_exists('IWPGVDatabase')) {
	class IWPGVDatabase extends IWPGVDashboard {

		public function __construct() {
			
			// Declare and set global variables
			global $wpdb;

		
			// Define the maximum file size to be uploaded (from the server)
			// $this->server_max_file_size = ini_get('upload_max_filesize');

			// // Define the maximum number of files that can be uploaded (from the server)
			// $this->max_file_upload = ini_get('max_file_uploads');
			
			// // Define the maximum post size to be uploaded (from the server)
			// $this->post_max_size = ini_get('post_max_size');
	
			// Define the plugin upload directory path with trailing slashes (examle:  /home/yrlusc5/public_html/test/uploads/)
			//$this->upload_dir = wp_upload_dir()['path']."/";
			
			$this->wpdb = $wpdb;
			
			// Declare database table
			$this->db_table = "{$wpdb->prefix}{$this->prefix}_files";

			// Declare database character set
			$this->charset_collate = $wpdb->get_charset_collate();
			
		
			// Declare upload directory
			//$this->file_path = $this->upload_dir;
			
			
		}	// END function __construct()



    	/* Create database table
		*
		*/
		public function create_db_table() {

      
      	// Declare database table
		  //$this->db_table = $wpdb->prefix . "files";

			
			// Create files table if it does not exist 	
			$sql = "CREATE TABLE $this->db_table (
				id int(9) UNSIGNED AUTO_INCREMENT,
				name varchar(300) DEFAULT '' NOT NULL,
				type varchar(300) DEFAULT '' NOT NULL,
				size int(9) DEFAULT 0 NOT NULL,
				file_path varchar(300) DEFAULT '' NOT NULL,
				created_date datetime,
				updated_date datetime,
				UNIQUE KEY id (id)
			) $this->charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			return $sql;
			
		}	// 	END public function create_file_db()




		/* Update database table 
		*
		*/
		public function update_db_table() {

			
			
			// 	To update a table, change the db_version number, add the new fields here and reactivate the plugin
			$sql = "CREATE TABLE $this->db_table (
				id int(9) UNSIGNED AUTO_INCREMENT,
				file_name varchar(300) DEFAULT '' NOT NULL,
				file_type varchar(300) DEFAULT '' NOT NULL,
				file_size int(9) DEFAULT 0 NOT NULL,
				file_path varchar(300) DEFAULT '' NOT NULL,
				chart_type varchar(300) DEFAULT '' NOT NULL,
				chart_notes text DEFAULT '',
				created_date datetime,
				updated_date datetime,
				UNIQUE KEY id (id)
			);";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
		}	// 	END public function update_file_db()



		

		public function reset_auto_increment() {

			// Reset table ID auto increment
			return $this->wpdb->query( "ALTER TABLE $this->db_table AUTO_INCREMENT = 1235");

		}
		
	
		/** check if table exists 
		*	returns last insert ID on success, false on failure
		*/
		public function table_exists() {
			
			if($this->wpdb->get_var("SHOW TABLES LIKE '$this->db_table'") == $this->db_table) {
				return true;
			}else {
				return false;	
			}
		}
		
		
		
		/** drop table
		*	returns last insert ID on success, false on failure
		*/
		public static function drop_table() {
		
	
		
			if ($this->wpdb->query("DROP TABLE $this->db_table")) {
				return true;
			} else {
			return false;
			}
			
		}
		
		
		
		
		/** Insert record into database
		*	returns last insert ID on success, false on failure
		*/
		public function create ($data = []) {
			
			
			if (!$this->wpdb->insert($this->db_table, $data)) {
				return false;
			} else {
				return $this->wpdb->insert_id;
			}
					
		}  	// END protected function insert_data ($db_table = "", $data = [])
		
		
		/** Insert record into database
		*	returns last insert ID on success, false on failure
		*/
		public function update($data = [], $where = []) {
			
	
			
			if (false === $this->wpdb->update($this->db_table, $data, $where)) {
				return false;
			} else {
				return true;
			}
					
		}  	// END protected function update($db_table = "", $data = [])
		
		
		
		
		//	Delete record by ID	
		public function delete($key_value = []) { 
						
			return ($this->wpdb->delete($this->db_table, $key_value))? true : false;
		  	
		 } 	// END protected function delete_by_id($db_table = "", $id_pair = ["id" => 0]) 
		
		
		
		/** Retrieve records from the databse
		*	returns objects if any, otherwise it returns false
		*/
		public function find_all () {
			
	
			
			$records = $this->wpdb->get_results("SELECT * FROM $this->db_table ORDER BY created_date ASC");	
			return ($records) ?  $records : false;	
			
					
		}  	// END protected function db_find_all ($db_table) 
			
			
			
		
		/** Retrieve a record by its ID 
		*	returns object if any, otherwise it returns false
		*/	
		public function find_by_id($id = 0) { 
		
	
			
			$object  = $this->wpdb->get_results("SELECT * FROM $this->db_table WHERE id = '$id' LIMIT 1");
		  	return ($object) ?  array_shift($object) : false; 
			
		 } 	//END protected function db_find_by_id($db_table = "", $id = 0) 
		 
		 
		 
		 /** Retrieve a record by its sql 
		*	returns objects if any, otherwise it returns false
		*/	
		public function find_by_sql($sql = "") { 
		
	
			
			if ($objects  = $this->wpdb->get_results($sql)) {
		  		return (($objects) ?  $objects : false); 
			} else {
				return false;
			}
			
		 } 	// END protected function db_find_by_sql($sql = "")
		 
		  
	}	// END class Database
	
}	// END if (!class_exists('Database'))

