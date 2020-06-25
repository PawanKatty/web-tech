<?php
//dummy database
session_start();

$uid = $_SESSION['userid'];
$error = "";
echo $error;
date_default_timezone_set("Asia/Kolkata");

$link = mysqli_connect("localhost","root","","consultancy");
//for checking whether connection is establised or not.
if(mysqli_connect_errno()){
    die ("Database conncection failed");
 }
else{
    //deletes otp afterr 15 min
    $delete_otp = mysqli_query($link,"DELETE FROM passcode WHERE start < ('" . date("Y-m-d H:i:s"). "' - INTERVAL 15 MINUTE)");
    
    $otp = $_POST['otp'];
if(array_key_exists("submitOtp",$_POST)){
    if(!$_POST['otp'])
    {
        $error .= "<p>Enter otp.<p/>";
    }
    else{
        //match otp
        $query = "SELECT `otp` FROM `passcode` WHERE uid='".mysqli_real_escape_string($link,$uid)."' ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($link,$query);
        $row = mysqli_fetch_array($result);
        $databaseOtp = $row["otp"];
//        echo "<p>".$databaseOtp."</p>";
        
        if($otp == $databaseOtp){
            $query = "DELETE FROM `passcode` WHERE uid='".mysqli_real_escape_string($link,$uid)."'";
            $result = mysqli_query($link,$query);
            header("Location: changepass.php");
        }
        else{
            $error .= "<p>Invalid OTP.<p/>";
        }
        
        
    }

}
else if(array_key_exists("resendOtp",$_POST)){
    //resend otp
    $otp = rand(100000,999999);
    $currentDate = date("Y-m-d H:i:s");
    
    //get email id 
    $queryy = "SELECT `email` FROM `users` WHERE id='".mysqli_real_escape_string($link,$uid)."' LIMIT 1";
    $resultt = mysqli_query($link,$queryy);
    $roww = mysqli_fetch_array($resultt);
    $otpEmail = $roww["email"];
;
    
    // Send OTP in database
    $query = "INSERT INTO `passcode`(`uid`,`otp`,`start`) VALUES ('".mysqli_real_escape_string($link,$uid)."',
    '".mysqli_real_escape_string($link,$otp)."',
    '".mysqli_real_escape_string($link,$currentDate)."')";
    if(mysqli_query($link,$query)){
        //send otp mail
        $message = 'Your otp for BADAKAAM = '.$otp;
        require('../mail sender/sendmail.php');
//            echo sendMail($otpEmail,'OTP',$message);
        if(sendMail($otpEmail,'OTP '.$otp ,$message)==1){
            echo "otp resended to email";
        }
        else{
            echo "failed to send otp";
        }        
    }
    else{
        $error .= "<p>unable to send otp.<p/>";
    }
}
echo $error;
}


?>
   
   
   
   
   
<form action="" method="post">
    <p>otp has been sent to your Email adddress</p>
    <input type="text" name="otp">
    <button name="resendOtp">Re-send</button>
    <button name="submitOtp" type="submit">Submit</button>
</form>