<html>

<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>
<style>
  /*За форма*/
  body {
    font-family: Arial, Helvetica, sans-serif;
  }

  {
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
  $id = -1;
  $user = null;
  $errors = array();
  if (isset($_POST['signupbtn'])) {
    if (!isset($_POST['username']) || mb_strlen($_POST['username'], 'utf-8') < 4 || mb_strlen($_POST['username'], 'utf-8') > 50) {
      $errors[] = "Потребителкото име е невалидно!";
    }
    if (!isset($_POST['password']) || mb_strlen($_POST['password'], 'utf-8') < 3 || mb_strlen($_POST['password'], 'utf-8') > 50 || !isset($_POST['tbPasswordAgain']) || $_POST['tbPasswordAgain'] != $_POST['password']) {
      $errors[] = "Невалидна парола!";
    }
    if (!isset($_POST['email']) || mb_strlen($_POST['email'], 'utf-8') < 8 || mb_strlen($_POST['email'], 'utf-8') > 100) {
      $errors[] = "Невалиден E-mail!";
    }
    if (count($errors) == 0 && $user == null) {
      $stm = $conn->prepare('INSERT INTO user(username, name, surname, password, email, role) VALUES(?, ?, ?, ?, ?, ?)');
      $stm->execute(array($_POST['username'], $_POST['name'], $_POST['surname'], $_POST['password'],$_POST['email'],$_POST['role']));
      header("Location: index.php");
    }
  }
  unset($conn);

	if (count($errors) > 0) {
	?>
		<ul style="color: red;">
			<?php
			foreach ($errors as $e) {
				echo "<li>$e</li>";
			}
			?>
		</ul>
	<?php
	}
	?>

  <div class="topnav">
    <a class="active" href="index.php">Начална страница</a>
    <!-- <a href="ticket.php">Ticket</a> -->
    <a href="login.php">Вход</a>
    <a href="register.php">Регистрация</a>
  </div>


  <form method="post" style="border:1px solid #ccc">>
    <div class="container">
      <h1>Регистрация</h1>
      <p>Моля, попълнете полетата правилно.</p>
      <hr>

      <p>
        <label for="tbUsername">Потребителско име:</label><br>
        <input type="text" id="tbUsername" name="username" required value="<?= ($user != null) ? $user["username"] : "" ?>"><br>
      </p>

      <label for="email"><b>Email</b></label>
      <input type="text" name="email" required>

      <p>
        <label for="tbPassword"><b>Парола:</b></label><br>
        <input type="password" id="tbPassword" name="password" required><br>
      </p>

      <p>
        <label for="tbPasswordAgain"><b>Парола(отново):</b></label><br>
        <input type="password" id="tbPasswordAgain" name="tbPasswordAgain" required><br>
      </p>

      <p>
        <label for="tbName"><b>Име:</b></label><br>
        <input type="text" id="tbName" name="name" required value="<?= ($user != null) ? $user["name"] : "" ?>"><br>
      </p>

      <p>
        <label for="tbSurname"><b>Фамилия:</b></label><br>
        <input type="text" id="tbSurname" name="surname" required value="<?= ($user != null) ? $user["surname"] : "" ?>"><br>
      </p>

      <p>
        <b>Роля във фирмата: </b><br>

        <select name="role">
          <option value="програмист - Junior" selected>програмист - Junior</option>
          <option value="програмист - Mid-level">програмист - Mid-level</option>
          <option value="програмист - Senior"> програмист - Senior</option>
          <option value="поддръжка офис">поддръжка офис</option>
          <option value="поддръжка - техническа част">поддръжка - техническа част</option>
        </select>
      <P>
      </p>

      <label><input type="checkbox" checked="checked"" style=" margin-bottom:15px"> Запомни ме </label>

      <div class="clearfix">
        <button type="submit" class="signupbtn" name ="signupbtn" >Регистрация</button>
        <!-- <button type="button" class="cancelbtn">Отказ</button> -->
      </div>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
</body>

</html>