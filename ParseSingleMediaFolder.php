<?php

if(isset($_GET["f"])){
	$dir = ltrim($_GET["f"],'/');
	$dir = rtrim($dir,'/');
	
}

// Run the recursive function
$folder_name=$_SERVER["DOCUMENT_ROOT"].'/files/'.$dir;
$domain = "http://".$_SERVER['SERVER_NAME'];

// $response = scan('../'.$dir);
$response = scan($folder_name);


// This function scans the files folder recursively, and builds a large array
function scan($folder_name){

	$files = array();

	// Is there actually such a folder/file?

	if(file_exists($folder_name)){
	
		foreach(scandir($folder_name) as $f) {
		
			if(!$f || $f[0] == '.') {
				continue; // Ignore hidden files
			}

			if(is_dir($folder_name . '/' . $f)) {
				

				// The path is a folder

				$files[] = array(
					"name" => $f,
					"type" => "folder",
					"path" => ltrim($folder_name . '/' . $f,'../')
					// "items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
				);
			}
			
			else {

				// It is a file
				///////////////////////////// 
				if (strpos($f, '.jpg') == false && strpos($f, '.png') == false &&
					(
						strpos(strtolower($f), '.avi') !== false || 
						strpos(strtolower($f), '.flv') !== false ||
						strpos(strtolower($f), '.mp4') !== false ||
						strpos(strtolower($f), '.mkv') !== false ||
						strpos(strtolower($f), '.m4v') !== false ||
						strpos(strtolower($f), '.webm') !== false ||
						strpos(strtolower($f), '.srt') !== false ||
						strpos(strtolower($f), '.zip') !== false ||
						strpos(strtolower($f), '.rar') !== false 
					)
					){ 
					$files[] = array(
						"name" => $f,
						"type" => "file",
						"path" => ltrim($folder_name . '/' . $f,'../'),
						"size" => filesize($folder_name . '/' . $f) // Gets the size of this file
					);
				}
			}
		}
	
	}

	return $files;
}



// Output the directory listing as JSON

header('Content-type: application/json');

echo json_encode(array(
	"name" => "files",
	"type" => "folder",
	"path" => $folder_name,
	"domain" => $domain,
	"items" => $response
));

