
<?php
	 
	require("functions.php");
	
	require("Helper.class.php");
	
	$Helper = new Helper($mysqli);
	
	require("Nature.class.php");
	$nature = new nature($mysqli);
	
	
	$descriptionError = "*";
		
	if (isset ($_POST["description"])) {
			if (empty ($_POST["description"])) {
				$descriptionError = "*Sisesta kirjeldus!";
			} else {
				$description = $_POST["description"];
		}
		
	} 
	
	$dateError = "*";
	
	if (isset ($_POST["date"])) {
			if (empty ($_POST["date"])) {
				$dateError = "*Sisesta kuupäev!";
			} else {
				$date = $_POST["date"];
		}
		
	} 
	
	$urlError = "*";
	
	if (isset ($_POST["url"])) {
			if (empty ($_POST["url"])) {
				$urlError = "*Sisesta ürituse asukoht!";
			} else {
				$url = $_POST["url"];
		}
		
	} 
	
	
		// kui ei ole sisseloginud, suunan login lehele
		if(!isset($_SESSION["userId"])) {
			header("Location: login.php");
		}
	
		//kas aadressi real on logout
	if (isset($_GET["logout"])) {
		session_destroy();
		
		header("Location: login.php");
		
	}
	if ( isset($_POST["description"]) &&
	     isset($_POST["date"]) &&
		 isset($_POST["url"]) &&
		 !empty($_POST["description"]) &&
		 !empty($_POST["date"])&&
		 !empty($_POST["url"])
		 ) {
			 $nature->saveNature($Helper->cleanInput($_POST["description"]), $Helper->cleanInput($_POST["date"]), $Helper->cleanInput($_POST["url"]));
			 
			 header("Location: data.php");
			 
			 }
			 
		
	if (isset($_GET["q"])) {
		
		$q = $_GET["q"];
	
	} else {
		$q = "";
		}
		$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && (isset($_GET["order"]))) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
		
		
	
	
	
	$nature = $nature->getAllNature($q, $sort, $order);
	
		
?>

<h1>Data</h1>

<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>!
	<a href="?logout=1">logi välja</a>
	
</p>
<body>
	
		<h1>Salvesta andmed</h1>
		
		<form method="POST">
	<input name="description" placeholder="Kirjeldus" type="text"> <br><br>
	<input name="date" placeholder="Kuupäev" type="text"> <br><br>
	<input name="url" placeholder="Pilt" type="text"> <br><br>
	<input type="submit" value="Sisesta andmed">
</form>
		
		<h2>Arhiiv</h2>
		
		<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>

<?php
	$html = "<table>";
		
		$html .= "<tr>";
		
			$orderId = "ASC";
			
			if (isset($_GET["order"]) && 
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "id" ) {
					
				$orderId = "DESC";
				
			}
			
			$orderDescription = "ASC";
        if (isset($_GET["order"]) &&
            $_GET["order"] == "ASC" &&
            $_GET["sort"] == "description" ) {
            $orderDescription = "DESC";
        }
		
			$orderDate = "ASC";
        if (isset($_GET["order"]) &&
            $_GET["order"] == "ASC" &&
            $_GET["sort"] == "date" ) {
            $orderDate = "DESC";
        }
		
			$orderUrl = "ASC";
        if (isset($_GET["order"]) &&
            $_GET["order"] == "ASC" &&
            $_GET["sort"] == "Url" ) {
            $orderUrl = "DESC";
        }
		
			$html .= "<th>
						<a href='?q=".$q."&sort=id&order=".$orderId."'>
							ID 
						</a>
					 </th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=description&order=".$orderDescription."'>
							Kirjeldus
						</a>
					 </th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=date&order=".$orderDate."'>
							Kuupäev
						</a>
					 </th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=url&order=".$orderUrl."'>
							Url
						</a>
					 </th>";
					 
					 $html .= "</tr>";
		
		foreach ($nature as $n) {
		$html .= "<tr>";
			$html .= "<td>".$n->id."</td>";
			
			$html .= "<td>".$n->description."</td>";
			
			$html .= "<td>".$n->date."</td>";
			$html .= "<td><img width='150' src=' ".$n->url." '></td>";
			 $html .= "<td><a href='edit.php?id=".$n->id."'>Muuda</a></td>";
			$html .= "</tr>";
		
	}
	$html .= "</table>";
	echo $html;
?>
</body>	
</html>