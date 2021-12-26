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

  /*For images */
  div.gallery {
    margin: 5px;
    border: 1px solid #ccc;
    float: left;
    width: 180px;
  }

  div.gallery:hover {
    border: 1px solid #777;
  }

  div.gallery img {
    width: 100%;
    height: auto;
  }

  div.desc {
    padding: 15px;
    text-align: center;
  }

  span.c {
    display: block;
    width: 100px;
    height: 100px;
    padding: 5px;
  }

  span.d {
    display: block;
    width: 40px;
    height: 40px;
    padding: 5px;
  }

  /* За бутон изпрати коментар */
  .buttonOne {
    background-color: #04AA6D;
    color: white;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
  }

  /* всички коментари */
  /* .comment {
  width: 1000px; 
  border: 1px solid #000000;
  word-wrap: break-word;
} */
</style>

<body>
  <?php
    session_start();
    require_once('DbHelper.php');
		$conn = DbHelper::GetConnection();
    $stm = $conn->prepare("SELECT * FROM ticket_comments WHERE ticket_id = ?");
    $count = $stm->execute(array($_SESSION["ticket_id"]));
		$rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    $stm1 = $conn->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
    $count1 = $stm1->execute(array($_SESSION["ticket_id"]));
    if($count1 == 1) {
		  $rows1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
    }

    if(isset($_POST['addBtn'])) {
      $imgData = addslashes(file_get_contents($_FILES['userImage']['name']));
      $stm = $conn->prepare('INSERT INTO ticket_comments(ticket_id, user_id, image, comment_content, sender_name, sender_role) VALUES(?, ?, ?, ?, ?, ?)');
      $stm->execute(array($_SESSION["ticket_id"], $_SESSION["user_id"], $imgData, $_POST['tbComment'], $_SESSION["user_fullname"], $_SESSION["user_role"]));
      header("Location: viewTicket.php");
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
  </div>


  <form method="post" style="border:1px solid #ccc" action="" enctype="multipart/form-data">
    <div class="container">
      <h1>Ticket - <?=$rows1[0]["title"]?></h1>
      <hr>

      <div class="gallery">
        <h3>Съдържание: </h3>
        <div class="desc"><?=$rows1[0]["content"]?></div>
        <h3>Получател: </h3>
        <div class="desc"><?=$rows1[0]["receiver"]?></div>
      </div>
      <div>
        <span class="c"></span> <span class="c"></span>
      </div>

      <section class="row">
        <div class="col-md-4 mt-3 col-lg-4">
      </div>
    </section>  
<br><br>
      <p>
        <label for="tbComment"><b>
            Коментар:</b></label><br>
        <input type="text" id="tbComment" name="tbComment" require><br>
      </p>
      <form action="/action_page.php">
        <label for="myfile"><b>Добавете снимка:</b></label>
        <input name="userImage" type="file" class="inputFile" required />
        <!-- <input class="form-control" id="image" name="tmp_name" type="file" accept="image/*" required /><br><br> -->
      </form>
      <button type="submit" class="addBtn" name="addBtn">Изпрати коментар</button>
      <div>
        <span class="d"></span> <span class="d"></span>
      </div>

      <div>
        <h3>Коментари</h3><br>
      </div>

      <?php
			foreach($rows as $r) {
        $recentImage = $r["image"];
			?>
      <div class="content">
        <h3>От: <?=$r["sender_name"]?></h3>
        <p>
          <?=$r["comment_content"]?>
        </p><br><br>
        <!-- <img src="data:image/jpeg;base64,<?=$recentImage?>"> -->
        <?php
      // echo '<div class="caption"><h3><img src="data:image/jpeg;base64,'.base64_encode($recentImage).'"/>'. $recentImage. '</h3></div>';
      echo '<img src="data:image;base64,'.base64_encode($recentImage).'"/ >';
      ?>
        <br><br>
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