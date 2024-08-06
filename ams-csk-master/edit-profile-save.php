<?php

    /// JHERIMY B. ///

    session_start();
    include "connects.php";

    $editImage = '';

    if (!empty($_FILES['editImage']['name'])) {
        $imageNew = $_FILES['editImage'];

        $imageName = $_FILES['editImage']['name'];
        $imageTemp = $_FILES['editImage']['tmp_name'];
        $imageSize = $_FILES['editImage']['size'];
        $imageError = $_FILES['editImage']['error'];
        $imageType = $_FILES['editImage']['type'];

        $imageExt = explode('.', $imageName);
        $imageActualExt = strtolower(end($imageExt));
        
        $extensions = array('jpg', 'jpeg', 'png', 'pdf');
        if (in_array($imageActualExt, $extensions)) {
            if ($imageError === 0) {
                if ($imageSize < 1000000) {
                    $imageNewName = uniqid('', true) . "." . $imageActualExt;
                    $imagePath = 'uploads/' . $imageNewName;
                    move_uploaded_file($imageTemp, $imagePath);

                    $editImage = $imagePath;
            
                } else {
                    echo '<script>alert("Image size too Large."); window.location.href = "my-profile.php";</script>';
                }
            } else {
                echo '<script>alert("Error occured."); window.location.href = "my-profile.php";</script>';
            }
        } else {
            echo '<script>alert("Image type/ format error."); window.location.href = "my-profile.php";</script>';

        }
    } else {
        $editImage = $_POST['imageProfile'];
    }

    $nameProfile = $_POST['nameProfile'];
    $positionProfile = $_POST['positionProfile'];
    
    $editName = $_POST['editName'];
    $editEmail = $_POST['editEmail'];
    $editPassword = $_POST['editPassword'];
    $editRole = $_POST['editRole'];
    $editPosition = $_POST['editPosition'];

    $editDepartment = $_POST['editDepartment'];
    $editStartDate = $_POST['editStartDate'];
    $editHrsReq = $_POST['editHrsReq'];
    $editAge = $_POST['editAge'];
    $editGender = $_POST['editGender'];
    $editAddress = $_POST['editAddress'];
    $editSchool = $_POST['editSchool'];
    $editJobPosition = $_POST['editJobPosition'];

    $monday = $_POST['mondayIn'] . " " . $_POST['mondayOut'];
    $tuesday = $_POST['tuesdayIn'] . " " . $_POST['tuesdayOut'];
    $wednesday = $_POST['wednesdayIn'] . " " . $_POST['wednesdayOut'];
    $thursday = $_POST['thursdayIn'] . " " . $_POST['thursdayOut'];
    $friday = $_POST['fridayIn'] . " " . $_POST['fridayOut'];
    $saturday = $_POST['saturdayIn'] . " " . $_POST['saturdayOut'];
    $sunday = $_POST['sundayIn'] . " " . $_POST['sundayOut'];

    $editAccount =  "UPDATE users
                    SET name = '$editName', email = '$editEmail', password = '$editPassword', 
                    role = '$editRole', position = '$editPosition', image ='$editImage'
                    WHERE name = '$nameProfile';

                    UPDATE time_in
                    SET name = '$editName'
                    WHERE name = '$nameProfile';

                    UPDATE time_out
                    SET name = '$editName'
                    WHERE name = '$nameProfile';
                    
                    UPDATE tasks
                    SET name = '$editName', department = '$editDepartment'
                    WHERE name = '$nameProfile';
                    
                    UPDATE schedule
                    SET name = '$editName', monday = '$monday', tuesday = '$tuesday', wednesday = '$wednesday', 
                    thursday = '$thursday', friday = '$friday', saturday = '$saturday', sunday = '$sunday'
                    WHERE name = '$nameProfile'; 

                    UPDATE notifications
                    SET name = '$editName', department = '$editDepartment'
                    WHERE name = '$nameProfile'; 
                    
                    UPDATE notices
                    SET name = '$editName'
                    WHERE name = '$nameProfile'; 
                    
                    UPDATE leaves
                    SET name = '$editName', department = '$editDepartment', position = '$editPosition'
                    WHERE name = '$nameProfile'; 
                    
                    UPDATE announcement
                    SET name = '$editName', department = '$editDepartment'
                    WHERE name = '$nameProfile'; ";

    if ($positionProfile === $editPosition) {
        if ($editPosition === 'intern') {
            $editAccount .= "UPDATE int_info
                        SET name = '$editName', department = '$editDepartment', start_date = '$editStartDate', hr_req = '$editHrsReq',
                        age = '$editAge', gender = '$editGender', address = '$editAddress', school = '$editSchool'
                        WHERE name = '$nameProfile'; ";
        } else {
            $editAccount .= "UPDATE emp_info
                        SET name = '$editName', department = '$editDepartment', position = '$editJobPosition', start_date = '$editStartDate',
                        age = '$editAge', gender = '$editGender', address = '$editAddress'
                        WHERE name = '$nameProfile'; ";
        }
    } else {
        if ($editPosition === 'intern') {
            $editAccount .= "INSERT INTO `int_info` (`name`, `department`, `start_date`, `hr_req`, `age`, `gender`, `address`, `school`)
                            VALUES ('$editName', '$editDepartment', '$editStartDate', '$editHrsReq', '$editAge', '$editGender', '$editAddress', '$editSchool');
                            DELETE FROM emp_info
                            WHERE name = '$nameProfile'; ";
        } else {
            $editAccount .= "INSERT INTO `emp_info` (`name`, `department`, `position`, `start_date`, `age`, `gender`, `address`)
                            VALUES ('$editName', '$editDepartment', '$editJobPosition', '$editStartDate', '$editAge', '$editGender', '$editAddress');
                            DELETE FROM int_info
                            WHERE name = '$nameProfile'; ";
        }
    }
    
    
    if ($conn->multi_query($editAccount) === TRUE) {
            echo '<script>alert("Account successfully edited."); window.location.href = "my-profile.php";</script>';
    return;
    } else {
        echo '<script>alert("Error editing account."); window.location.href = "default.php";</script>';
    }  

    mysqli_close($conn);
?>