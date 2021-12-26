<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Ticket</title>
</head>
<style>
  /*За форма*/
  body {
    font-family: Arial, Helvetica, sans-serif;
  }

  * {
    box-sizing: border-box
  }

  /* Full-width input fields */
  input[type=text],
  input[type=password] {
    width: 100%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #f1f1f1;
  }

  input[type=text]:focus,
  input[type=password]:focus {
    background-color: #ddd;
    outline: none;
  }

  hr {
    border: 1px solid #f1f1f1;
    margin-bottom: 25px;
  }

  /* Set a style for all buttons */
  button {
    background-color: #04AA6D;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    opacity: 0.9;
  }

  button:hover {
    opacity: 1;
  }

  /* Extra styles for the cancel button */
  .cancelbtn {
    padding: 14px 20px;
    background-color: #f44336;
  }

  /* Float cancel and signup buttons and add an equal width */
  .cancelbtn,
  .signupbtn {
    float: left;
    width: 50%;
  }

  /* Add padding to container elements */
  .container {
    padding: 16px;
  }

  /* Clear floats */
  .clearfix::after {
    content: "";
    clear: both;
    display: table;
  }

  /* Change styles for cancel button and signup button on extra small screens */
  @media screen and (max-width: 300px) {

    .cancelbtn,
    .signupbtn {
      width: 100%;
    }
  }

  /*For navigation bar*/
  .topnav {
    overflow: hidden;
    background-color: #333;
  }

  .topnav a {
    float: left;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
  }

  .topnav a:hover {
    background-color: #ddd;
    color: black;
  }

  .topnav a.active {
    background-color: #04AA6D;
    color: white;
  }

  /* За снимки */
  .content {
    width: 50%;
    padding: 20px;
    overflow: hidden;
  }

  .content img {
    margin-right: 15px;
    float: left;
  }

  .content p {
    margin-left: 15px;
    display: block;
    margin: 2px 0 0 0;
  }

  /*за бутон добави ticket */
  a {
    text-decoration: none;
    display: inline-block;
    padding: 8px 16px;
  }

  .next {
    margin-left: 15px;
    background-color: #04AA6D;
    color: white;
  }
</style>

<body>
  <?php
require_once('DbHelper.php');
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_POST['viewTicketBtn'])) {
  $_SESSION["ticket_id"] = $_POST["viewTicketBtn"];
  header("location: viewTicket.php");
}
?>

  <div class="topnav">
    <a class="active" href="index.php">Начална страница</a>
    <?php
      if(strpos($_SESSION["user_role"], 'програмист') !== false) {
      ?>
      <a href="ticket.php">Добавяне на Ticket</a>
      <?php
}
?>
    <a href="logout.php">Изход</a>
    <p style="color:white; float:right;"><?=$_SESSION["user_fullname"]?>  </p>
  </div>


  <?php
		$conn = DbHelper::GetConnection();
    if(strpos($_SESSION["user_role"], 'програмист') !== false) {
      $stm = $conn->prepare("SELECT * FROM tickets WHERE user_id = ?");
      $count = $stm->execute(array($_SESSION["user_id"]));
    } else {
      $stm = $conn->prepare("SELECT * FROM tickets WHERE receiver = ? AND isPrivateTicket = ?");
      $count = $stm->execute(array($_SESSION["user_role"], "За всички"));
    }
		$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
		unset($conn);
	?>
  <form method="post" style="border:1px solid #ccc" action="" enctype="multipart/form-data">

    <div class="container">
      <h1>Tickets</h1>

      <?php
			foreach($rows as $r) {
        $recentImage = $r["image"];
			?>
      <div class="content">
        <p>
          <?=$r["title"]?>
        </p><br><br>
        <p>
          <?=$r["content"]?>
        </p><br><br>
        <img src="images\<?=$r["image"]?>">
        <br><br>
        <p>
          <?=$r["isPrivateTicket"]?>
        </p><br><br>
        <p>
          <?=$r["receiver"]?>
        </p><br><br>
        <button type="submit" class="viewTicketBtn" name="viewTicketBtn" value="<?=$r["ticket_id"]?>">Виж Ticket&raquo;</button>
      </div>
      <?php
			}
		?>

      <?php
      if(strpos($_SESSION["user_role"], 'програмист') !== false) {
      ?>
      <div class="content">
        <a href="ticket.php" class="next">Добави Ticket &raquo;</a>
      </div>
      <?php
}
?>
  </form>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
</body>

</html>