<?php 
	
	error_reporting(0);
	set_time_limit(0);
	ini_set("memory_limit","999999M");
	
	//source directory for compare
	$sourceDir = "C:\\Zend\\Apache2\\htdocs\\gdcsubsqaV1";
	//destination directory to compare
	$destinationDir = "C:\\Zend\\Apache2\\htdocs\\gdcsubstrunk";
	//baseDir that needs to shown in the final output
	$baseDir = "gdcsubstrunk";
	
	//list of files/Directory that don't require compare 
	$invalidFiles = array('.','..','.svn','.project','log','compile','scribd.log','log.txt');
		
	function findFilesRecursive($sourceDir, $destDir, $baseDir)
	{
		global $invalidFiles;
		
		$list = array();
		$handle = opendir($sourceDir);
		while(($file = readdir($handle))!==false)
		{
			if(in_array($file,$invalidFiles))
				continue;
				
			$sourcepath = $sourceDir.DIRECTORY_SEPARATOR.$file;
			$destpath = $destDir.DIRECTORY_SEPARATOR.$file;
			//path that need to shown in final output
			$finalPath = $baseDir.DIRECTORY_SEPARATOR.$file;
			$isFile = is_file($sourcepath);
			
			if($isFile){
				if(is_file($destpath) && file_exists($destpath)){
					if(md5_file($sourcepath) === md5_file($destpath)){
						$list[] = array('file'=>$finalPath,'message'=>'Match', 'txtcolor'=>'green');		
					}else{
						$list[] = array('file'=>$finalPath,'message'=>'Mis-Match', 'txtcolor'=>'red');
					}
				}else{
					$list[] = array('file'=>$finalPath,'message'=>'New File', 'txtcolor'=>'blue');
				}
			}else{
				$list = array_merge($list,findFilesRecursive($sourcepath, $destpath, $finalPath));
			}
		}
		closedir($handle);
		return $list;
	}
	
	//compare source and dest directory recursively
	$list = findFilesRecursive($sourceDir, $destinationDir, $baseDir);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset=utf-8>
<title>Folder Compare</title>
</head>
<style type="text/css">
p{
	margin:10px;
}
tr{
	position:relative;
	height:15px;
}
</style>
<body>
<table cellspacing="0" cellpadding="0" width="100%" border="1">
<thead> 
	<tr> 
		<th>File Path</th>
		<th>Status</th>							
	</tr> 
</thead>
<tbody> 
	<?php 
		foreach($list as $values){
			$color = ($values['message'] === '')
	?>					
	<tr>
	<td style="color:<?php echo $values['txtcolor'];?>" valign="top">
		<p><?php echo $values['file'];?></p>
	</td>
	<td>
		<p style="color: <?php echo $values['txtcolor'];?>; font-style: italic"><?php echo $values['message'];?></p>
	</td>
	</tr>
	<?php }?>
</tbody>
</table>
</body>
</html>