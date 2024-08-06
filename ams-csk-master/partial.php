<?php include 'connects.php';?>

<?php
    // This query is for USERS table which will be used to get and fetch data from data base also in making intialization
    
 
    if (isset($_Get['name'])) {
        // echo "Getting Data";
        //Get the ID and other details
        $name = $_GET['name'];
        //Query
        $sql = "SELECT * FROM users WHERE name=$name";
        //Execute
        $res = mysqli_query($conn, $sql);
        //Count the rows
        $count = mysqli_num_rows($res);
    
        if ($count == 1) {
           
            $name = $rows['name'];
            
            $image = $rows['image'];
        } 
    
    }
    // $user_name = $name;
    // $user_position= $position;
    // $user_role = $role;
    // $user_email = $email;
    // $user_password = $password;
// ==================== FOR CHANGE PASSWORD=============================

                $sql5 = "SELECT * FROM users";
                //Execute
                $res5 = mysqli_query($conn, $sql5);
                if (!$conn) {
                    die( "Unable to select database");
                }

                $count = mysqli_num_rows($res5);

                if($count>0){

                    // We do not have data
                    while($row=mysqli_fetch_assoc($res5)){
                        $id = $row['id'];
                        $email = $row['email'];
                        $password = $row['password'];
                      
            

                    }
                }

?>


