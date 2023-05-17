<?php
require '../vendor/autoload.php';

/** TODO
* Use PDO connection to connect to MySQL Database
*
* Update configuration variables used to connect to MySQL database
*
* NOTE: Do not create new connection within each endpoint. Use connection variable which was set using Flight
*
* NOTE: Do not add new files to the project
*
* NOTE: table that contains investors is named investors and table with tranfers is named transfers
*
* NOTE: If you are having issues with non working routes in flightPHP you have to enable MOD_REWRITE on Apache
*/
$servername = "localhost";
$username = "root";
$password = "";
$schema = "midterm-2022";

$conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
Flight::set("connection", $conn);

Flight::route('GET /transfers/report_by_day', function(){
    /** TODO
    * write a query that will list total number of transactions and total amount of transfers
    * per day.
    *
    * List should be sorted by the total amount and number of transactions having the highest values on the top.
    *
    * This endpoint should return output in JSON format
    */

    $stmt = Flight::get('connection')->prepare("SELECT transfers.created_at, COUNT(transfers.id) AS total_number_transactions, SUM(amount) AS total_amount_transfers 
    FROM transfers JOIN investors ON investors.id=transfers.sender_id 
    Group by created_at
    Order by total_number_transactions desc, total_amount_transfers desc");
    $stmt->execute();
   Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));

});

Flight::route('GET /transfers/report_by_investors', function(){
    /** TODO
    * write a query that will list total number of transactions and total amount of transfers
    * per investor.
    *
    * List should be sorted by the total transferred amount and number of transactions having the highest values on the top.
    *
    * This endpoint should return output in JSON format
    */

    $stmt = Flight::get('connection')->prepare("SELECT investors.first_name,investors.last_name, COUNT(transfers.id) AS total_number_transactions, SUM(transfers.amount) AS total_amount_transfers  FROM `investors` JOIN transfers ON investors.id=transfers.sender_id
    group by transfers.sender_id Order by total_number_transactions desc, total_amount_transfers desc");
    $stmt->execute();
   Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('GET /transfers/report_by_day_and_investors', function(){
    /** TODO
    * write a query that will list total number of transactions and total amount of transfers
    * per investor for each day.
    *
    * List should be sorted by the total transferred amount and number of transactions having the highest values on the top.
    *
    * This endpoint should return output in JSON format
    */
    $stmt = Flight::get('connection')->prepare("SELECT investors.first_name,investors.last_name, transfers.created_at, COUNT(transfers.id) AS total_number_transactions, SUM(transfers.amount) AS total_amount_transfers  FROM `investors` JOIN transfers ON investors.id=transfers.sender_id
    group by transfers.sender_id, transfers.created_at Order by total_number_transactions desc, total_amount_transfers desc");
    $stmt->execute();
   Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::start();
?>
