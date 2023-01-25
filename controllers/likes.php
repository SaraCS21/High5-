<?php

    function countLikesPost($idPost){
        try {
            $connection = connect();

            $querySQL = "SELECT * FROM likes WHERE idPost = $idPost";
            $sentence = $connection->prepare($querySQL);
            $sentence->execute();

            $likes = $sentence->fetchAll();
            $continue = ($likes && $sentence->rowCount()>0) ? true : false;

            return $sentence->rowCount();

        } catch(PDOException $error) {
            $error = $error->getMessage();
        }
    }

    function getLikesPost($idPost){
        try {
            $connection = connect();

            $querySQL = "SELECT * FROM likes WHERE idPost = $idPost";
            $sentence = $connection->prepare($querySQL);
            $sentence->execute();

            $likes = $sentence->fetchAll();
            return $likes;

        } catch(PDOException $error) {
            $error = $error->getMessage();
        }
    }

    function setLike(){
        try {
            $connection = connect();

            $like = [
                "idUser" => $_SESSION["idUser"],
                "idPost" => $_REQUEST["idPost"]
            ];

            $querySQL = "INSERT INTO likes (idUser, idPost)";
            $querySQL .= "VALUES (:" . implode(", :", array_keys($like)) . ")";

            $sentence = $connection->prepare($querySQL);
            $sentence->execute($like);

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    function deleteLike(){
        try {
            $connection = connect();

            $sql = "DELETE FROM likes WHERE idUser = :idUser AND idPost = :idPost";

            $querySQL = $connection->prepare($sql);

            $idUser = $_SESSION["idUser"];
            $querySQL->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            
            $idPost = $_REQUEST["idPost"];
            $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }



?>