<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 3:28 PM.
 */


require('../includes/db.php');

if (!isset($_GET['q'])) {
    die("Error");
}

$searchText = mysqli_real_escape_string($con, $_GET['q']);
$searchText = strtolower($searchText);
$searchText = preg_replace('/[^a-z0-9 -]+/', '', $searchText);
$searchText = str_replace(' ', '-', $searchText);
$searchText = trim($searchText, '-');
$searchText = explode("-", $searchText);
$searchTextWordCount = count($searchText);
$searchQuery = "";
if ($searchTextWordCount < 1) {
    echo "<div class='alert alert-dismissible alert-warning'>Invalid Search Query</div>";
} else {
    $searchQuery = "SELECT * FROM user WHERE type=1 AND ( ";
    foreach ($searchText as $searchWord) {
        $searchQuery .= "LOWER(fname) LIKE ('%$searchWord%') OR LOWER(lname) LIKE('%$searchWord%') OR LOWER(email) LIKE('%$searchWord%')";
        $searchTextWordCount--;
        if ($searchTextWordCount > 0) {
            $searchQuery .= " OR ";
        }
    }
    $searchQuery .= ")";
    $searchQueryResult = mysqli_query($con, $searchQuery) or die($con);
    //Uncomment the line below for DEBUG Purposes
    //echo $searchQuery;
}

//lookup all links from the xml file if length of q>0
$hint = "";

while ($searchData = mysqli_fetch_array($searchQueryResult)) {
    $email = $searchData['email'];
    $name = $searchData['fname'] . " " . $searchData['lname'];
    $hint .= "<a href='#' class='list-group-item list-group-item-action' onclick='addThisEmail(\"$email\")'>". $name . "</a><br>";
}
// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint == "") {
    $response = "No matches found";
} else {
    $response = $hint;
}

//output the response
echo $response;

?>


