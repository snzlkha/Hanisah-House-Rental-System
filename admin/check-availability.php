<?php
    require_once("../includes/dbconn.php");
    if(!empty($_POST["emailid"])) {
        $email= $_POST["emailid"];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)===false) {

            echo "error : You did not enter a valid email.";
        } else {
            $result ="SELECT count(*) FROM userRegistration WHERE email=?";
            $stmt = $mysqli->prepare($result);
            $stmt->bind_param('s',$email);
            $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count>0){
    echo "<span style='color:red'> Email already exist! Try using new one.</span>";
        } else {
            echo "<span style='color:green'> Email available for registration!!</span>";
        }
     }
    }

    if(!empty($_POST["oldpassword"])) {
    $pass=$_POST["oldpassword"];
    $pass=md5($pass);
    $result ="SELECT password FROM userregistration WHERE password=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s',$pass);
    $stmt->execute();
    $stmt -> bind_result($result);
    $stmt -> fetch();
    $opass=$result;
    if($opass==$pass) 
    echo "<span style='color:green'> Password  matched.</span>";
    else echo "<span style='color:red'>Password doesnot match!</span>";
    }


  if (!empty($_POST["roomno"])) {
    // Check the availability of each seat in the specified room
    $roomno = $_POST["roomno"];
    $totalSeats = 4; // Assuming there are 4 seats in each room
    $availableSeats = $totalSeats;
    $unavailableSeats = [];

    for ($i = 1; $i <= $totalSeats; $i++) {
        $result = "SELECT count(*) FROM registration WHERE roomno=? AND seater=?";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('is', $roomno, $i);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<span style='color:red'>Seat $i is not available.</span><br>";
            $availableSeats--;
            $unavailableSeats[] = $i;
        }
    }

    if ($availableSeats == 0) {
        echo "<span style='color:red'>All seats are already full for this room.</span>";
    } else {
        echo "<span style='color:green'>Seats ";
        $availableSeatCount = $totalSeats - count($unavailableSeats);
        $index = 0;
        for ($i = 1; $i <= $totalSeats; $i++) {
            if (!in_array($i, $unavailableSeats)) {
                echo $i;
                $index++;
                if ($index < $availableSeatCount) {
                    if ($index == $availableSeatCount - 1) {
                        echo " and ";
                    } else {
                        echo ", ";
                    }
                }
            }
        }
        echo " are available for registration!</span>";
    }
}
?>