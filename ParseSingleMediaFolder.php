<?php

if(isset($_GET["f"])){
	$dir = ltrim($_GET["f"],'/');
	$dir = rtrim($dir,'/');
	
}

// Run the recursive function
$response = scan('../'.$dir);


// This function scans the files folder recursively, and builds a large array
function scan($dir){

	$files = array();

	// Is there actually such a folder/file?

	if(file_exists($dir)){
	
		foreach(scandir($dir) as $f) {
		
			if(!$f || $f[0] == '.') {
				continue; // Ignore hidden files
			}

			if(is_dir($dir . '/' . $f)) {
				

				// The path is a folder

				$files[] = array(
					"name" => $f,
					"type" => "folder",
					"path" => ltrim($dir . '/' . $f,'../')
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
						"path" => ltrim($dir . '/' . $f,'../'),
						"size" => filesize($dir . '/' . $f) // Gets the size of this file
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
	"path" => $dir,
	"items" => $response
));

