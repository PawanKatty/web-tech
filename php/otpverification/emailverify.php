<?php
//dummy database
$link = mysqli_connect("localhost","root","","consultancy");
//for checking whether connection is establised or not.
if(mysqli_connect_errno()){
    die ("Database conncection failed");
 }
else{
    session_start();//for sharing data
    $error = "";
    echo $error;
    $otpEmail = $_GET['otpEmail'];
    date_default_timezone_set("Asia/Kolkata");
if(array_key_exists("otpSend",$_GET)){
    if(!$otpEmail)
    {
        $error .= "<p>Email is required.<p/>";
    }
    else{
        //check email in database
        echo $otpEmail;
        $query = "SELECT `email`,`id` FROM `users` WHERE email='".mysqli_real_escape_string($link,$otpEmail)."' LIMIT 1 ";
        $result = mysqli_query($link,$query);
        $row = mysqli_fetch_array($result);
        $uid = $row["id"];
        $_SESSION['userid'] = $uid;
        if(mysqli_num_rows($result) > 0){
        // generate OTP
		$otp = rand(100000,999999);
        $currentDate = date("Y-m-d H:i:s");
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
                header("Location: otpverify.php");
            }
            else{
                echo "failed to send otp";
            }
        }
        else{
            $error .= "<p>unable to send otp.<p/>";
        }
		 
        exit();
        }
        else{
            $error .= "<p>Email does not exists.<p/>";
        }
        
    }

}
echo $error;
}

//function to send otp to database.
//function otpToDatabase($userId, $headerAddress){
//    
//    $currentDate = date("Y-m-d H:i:s");
//    $otp = rand(100000,999999);
//    // Send OTP in database
//    $query = "INSERT INTO `passcode`(`uid`,`otp`,`start`) VALUES ('".mysqli_real_escape_string($link,$userId)."',
//    '".mysqli_real_escape_string($link,$otp)."',
//    '".mysqli_real_escape_string($link,$currentDate)."')";
//    if(mysqli_query($link,$query)){
//        header("Location: ".$headerAddress);
//    }
//    else{
//        $error .= "<p>unable to send otp.<p/>";
//    }
//
//}



?>


<form action="" method="get">
    <p>Enter email to send otp on it</p>
    <input type="email" name="otpEmail">
    <button name="otpSend">Send</button>
</form>