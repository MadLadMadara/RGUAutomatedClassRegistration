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
		
		// sort tsxt data into key vallue array
		
	
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
					case 'Semester':
						$sortedFileText['Semester'] = $cleanFlieTextArray[$i + 1];
							$i++;
							
						break;
					case 'Module':
						$sortedFileText['Module'] = $cleanFlieTextArray[$i + 1];
							$i++;
							
						break;
					case 'Module Code':
						$sortedFileText['Module_Code'] = $cleanFlieTextArray[$i + 1];
							$i++;
						break;
					case 'Lecturer':
						$sortedFileText['Lecturer'] = $cleanFlieTextArray[$i + 1];
							$i++;
						break;
					case 'Week Number':
						$sortedFileText['Week_Number'] = $cleanFlieTextArray[$i + 1];
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
	


		// now uploading all to the db.


		// find if module exist, if so get id
		$moduleFound = false;
		$moduleId = null;
		$stmtCheckModuleExists = $db->prepare("SELECT module_id FROM modules WHERE module_code = ? LIMIT 1");
		$stmtCheckModuleExists->bind_param('s', $sortedFileText['Module_Code']);
		$stmtCheckModuleExists->execute();
		$stmtCheckModuleExists->bind_result($currentModuleId);
		while ($stmtCheckModuleExists->fetch()) {
			$moduleFound = true;
			$moduleId = $currentModuleId;
		}
		$stmtCheckModuleExists->close();
		// if the module is not found insert the module
		if(!$moduleFound){
			$insertNewModule = $db->prepare("INSERT INTO modules (school_department_name, module_code, module_name) VALUES (?, ?, ?)");
			$insertNewModule->bind_param("sss", $sortedFileText['Department'], $sortedFileText['Module_Code'], $sortedFileText['Module']);
			$insertNewModule->execute();
			$moduleId = $insertNewModule->insert_id;
			$insertNewModule->close();
		}
		// upload the class information

		// check if class has been uploaded already
		
		$stmtCheckModuleExists = $db->prepare("SELECT class_id FROM class WHERE module_id_ref = ? AND semester = ? AND session = ? AND week = ?  LIMIT 1");
		$stmtCheckModuleExists->bind_param('iisi', $moduleId, $sortedFileText['Semester'], $sortedFileText['Session'], $sortedFileText['Week_Number']);
		$stmtCheckModuleExists->execute();
		$stmtCheckModuleExists->bind_result($currentClassId);
		while ($stmtCheckModuleExists->fetch()) {
			redirect("../upload.php?message=Class has already been uploaded");
			die();
		}
		$stmtCheckModuleExists->close();
		// class dose not exist can add class
		$classId = null;

		$insertNewModule = $db->prepare("INSERT INTO class (module_id_ref, semester, lecturers, session, week) VALUES (?, ?, ?, ?, ?)");
		$insertNewModule->bind_param("iissi", $moduleId, $sortedFileText['Semester'], $sortedFileText['Lecturer'], $sortedFileText['Session'], $sortedFileText['Week_Number']);
		$insertNewModule->execute();
		$classId = $insertNewModule->insert_id;
		$insertNewModule->close();

		// add students

		foreach($sortedFileText['students'] as $stu){
			$insertStudent = $db->prepare("INSERT INTO student (class_id_ref, student_number, surname, first_name, course, attend) VALUES (?, ?, ?, ?, ?, ?)");
			$insertStudent->bind_param("issssi", $classId, $stu['MatNum'], $stu['Surname'], $stu['Firstname'], $stu['Course'], $stu['signin']);
			$insertStudent->execute();
			$insertStudent->close();
		}
		

		$db->commit();
		$db->close();
	}else{
		redirect("../upload.php?message=No file uploaded");
		die();
	}

	redirect("../upload.php?message=File uploaded successfully.");
	die();


?>	