<?php

    /// JHERIMY B. ///

    session_start();
    include "connects.php";


    $status = $statusMsg = ''; 
    if(isset($_POST["create"])){ 
        $imageNew = $_FILES['createImage'];

        $imageName = $_FILES['createImage']['name'];
        $imageTemp = $_FILES['createImage']['tmp_name'];
        $imageSize = $_FILES['createImage']['size'];
        $imageError = $_FILES['createImage']['error'];
        $imageType = $_FILES['createImage']['type'];

        $imageExt = explode('.', $imageName);
        $imageActualExt = strtolower(end($imageExt));
        
        $extensions = array('jpg', 'jpeg', 'png', 'pdf');
        if (in_array($imageActualExt, $extensions)) {
            if ($imageError === 0) {
                if ($imageSize < 1000000) {
                    $imageNewName = uniqid('', true) . "." . $imageActualExt;
                    $imagePath = 'uploads/' . $imageNewName;
                    move_uploaded_file($imageTemp, $imagePath);

                    $newName = $_POST['createName'];
                    $newEmail = $_POST['createEmail'];
                    $newPassword = $_POST['createPassword'];
                    $newRole = $_POST['createRole'];
                    $newPosition = $_POST['createPosition'];

                    $monday = $_POST['mondayIn'] . " " . $_POST['mondayOut'];
                    $tuesday = $_POST['tuesdayIn'] . " " . $_POST['tuesdayOut'];
                    $wednesday = $_POST['wednesdayIn'] . " " . $_POST['wednesdayOut'];
                    $thursday = $_POST['thursdayIn'] . " " . $_POST['thursdayOut'];
                    $friday = $_POST['fridayIn'] . " " . $_POST['fridayOut'];
                    $saturday = $_POST['saturdayIn'] . " " . $_POST['saturdayOut'];
                    $sunday = $_POST['sundayIn'] . " " . $_POST['sundayOut'];
                
                    $addAccount =   "INSERT INTO `users` (`email`, `password`, `name`, `role`, `position`, `image`) 
                                    VALUES ('$newEmail', '$newPassword', '$newName', '$newRole', '$newPosition', '$imagePath'); 
                                    INSERT INTO `schedule` (`name`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`)
                                    VALUES ('$newName', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday'); ";
                    
                    $newDepartment = $_POST['createDepartment'];
                    $newStartDate = $_POST['createStartDate'];
                    $newHrsReq = $_POST['createHrsReq'];
                    $newAge = $_POST['createAge'];
                    $newGender = $_POST['createGender'];
                    $newAddress = $_POST['createAddress'];
                    $newSchool = $_POST['createSchool'];
                    $newJobPosition = $_POST['createJobPosition'];
                
                    if ($newPosition === 'intern') {
                        $addAccount .= "INSERT INTO `int_info` (`name`, `department`, `start_date`, `hr_req`, `age`, `gender`, `address`, `school`)
                        VALUES ('$newName', '$newDepartment', '$newStartDate', '$newHrsReq', '$newAge', '$newGender', '$newAddress', '$newSchool')";
                    } else {
                        $addAccount .= "INSERT INTO `emp_info` (`name`, `department`, `position`, `start_date`, `age`, `gender`, `address`)
                        VALUES ('$newName', '$newDepartment', '$newJobPosition', '$newStartDate', '$newAge', '$newGender', '$newAddress')";
                    }
                
                    if ($conn->multi_query($addAccount) === TRUE) {
                            echo '<script>alert("Account successfully added."); window.location.href = "attendance-create-acc.php";</script>';
                    return;
                    } else {
                        echo '<script>alert("Error creating account."); window.location.href = "attendance-create-acc.php";</script>';
                    }
                } else {
                    echo '<script>alert("Image size too Large."); window.location.href = "attendance-create-acc.php";</script>';
                }
            } else {
                echo '<script>alert("Error occured."); window.location.href = "attendance-create-acc.php";</script>';
            }
        } else {
            echo '<script>alert("Image type/ format error."); window.location.href = "attendance-create-acc.php";</script>';

        }
    } 

    mysqli_close($conn);
?>