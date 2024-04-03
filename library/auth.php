<?php
    session_start();
    function isLogin()
    {
        if(isset($_SESSION['valid']) && $_SESSION['valid']  == true)
            return true;
        return false;
    }
    function validUser($username, $password)
    {
        $result = dbSelect("tbl_user", "*", "username = '$username' and password = md5('". $password. "') and active ='1' ");
        $num  = mysqli_num_rows($result);
        if($num == 1)
        {
            $row = mysqli_fetch_array($result);
            $user_id = $row['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['valid'] = true;
            $today = date('Y-m-d H:i:s') . "";
            $data = ["lastlogin"=> "$today"];
            dbUpdate("tbl_user", $data, "user_id = $user_id");
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function logOut()
    {
        session_destroy();
    }