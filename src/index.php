<?php
    
    require("adm\common.php");

    $q = '0';

    if(isset($_GET['q'])) {
        $q = $_GET['q'];
    }    

    $submitted_username = '';

    $login_failed = false;

    if(!empty($_POST)) {

        $query = "SELECT id, username, password, salt, email from users WHERE username = :username";
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 

        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) { 
            die("Failed to run query."); 
        } 

        $login_ok = false;

        $row = $stmt->fetch();
        if($row) {

            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 500; $round++) { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            } 

            if($check_password === $row['password']) {
                $login_ok = true;
            }
        }

        if($login_ok) {
            unset($row['salt']);
            unset($row['password']);
            
            $_SESSION['user'] = $row;

            header("Location: home.php");
            die("Redirecting to: notes.php");
        } else {
            $login_failed = true;
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        }
    }
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example that shows off a responsive product landing page.">

    <title>Never Alone &ndash; </title>


    <!-- Baixar as dependecias e colocar no servidor depois -->
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
    <link rel="stylesheet" href="css/marketing.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

</head>

<body>

<div class="header">
    <div class="home-menu pure-menu pure-menu-open pure-menu-horizontal pure-menu-fixed">
        <a class="pure-menu-heading" href="">Your Site</a>

        <ul>
            <li class="pure-menu-selected"><a href="#">Home</a></li>
            <li><a href="#">Tour</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </div>
</div>

<div class="splash-container">
    <div class="splash">
        <h1 class="splash-head">Never Alone</h1>
        <p class="splash-subhead">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
        </p>
        <p>
            <a href="http://purecss.io" class="pure-button pure-button-primary">Get Started</a>
        </p>
    </div>
</div>

<div class="content-wrapper">
    

    <div class="ribbon l-box-lrg pure-g">
        <div class="l-box-lrg is-center pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
            <img class="pure-img-responsive" alt="File Icons" width="300" src="img/common/file-icons.png">
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">

            <h2 class="content-head content-head-ribbon">Laboris nisi ut aliquip.</h2>

            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor.
            </p>
        </div>
    </div>

    <div class="content">
        <h2 class="content-head is-center">Dolore magna aliqua. Uis aute irure.</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-2-5">
                <form class="pure-form pure-form-stacked" method="post">
                    <fieldset>

                        <label for="name">Username</label>
                        <input id="name" name="username" type="text" placeholder="Username">

                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Password">

                        <button type="submit" class="pure-button">Login</button>
                    </fieldset>
                </form>
            </div>

            <div class="l-box-lrg pure-u-1 pure-u-md-3-5">
                <h4>Contact Us</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat.
                </p>

                <h4>More Information</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>
        </div>

    </div>

    <div class="footer l-box is-center">
        Made with love by the YUI Team.
    </div>

</div>

</script>
</body>
</html>
