<?php
class nature {
	
	private $connection;
	

	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}
	
		function saveNature($description, $date, $url) {
			
			$stmt = $this->connection->prepare("INSERT INTO colorNotes2 (description, date, url) VALUE (?, ?, ?)");
			echo $this->connection->error;
			
			$stmt->bind_param("sss", $description, $date, $url);
			
			if ($stmt->execute() ){
				echo "õnnestus";
			} else {
				echo "ERROR".$stmt->error;
			}
		}
		
		function getAllNature ($q, $sort, $order){
			
			$allowedSort = ["id", "description", "date", "url"];
			
			if(!in_array($sort, $allowedSort)){
            $sort = "id";
        }
        $orderBy = "ASC";
        if($order == "DESC") {
            $orderBy = "DESC";
        }
        echo "Sorteerin: ".$sort." ".$orderBy." ";
			
			if ($q != "") {
			
			echo "otsin: ".$q;
			
				$stmt = $this->connection->prepare("SELECT id, description, date, url FROM colorNotes2 WHERE deleted IS NULL AND ( description LIKE ? OR date LIKE ? OR url like ? ) ORDER BY $sort $orderBy");
				$searchWord = "%".$q."%";
				$stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
				
			} else {
				
				$stmt = $this->connection->prepare("SELECT id, description, date, url FROM colorNotes2 WHERE deleted IS NULL ORDER BY $sort $orderBy");
					}
			$stmt->bind_result($id, $description, $date, $url);
			$stmt->execute();
			
			$results = array();
			// Tsükli sisu tehake nii mitu korda, mitu rida SQL lausega tuleb
			while($stmt->fetch()) {
				//echo $color."<br>";
				$nature2= new StdClass();
				$nature2->id = $id;
				$nature2->description = $description;
				$nature2->date = $date;
				$nature2->url = $url;
				
				array_push($results, $nature2);
			}
			
			return $results;
		}
		
		function getSinglePerosonData($edit_id){
		
		$stmt = $this->connection->prepare("SELECT description, date, url FROM colorNotes2 WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($description, $date, $url);
		$stmt->execute();
		
		
		$p = new Stdclass();
		
		
		if($stmt->fetch()){
			
			$p->description = $description;
			$p->date = $date;
			$p->url = $url;
			
			
		}else{
			
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $p;
		
	}
		
	function updateNature($id, $description, $date, $url){
    	
		
		$stmt = $this->connection->prepare("UPDATE colorNotes2 SET description=?, date=?, url=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$description, $date, $url, $id);
		
		
		if($stmt->execute()){
			
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	function deleteNature($id){
    	
		$stmt = $this->connection->prepare("UPDATE colorNotes2 SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		
		if($stmt->execute()){
			
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	
	
}