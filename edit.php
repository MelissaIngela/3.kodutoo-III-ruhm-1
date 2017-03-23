<?php
	
	require("functions.php");
	
	require("Helper.class.php");
	$Helper = new Helper();
	
	require("Nature.class.php");
	$Nature = new Nature($mysqli);
	
	if(isset($_GET["delete"]) && isset($_GET["id"])){
		
		$Nature->deleteNature($Helper->cleanInput($_GET["id"]));
		header("Location: data.php");
        exit();	
		
	}
	
	
	if(isset($_POST["update"])){
		
		$Nature->updateNature($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["description"]), $Helper->cleanInput($_POST["date"]), $Helper->cleanInput($_POST["url"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	
	$p = $Nature->getSinglePerosonData($_GET["id"]);
	var_dump($p);

	
?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="description" >Kirjeldus</label><br>
	<input id="description" name="description" type="text" value="<?php echo $p->description;?>" ><br><br>
  	<label for="date" >Kuupäev</label><br>
	<input id="date" name="date" type="date" value="<?=$p->date;?>"><br><br>
	<label for="url" >Url</label><br>
	<input id="url" name="url" type="text" value="<?php echo $p->url;?>" ><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  <a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a> 