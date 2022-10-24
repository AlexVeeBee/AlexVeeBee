<?php
class login {
    public static function isloggedin() {
        if (isset($_COOKIE['LHID'])) {
            if (DB::query('SELECT user_id FROM login_token WHERE token=:token' , array(':token'=>sha1($_COOKIE['LHID'])))) {
                $userid = DB::query('SELECT user_id FROM login_token WHERE token=:token' , array(':token'=>sha1($_COOKIE['LHID'])))[0]['user_id'];
                if (isset($_COOKIE['LHID_'])) {
                    return $userid;
                } else {
                    $cstrong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                    DB::query('INSERT INTO login_token VALUES (\'\' ,:token ,:user_id)' , array(':token'=>sha1($token), ':user_id'=>$userid) );
                    DB::query('DELETE FROM login_token WHERE token=:token' , array(':token'=>sha1($_COOKIE['LHID'])));
                    
                    setcookie('LHID', $token, time() + 60 * 60 *24 * 7 , '/' , null , true , true );
                    setcookie('LHID_' , '1' , time() + 60 * 60 *24 * 4 , '/' , null , true , true );    
                    return $userid;
                    header('location: ');
                }
            }
        }
        return false;
    }
    public static function Loggedinusername($type = '') {
        $useridName = DB::query('SELECT username FROM users WHERE id='.login::isloggedin());
        $usname = '';
        $usnameText = '';
        foreach ($useridName as $usnameText) {
            if ($type == 'full_name') {
                if (DB::query('SELECT username FROM users WHERE banned=0 AND id='.login::isloggedin())) {
                    $usnameText = htmlspecialchars($usnameText['username']);
                } else {
                    $usnameText = 'Banned '.htmlspecialchars($usnameText['username']);
                }
            } else if ($type == 'just_name') {
                $usnameText = htmlspecialchars($usnameText['username']);
            } else {
                $usnameText = "Invalid type Value";
            }
        }
        return $usnameText ;
    }
    public static function getuseridbyname($username = '') {
        $getuserid = DB::query('SELECT id FROM users WHERE username=:getuser' , array(":getuser" => $username)) [0]['id'];
        if ($getuserid) {
            return $getuserid;
        } else {
            return "Invalid username";
        }
    }
    public static function profileimage($userid) {
        $profileimg = DB::query('SELECT profilepicture FROM users WHERE id='.$userid);
        $previewimageURL = '';
        foreach ($profileimg as $imageload) {
            $previewimageURL = $imageload['profilepicture'];
        }
        return $previewimageURL;
    }
    public static function profilebanner($userid) {
        $profileimg = DB::query('SELECT profilebanner FROM users WHERE id='.$userid);
        $previewimageURL = '';
        foreach ($profileimg as $imageload) {
            $previewimageURL = $imageload['profilebanner'];
        }
        return $previewimageURL;
    }
    public static function profilename($userid) {   
        $profileimg = DB::query('SELECT username FROM users WHERE id='.$userid);
        $previewimageURL = '';
        foreach ($profileimg as $imageload) {
            $previewimageURL = $imageload['username'];
        }
        return $previewimageURL;
    }
    public static function profileDescription($userid) {
        $profileimg = DB::query('SELECT `description` FROM users WHERE id='.$userid);
        $description = '';
        foreach ($profileimg as $description) {
            $description = $description['description'];
        }
        return $description;
    }
    public static function checkloggedinban($Currentuserid) {
        $banneduser = false;
        if (DB::query('SELECT username FROM users WHERE banned=0 AND id='.$Currentuserid)) {
            $banneduser = false;
        } else {
            $banneduser = true;
        }
        return $banneduser ;
    }
}
?>