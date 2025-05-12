<?php

 
 
    $sql = "SELECT * FROM roles";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
 

