<?php
	function upload_file($file,$path)
	{
		$file_name = rand(1000,9999).'-'.$file['name'];
		$file_name = str_replace(' ', '-', $file_name);
		$file_tmp = $file['tmp_name'];

		move_uploaded_file($file_tmp, $path.$file_name);
		return $file_name;
	}

	function upload_two_files($file,$path){
		$files_d = [];
		for ($i=0; $i < count($file['name']) ; $i++) { 
			$file_name = rand(1000,9999).'-'.$file['name'][$i];
			$file_name = str_replace(' ', '-', $file_name);

			$file_tmp = $file['tmp_name'][$i];
			move_uploaded_file($file_tmp, $path.$file_name);
			array_push($files_d, $file_name);
			if ($i == 1) {
				return $files_d;
			}
		}
		return $files_d;
	}

	function set_flash($title,$description){
		$_SESSION[$title] = $description;
	}

	function display_flash($title){
		if (isset($_SESSION[$title])) {
			echo $_SESSION[$title];
			unset($_SESSION[$title]);
		}
	}

	function format_date($date){
		return date('F d, Y', strtotime($date));
	}
	function redirect($link){
		header("location:".$link);
	}
	function displayError($message)
	{
	    return '<div class="alert alert-danger">' . $message . '</div>';
	}

	function displayWarning($message)
	{
	    return '<div class="alert alert-warning">' . $message . '</div>';
	}

	function displaySuccess($message)
	{
	    return '<div class="alert alert-success">' . $message . '</div>';
	}
?>