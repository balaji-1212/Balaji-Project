<?php
if ( ! defined('ABSPATH') ) {
	exit('Direct\'s not allowed');
}
//save files uploaded by user and modify data before inserting to database
add_filter('cf7d_modify_form_before_insert_data', 'cf7d_modify_form_before_insert_data_cb');
if ( ! function_exists('cf7d_modify_form_before_insert_data_cb') ) {
	function cf7d_modify_form_before_insert_data_cb( $cf7 ) {
		//if it has at least 1 file uploaded
		if ( is_object($cf7) && isset($cf7->uploaded_files) && count($cf7->uploaded_files) > 0 ) {
			$upload_dir         = wp_upload_dir();
			$cf7d_upload_folder = cf7d_upload_folder();
			$dir_upload         = $upload_dir['basedir'] . '/' . $cf7d_upload_folder;
			wp_mkdir_p($dir_upload);
			foreach ( $cf7->uploaded_files as $k => $v ) {
				if(!empty($v)) {
					$file = $v;
					if(is_array($file)) $file = $file[0];
					$file_name = basename($file);
					$file_name = wp_unique_filename($dir_upload, $file_name);
					$dst_file  = $dir_upload . '/' . $file_name;
					if ( @copy($file, $dst_file) ) {
						$cf7->posted_data[ $k ] = $upload_dir['baseurl'] . '/' . $cf7d_upload_folder . '/' . $file_name;
					}
				}
			}
		}
		return $cf7;
	}
}
