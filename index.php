<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical TEST</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php


//GETTING DATA
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://g3xc1v5ana.execute-api.eu-west-1.amazonaws.com/production/metrics/day?ps=adsjsjrdro20g2s");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

$response = json_decode($response, true); //because of true, it's in an array


//TODAY
$today = date("d")."/".date("m")."/".date("Y");


// TODAY'S RADIOS CONNECTED
function todayFilter($var){
    $today = date("Y")."-".date("m")."-".date("d");
    if ($var["ts"] == $today){
        return $var;
    }
}
$connectedToday = count(array_filter($response, "todayFilter"));


//OLDEST DATA
$oldest = new DateTime($response[0]["ts"]);
for ($i = 0; $i < count($response); $i++){
    $dataDate = new DateTime($response[$i]["ts"]);
    if ($dataDate < $oldest){
        $oldest = $dataDate;
    }
}


//MAXIMUM LISTENERS ON SELECTED DATE
if (isset($_GET["selectDate"])){
    $date = $_GET["selectDate"];
    $maxListenersShown = "Not data for that date.";
    for ($i = 0; $i < count($response); $i++){
        $equal = strcmp($response[$i]["ts"], $date);
        // echo "<br>".$response[$i]["ts"]."----".$date."equal =".var_dump($equal);
        if ($equal === 0){
            $maxListeners = $response[$i]["max_listeners"];
            $maxListenersShown = "Max. Listeners: ".$maxListeners;
        }
    }
    $dateShown = "At: ".substr($date, 8, 2)."/".substr($date, 5, 2)."/".substr($date, 0, 4);
} else {
    $dateShown = "";
    $maxListenersShown = "Select a date";
}


// echo "<br><br>";
// print_r($response);

?>
    <div class="window">
        <div class="header">
            <h1 class="title">Radio Comunication Sistem</h1>
            <p>Today: <?php echo $today ?></p>
        </div>
        <div class="contentBody">
            <table class ="table">
                <thead class="thead">
                    <tr>
                        <th>Radios connected<br> today</th>
                        <th>Total data</th>
                        <th>Oldest data</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <tr>
                        <td><?php echo $connectedToday ?></td>
                        <td><?php echo count($response) ?></td>
                        <td><?php echo date_format($oldest, "d/m/Y")?></td>
                    </tr>
                <tbody>
            </table>
            <div class="maxListeners">
                <div class="maxHeader">
                    <h3>Maximum listeners on<br> selected date</h3>
                    <form class="" action="" method="get">
                        <input type="date" name="selectDate">
                        <button type="submit" name="button">Search</button>
                    </form>
                </div>
                <div class="maxBody">
                    <p><?php echo $dateShown ?></p>
                    <p><?php echo $maxListenersShown?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


