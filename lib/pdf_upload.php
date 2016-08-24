<?php
	require_once('/masters/master.php');
	require_once('/masters/PDF2Text.php');
	// functions

	// http://stackoverflow.com/questions/5540886/extract-text-from-doc-and-docx

		
   // main 
	if (session_status() == PHP_SESSION_NONE)
    	session_start();
   	// check if file has been uploaded
	if($_FILES['file']['error'] == UPLOAD_ERR_OK){

    	$file_Name = $_FILES['file']['name'];
	    $file_loc = $_FILES['file']['tmp_name'];
		$file_size = $_FILES['file']['size'];
		$file_type = $_FILES['file']['type'];
		// check file type
	
		if($_FILES['file']['type'] !== "application/octet-stream"){

			redirect("../upload.php?message=".$_FILES['file']['type']." is not valid");
			die();

		}

		/*
			parse file and clean the text up
		*/ 
		$cleanFlieTextArray = [];
		foreach (explode("</p>",DocumentParser::parseFromFile($file_loc, $file_type)) as $val) {
			array_push($cleanFlieTextArray, trim(preg_replace('/\s\s+/', ' ', strip_tags($val))));
		}
		var_dump($cleanFlieTextArray); 
		// sort tsxt data into key vallue array
		/*
			output
			array (size=7)
  'students' => 
    array (size=5)
      0 => 
        array (size=5)
          'MatNum' => string '1034567' (length=7)
          'Surname' => string 'Banner' (length=6)
          'Firstname' => string 'Bruce' (length=5)
          'Course' => string 'CS' (length=2)
          'signin' => int 0
      1 => 
        array (size=5)
          'MatNum' => string '1134567' (length=7)
          'Surname' => string 'Potts' (length=5)
          'Firstname' => string 'Pepper' (length=6)
          'Course' => string 'BIT' (length=3)
          'signin' => int 0
      2 => 
        array (size=5)
          'MatNum' => string '1234567' (length=7)
          'Surname' => string 'Pym' (length=3)
          'Firstname' => string 'Hope' (length=4)
          'Course' => string 'CASD' (length=4)
          'signin' => int 0
      3 => 
        array (size=5)
          'MatNum' => string '1334567' (length=7)
          'Surname' => string 'Quill' (length=5)
          'Firstname' => string 'Petter' (length=6)
          'Course' => string 'CS' (length=2)
          'signin' => int 0
      4 => 
        array (size=5)
          'MatNum' => string '1434567' (length=7)
          'Surname' => string 'Bowie' (length=5)
          'Firstname' => string 'George' (length=6)
          'Course' => string 'CS' (length=2)
          'signin' => int 1
  'Department' => string 'Faculty of Design and Technology School of Computer Science and Digital Media' (length=77)
  'Session' => string '2016-2017 Semester 1' (length=20)
  'Module' => string 'Administrators for the win' (length=26)
  'Module Code' => string 'CM_2024' (length=7)
  'Lecturer' => string 'Dr Hank Pym' (length=11)
  'Week Number' => string '1' (length=1)


		*/
	
		 // dev not... dont ask me how this works 
		$sortedFileText = [];
		$sortedFileText['students'] = [];
		$studentIndex = 1;
		$skip = false;
		for ($i=0; $i < count($cleanFlieTextArray); $i++) { 


			if(!$skip){
				
				switch ($cleanFlieTextArray[$i]) {
					case 'Department':
						$sortedFileText['Department'] = $cleanFlieTextArray[$i + 1];
							$i++;
						break;
					case 'Session':
						$sortedFileText['Session'] = $cleanFlieTextArray[$i + 1];
							$i++;
							
						break;
					case 'Module':
						$sortedFileText['Module'] = $cleanFlieTextArray[$i + 1];
							$i++;
							
						break;
					case 'Module Code':
						$sortedFileText['Module Code'] = $cleanFlieTextArray[$i + 1];
							$i++;
						break;
					case 'Lecturer':
						$sortedFileText['Lecturer'] = $cleanFlieTextArray[$i + 1];
							$i++;
						break;
					case 'Week Number':
						$sortedFileText['Week Number'] = $cleanFlieTextArray[$i + 1];
						break;
					default:
						$skip = true;
						break;
				}
				continue; 
			}
			if($cleanFlieTextArray[$i] == '0'){
				$i += 5;
				continue;
			}
			if($i + 1 >= count($cleanFlieTextArray)){
				break;
			}
			$oneStudent = [];
			$oneStudent['MatNum'] = $cleanFlieTextArray[$i + 1];
			$oneStudent['Surname'] = $cleanFlieTextArray[$i + 2];
			$oneStudent['Firstname'] = $cleanFlieTextArray[$i + 3];
			$oneStudent['Course'] = $cleanFlieTextArray[$i + 4];
			if($i + 5 < count($cleanFlieTextArray)){

					 if($cleanFlieTextArray[$i + 5] == "" || $cleanFlieTextArray[$i + 5] == " "){
						$oneStudent['signin'] = 0;
						$i+=5;
					}else if($cleanFlieTextArray[$i + 5] != ($studentIndex + 1)){
						$oneStudent['signin'] = 1;
						$i+=5;
					
					}else{
						$oneStudent['signin'] = 0;
						$i+=4;
					}
					
			}else{
				$oneStudent['signin'] = 0;
				$i+=5;
			}

			$studentIndex += 1;
			array_push($sortedFileText['students'] , $oneStudent);
		}
		 echo var_dump($sortedFileText);
		// insert to db

		 	// query to see if modual exists

		 		// insert if it dosent






		// return back message

		
	}else{
		redirect("../upload.php?message=No file uploaded");
		die();
	}



		
		
    
 

?>	