<?php
   if(isset($_FILES['epubfile'])){
      $errors= array();
      $file_name = $_FILES['epubfile']['name'];
      $file_size =$_FILES['epubfile']['size'];
      $file_tmp =$_FILES['epubfile']['tmp_name'];
      $file_type=$_FILES['epubfile']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['epubfile']['name'])));

      $file_name_clean = preg_replace("/[^A-Za-z0-9\.]/", "_", $file_name);


      $extensions= array("epub");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a epub file.";
      }
      
      if($file_size > 15000000){
         $errors[]='File size must be less than 15 MB';
      }
      echo "<html><body>";
      if(empty($errors)==true){
           $didUpload = move_uploaded_file($file_tmp,"/var/www/html/epub/".$file_name_clean);
            if ($didUpload) {
            	echo "The file " . basename($file_name_clean) . " has been uploaded<br/>";
	        $output=null;
		$retval=null;
		exec("ebook-convert /var/www/html/epub/".$file_name_clean." /var/www/html/kindle/".$file_name_clean." --language en --output-profile kindle", $output, $retval);
		if ($retval == 0){
			echo "<a href='https://ovh.batiot.com/kindle/$file_name_clean' >$file_name_clean</a>";
		} else {
			echo "Returned with status $retval and output :<br/>";
			print_r($output);
		}
	    } else {
            	echo "An error occurred. Please contact the administrator.";
            }
      }else{
         print_r($errors);
      }
      echo "</body></html>";
   }
?>

