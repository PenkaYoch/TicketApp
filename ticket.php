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
</style>

<body>
  <?php
require_once('DbHelper.php');
$conn = DbHelper::GetConnection();
session_start();
$ticket = null;
$errors = array();
if(isset($_POST['addTicketBtn'])) {
    if(!isset($_POST['tbTitle']) || mb_strlen($_POST['tbTitle'], 'utf-8') < 4 || mb_strlen($_POST['tbTitle'], 'utf-8') > 50) {
        $errors[] = "Invalid title!";
    }
    if(!isset($_POST['tbContent']) || mb_strlen($_POST['tbContent'], 'utf-8') < 4 || mb_strlen($_POST['tbContent'], 'utf-8') > 150) {
      $errors[] = "Invalid content!";
  }
    if(count($errors) == 0 && $ticket == null) {
      $image = null;
      // if(!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
      //   $image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
      //  }
        $imgData = $_FILES['userImage']['name'];//addslashes(file_get_contents($_FILES['userImage']['name']));
        $userId = $_SESSION["user_id"];
        $stm = $conn->prepare('INSERT INTO tickets(title, content, image, isPrivateTicket, receiver, user_id) VALUES(?, ?, ?, ?, ?, ?)');
        $stm->execute(array($_POST['tbTitle'], $_POST['tbContent'], $imgData, $_POST['isPrivate'], $_POST['receiver'], $userId));
        header("Location: index.php");
    }
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
    <!-- <a href="login.php">Вход</a> -->
    <!-- <a href="register.php">Регистрация</a> -->
  </div>


  <form method="post" style="border:1px solid #ccc" action="" enctype="multipart/form-data">  
    <div class="container">
      <h1>Ticket</h1>
      <p>Моля, попълнете полетата правилно.</p>
      <hr>

      <p>
        <label for="tbTitle"><b>Заглавие:</b></label><br>
        <input type="text" id="tbTitle" name="tbTitle" required value="<?=($ticket != null) ? $ticket[" title"] : ""
          ?>"><br>
      </p>

      <label for="tbContent"><b>Съдържание</b></label>
      <input type="text" id="tbContent" name="tbContent" required>

      <form action="/action_page.php">
        <label for="myfile"><b>Добавете снимка:</b></label>
        <input name="userImage" type="file" class="inputFile" required />
        <!-- <input class="form-control" id="image" name="tmp_name" type="file" accept="image/*" required /><br><br> -->
      </form>

      <p>
        <b>Видима за: </b><br>
        <select name="isPrivate">
          <option value="За мен" selected>За мен</option>
          <option value="За всички">За всички</option>
        </select>
      <P>
      </p><br>
      <b>За кого се отнася този Ticket: </b><br>
      <select name="receiver">
        <option value="поддръжка офис" selected>поддръжка офис</option>
        <option value="поддръжка - техническа част">поддръжка - техническа част</option>
      </select>
      <P>
      </p>

      <!-- <label><input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Запомни ме </label> -->

      <div class="clearfix">
        <button type="submit" class="addTicketBtn" name="addTicketBtn">Добави Ticket</button>
      </div>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
</body>

</html>