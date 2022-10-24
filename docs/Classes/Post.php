<!--

CLASS = POST

-->
<?php 
class Post{
    public static function createpost($postbody , $loggedinuserid , $profileuserid) {
        $IncorrectLength = false;
        if(strlen($postbody) > 240 || strlen($postbody) < 1) {
            echo '<h1>Incorrect Length</h1><style>
            body{
                text-align: center;
            }</style>' ;
            $IncorrectLength = true;
        }
        if ($IncorrectLength == false) {
            $topics = self::GetTopics($postbody);
            if ($loggedinuserid == $profileuserid) {
                if(count(notify::createNotify($postbody)) != 0) {
                    foreach (notify::createNotify($postbody) as $key => $n) {
                        $s = $loggedinuserid;
                        $r = DB::query('SELECT id FROM users WHERE username=:username' , array(':username'=>$key))[0]['id'];
                        if ($r != 0) {
                            DB::query('INSERT INTO notifications VALUE (\'\', :type , :receiver , :sender, :extra)',array(':type'=>$n['type'] , ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n['extra']));
                        }
                    }
                }

                DB::query('INSERT INTO posts VALUE (\'\', :postbody , NOW() , :userid, 0 , \'\' , :topics)' , array(':postbody'=>$postbody, ':userid' => $profileuserid , ':topics'=>$topics));
            } else {
                die("<h1>Incorrect User</h1><style>
                body{
                    text-align: center;
                }</style>
                ");
            }
        }
    }
    public static function createImagePost($postbody , $loggedinuserid , $profileuserid) {
        if(strlen($postbody) > 240 ) {
            die('Incorrect Length
            <style>
            body{
                text-align: center;
            }
            </style>');
        }

        $topics = self::GetTopics($postbody);

        if ($loggedinuserid == $profileuserid) {
            if(count(notify::createNotify($postbody)) != 0) {
                foreach (notify::createNotify($postbody) as $key => $n) {
                    $s = $loggedinuserid;
                    $r = DB::query('SELECT id FROM users WHERE username=:username' , array(':username'=>$key))[0]['id'];
                    if ($r != 0) {
                        DB::query('INSERT INTO notifications VALUE (\'\', :type , :receiver , :sender, :extra)',array(':type'=>$n['type'] , ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n['extra']));
                    }
                }
            }
        }
        if ($loggedinuserid == $profileuserid) {
            DB::query('INSERT INTO posts VALUE (\'\', :postbody , NOW() , :userid, 0 , \'\' , \'\')' , array(':postbody'=>$postbody, ':userid' => $profileuserid ));
            $postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY id DESC LIMIT 1' , array(':userid'=>$loggedinuserid))[0]['id'];
            return $postid;

        } else {
            die("<h1>Incorrect User</h1>
            <style>
            body{
                text-align: center;
            }
            </style>
            ");
        }
    }
    public static function likePost($postid , $likerid) {
        if(!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid' , array(':postid'=>$postid, ':userid'=>$likerid))) {
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid' , array(':postid'=>$postid));
            DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)' , array(':postid'=>$postid,':userid'=>$likerid));
            #notify::createNotify("" , $postid);
        } else {
            DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid' , array(':postid'=>$postid));
            DB::query('DELETE FROM post_likes WHERE post_id=:postid and user_id=:userid' , array(':postid'=>$postid , ':userid'=>$likerid));
        }
    }

    public static function GetTopics($text) {
            $text = explode(" " , $text);
        $topics = "";

        foreach ($text as $word) {
            if (substr($word , 0 , 1) == '#') {
                $topics .= substr($word, 1).",";
            }
        }
        return $topics;
    }
    public static function link_add($text,$id=0,$topost=false,$username="",$postid=0) {
        $text = explode(" " , $text);
        $newstring = "";

        foreach ($text as $word) {
            if (substr($word , 0 , 1) == '@') {
                $newstring .= " <a href='profile.php?username=".substr($word , 1)."'>".htmlspecialchars($word)."</a> "; if($topost == true) {$newstring .= "<a class='ToPost' href='profile.php?username=".$username."&userpostid=".$postid."' ><h1 class='post-text'>".htmlspecialchars($word)." "."</h1>";} 
            } else if (substr($word , 0 , 1) == '#') {
                $newstring .= " <a href='topics.php?topic=".substr($word , 1)."'>".htmlspecialchars($word)."</a> "; if($topost == true) {$newstring .= "<a class='ToPost' href='profile.php?username=".$username."&userpostid=".$postid."' ><h1 class='post-text'>".htmlspecialchars($word)." "."</h1>";}
            } else {
                $newstring .= htmlspecialchars($word)." "; 
            }
        }

        return $newstring;
    }

    public static function displayPosts($userid, $username , $loggedinuserid , $showbuttons = true) {
        $dbposts = DB::query('SELECT posts.* FROM posts  WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
        $posts = "";
        foreach($dbposts as $p) {
            if (login::isloggedin()) {
                $posts .= "
                    <div class='user-post'>
                    <div class='User-img-name'>
                    <img class='post-img' src='".login::profileimage($p['user_id'])."'>
                    <div>
                        <h2 class='post-name'><a href='profile.php?username=$username'>".$username."</a></h2>
                        <h4 class='Post-created' style='position: absolute; right: 0px;  top: 0px;
                        '>".$p['pointed_at']."</h4>
                    </div>
                    </div>
                    <a class='ToPost' href='profile.php?username=".$username."&userpostid=  ".$p['id']."' ><h1 class='post-text'>".self::link_add($p['body'],$p['id'])."</a></h1>
                    </a><br><a class='ToPost' href='profile.php?username=".$username."&userpostid=  ".$p['id']."' ><img class='imagepreview' srcset='".$p['postimg']."'></a>
                    <br><h4>".$p['pointed_at']."</h4>
                    "; if($showbuttons == true) { $posts .="
                    <form action='profile.php?username=$username&postid=".$p['id']."' method='POST'>"; 
                    
                    if (!DB::query('SELECT post_id,users.id FROM post_likes , users WHERE post_id=:postid AND user_id=:userid' , array(':postid'=>$p['id'] , ':userid'=>$loggedinuserid))) {
                        $posts .="
                        <input class='postlike liked' type='submit' name='like' value='like'>
                        <span>".$p['likes']." Likes</span>
                        "; 
                    } else {
                        $posts .="
                        <input class='postlike unliked' type='submit' name='unlike' value='unlike'>
                        <span>".$p['likes']." Likes</span>";
                    }
                    if($userid == $loggedinuserid){
                        $posts .= '<input type="submit" value="Delete Post" name="deletePost" class="ButtonStyle">';
                    }
                    $posts .= "
                    </form>
                    <a href='profile.php?username=".$username."&userpostid=  ".$p['id']."' '><button class='ButtonStyle'>View Post</button></a>
                    "; } $posts .= "
                    </div>
                ";
            } else {
                $posts .= "
                
                <div class='user-post'>
                <div class='User-img-name'>
                    <img class='post-img' src='".login::profileimage($p['user_id'])."'>
                    <div>
                        <h2 class='post-name'>$username</h2>
                    </div>
                </div>
                <h1 class='post-text'>".self::link_add($p['body'])."</h1><br>
                <img class='imagepreview'src='".$p['postimg']."'>
                <h4>".$p['pointed_at']."</h4>
                <span>".$p['likes']." Likes</span>
                </div>
                ";
            }
        }
        return $posts;
    }
    public static function displayPosts1post($userid, $username , $userpostid , $loggedinuserid , $showcomments = false) {
        $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid AND id=:userpostid ', array(':userid'=>$userid , ':userpostid'=>$userpostid));
        $posts = "";
        foreach($dbposts as $p) {
            if (login::isloggedin()) {
                $posts .= "
                <div class='user-post'>
                    <div class='User-img-name'>
                    <img class='post-img' src='".login::profileimage($p['user_id'])."'>
                    <div>
                        <h2 class='post-name'><a href='profile.php?username=$username'>".$username."</a></h2>
                        <h4 class='Post-created' style='position: absolute; right: 0px;  top: 0px;
                        '>".$p['pointed_at']."</h4>
                    </div>
                </div>
                
                <h2 class='post-text'>".self::link_add($p['body'])."</h2>
                    <br><img class='imagepreview' srcset='".$p['postimg']."'
                    <br>
                    <form action='profile.php?username=$username&postid=".$p['id']."' method='POST'>";
                        if (!DB::query('SELECT post_id,users.id FROM post_likes , users WHERE post_id=:postid AND user_id=:userid' , array(':postid'=>$p['id'] , ':userid'=>$loggedinuserid))) {
                            $posts .="
                            <input class='postlike liked' type='submit' name='like' value='like'>
                            <span>".$p['likes']." Likes</span>
                            "; 
                        } else {
                            $posts .="
                            <input class='postlike unliked' type='submit' name='unlike' value='unlike'>
                            <span>".$p['likes']." Likes</span>";
                        }                        
                        if($userid == $loggedinuserid){
                            $posts .= '<input type="submit" value="Delete Post" name="deletePost" class="ButtonStyle">';
                        }
                        $posts .= "
                    </form>";
                    //</div>
                    //<div class='postcomment'>";
                    //if (DB::query('SELECT banned FROM users WHERE id=:userid AND banned=0' , array(':userid'=>$userid))) {
                    //    $posts .= "<form action='profile.php?username='".$_GET['username']."&userpostid=".$_GET['userpostid']."&commentonpostid=".$_GET['userpostid']."' method='POST'>
                    //        <textarea name='CommentBody' class='FormField-Input-TextBox Sizeing TextBox-Posting' maxlength='160' name='postbody' rows='3' cols='20'></textarea>
                    //        <input name='comment' class='' type='submit' value='Comment'>
                    //    </form>''; 
                    //    <h2>Comments</h2>";
                    //    $comments = comment::displayComments($_GET['userpostid'] , true);
                    //} $posts .= "
                    //</div>
                    //";
            } else {
                $posts .= "<h1 class='post-text'>".self::link_add($p['body'])."</h1><br>
                <img class='imagepreview'src='".$p['postimg']."'>
                <h4>".$p['pointed_at']."</h4>
                <span>".$p['likes']." Likes</span>
                ";
            }
        }
        if ($dbposts) {
            return $posts;
        } else {
            return '<div class="user-post"><h1>'.$_GET['username'].'´s post does not exist or '.$_GET['username'].' has not created that post.</h1></div>';
        }
    }
}
?>
