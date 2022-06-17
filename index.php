<?php
// include "print_types.php";

function myUrlEncode($string) {
    $replacements = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%40', '%26', '%3D', '%2B', '%24', '%2C','%3F', '%23', '%5B', '%5D',"%20","/K");
    $entities = array('!', '*', "'", "(", ")", ";", "@", "&", "=", "+", "$", ",","?", "#", "[", "]"," ","//K");
    return str_replace($entities, $replacements, $string);
}


//set_error_handler('exceptions_error_handler');
function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

if(isset($_GET["type"])){
	$type = ltrim($_GET["type"],'/');
	$type=rtrim($type,'/');
}


function check_folder($folder_name,$folder_path){

    global $global_data;
    preg_match('#\((.*?)\).*#', $folder_name, $match);
    try 
    {           
        $movie_name = str_replace($match[0],"",$folder_name);
        $movie_year=$match[1];
        if(strlen($movie_name)!=0  && strlen($movie_year)!=0){
            $movie_year=$match[1];
            $data = array();
            $data['title']=trim($movie_name);
            preg_match_all('!\d+!', $movie_year, $matches);
            $data['year']=substr(implode("",$matches[0]), 0, 4);
            if($data['year']==false){
                $data['year']=date("Y");
            }else{
                $data['path']='http://'.myUrlEncode(str_replace($_SERVER["DOCUMENT_ROOT"],$_SERVER["SERVER_ADDR"],$folder_path."/".$folder_name.'/'));
                array_push($global_data,$data);
            }
        }
        else {
        echo "it is not a movie folder";
        }       
    } catch (Exception $e) {
    }
}
// Define a function to output files in a directory
function outputFiles($path){
    // Check directory exists or not
    if(file_exists($path) && is_dir($path)){
        // Scan the files in this directory
        $result = scandir($path);
        // Filter out the current (.) and parent (..) directories
        $files = array_diff($result, array('.', '..'));
        if(count($files) > 0){
            // Loop through retuned array
            foreach($files as $file){
                if(is_file("$path/$file")){
                } else if(is_dir("$path/$file")){                                        
                    check_folder($file,$path);                    
                    outputFiles("$path/$file");
                }
            }
        } else{
            echo "ERROR: No files found in the directory.".'<br>';
        }
    } else {
        echo "ERROR: The directory does not exist."."<br>";
    }
}
$scanning_folder_name=$_SERVER["DOCUMENT_ROOT"].'/files/'.$type;
$global_data=array();
outputFiles($scanning_folder_name);
$send_data[]=array('send_data'=>$global_data);
echo json_encode($send_data);
?>











