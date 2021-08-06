<?php

$message = "";

function replace_first_str($search_str, $replacement_str, $src_str){
    return (false !== ($pos = strpos($src_str, $search_str))) ? substr_replace($src_str, $replacement_str, $pos, strlen($search_str)) : $src_str;
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function Validate(){
    $phone_number = $_POST['phone_number'];
    $media = $_POST['media'];

    //remove space
    $phone_number = str_replace(' ', '', $phone_number);

    //remove -
    $phone_number = str_replace('-', '', $phone_number);

    //remove +
    $phone_number = str_replace('+', '', $phone_number);

    //validate phone number
    if(!is_numeric($phone_number)){
        $GLOBALS['message'] = "Phone number only can be numeric!\nExample: 6281234567890";
        return;
    }

    //replace leading 0 with 62
    if(startsWith($phone_number, '0')){
        $phone_number = replace_first_str('0', '62', $phone_number);
    }

    //validate media (mitigate intercept attack by modifying POST parameter)
    $media_list = array('sms', 'whatsapp', 'line', 'telegram');
    if (!in_array($media, $media_list)) {
        $GLOBALS['message'] = "Invalid media type. Please choose from the UI!";
        return;
    }

    date_default_timezone_set('Asia/Jakarta');
    $time = date('Y-m-d H:i:s');

    //database
    include 'db_con.php';
    $con = OpenCon();

    $stmt = $con->prepare("INSERT INTO nohp (nohp, media, recorded_on) VALUES (?, ?, ?);");
	$stmt -> bind_param("sss", $phone_number, $media, $time);
	$result = $stmt -> execute();

    if($result){
        $GLOBALS['message'] = "Submitted! Thank you :)";
    }else{
        $GLOBALS['message'] = $stmt -> error;
    }

    CloseCon($con);
}

//check if form was submitted
if(isset($_POST['submit'])){
    Validate();
}

?>

<!DOCTYPE html>
<html style="background: #1e1e1e;color: rgb(255,255,255);">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Tronit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Coda">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Coda+Caption:800">
    <link rel="stylesheet" href="assets/css/styles.min.css">
</head>

<body style="background: #1e1e1e;color: rgb(255,255,255);">
    <div>
        <div class="header-dark">
            <nav class="navbar navbar-dark navbar-expand-lg navigation-clean-search">
                <div class="container"><a class="navbar-brand" style="font-family: 'Coda Caption', sans-serif;" href="#">Tronit Project</a></div>
            </nav>
            <div class="container hero">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <h1 class="text-center" style="font-family: 'Coda Caption', sans-serif;">Tired of Spammers?</h1>
                        <h2 class="text-center" style="color: rgb(255,255,255);font-family: Coda, cursive;">Submit their phone number here!<br>Fight them with data!</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="justify-content-center align-items-center align-content-center col-md-6 mx-auto" style="margin: 60px;">
        <form method="POST" action="">
            <p style="font-family: Coda, cursive;font-size: 24px;margin-bottom: -5px;color: rgb(255,255,255);"><br>Phone number:</p>
            <input class="bg-dark form-control" type="text" required="" name="phone_number" placeholder="6281234567890" style="color: rgb(255,255,255);">
            <p style="font-family: Coda, cursive;font-size: 24px;margin-bottom: -5px;margin-top: 16px;color: rgb(255,255,255);">Media:</p>
            <select class="bg-dark form-control" name="media" style="color: rgb(255,255,255);">
                <optgroup label="Select media...">
                    <option value="sms">SMS</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="line">LINE</option>
                    <option value="telegram">Telegram</option>
                </optgroup>
            </select>
            <button class="btn btn-primary bg-dark" type="submit" name="submit" style="margin-top: 12px;width: 112px;">Submit</button>
        </form>
        <?php
            if(isset($_POST['submit'])){
                $s = $GLOBALS['message'];
                echo "<p style=\"font-family: Coda, cursive;font-size: 24px;color: rgb(255,255,255);\"><br>$s</p>";    
            }
        ?>
    </div>
    <div class="footer-dark">
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 item text">
                        <h3>Who cares about their data privacy?</h3>
                        <p class="text-justify">They don't care at all and keep spamming us!<br><br></p>
                        <h3>How about my data?</h3>
                        <p class="text-justify">Zerrium cares about your data, not the spammers data. So this website <strong>never</strong> collect any info (IP Address, Location, Microphone, Camera, etc) on your device and this website <strong>doesn't</strong> use cookies.<br></p>
                    </div>
                    <div class="col-md-6 item text">
                        <h3>Where do this data go?</h3>
                        <p class="text-justify">To Zerrium's database. When we have collected many phone numbers of spammers, he will submit and report all of these to <a class="bg-dark" href="https://twitter.com/aduanBRTI" style="color: rgb(139,195,255);" target="_blank">Aduan BRTI</a>.
                            If we don't get a good response from it, Zerrium might consider selling the data to spammers so they take their own medicine :D<br></p>
                    </div>
                </div>
                <div class="text-dark social-icons">
                    <a href="https://github.com/zerrium/tronit" target="_blank"><img src="assets/img/github.png"></img></a>
                </div>
                <p class="copyright">Zerrium © 2021</p>
            </div>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>