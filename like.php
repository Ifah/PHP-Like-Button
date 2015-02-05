<?php
require_once  'init.php';

if(isset($_GET['type'], $_GET['id'])){
	$type = $_GET['type'];
	$id = (int)$_GET['id'];

	switch ($type) {
		case 'article':
			$db->query("
				INSERT INTO articles_likes (user, article)
					SELECT {$_SESSION['user_id']}, {$id}
					FROM articles
					WHERE EXISTS (
						SELECT id
						FROM articles
						WHERE id = {$id})
					AND NOT EXISTS (
						SELECT id
						FROM articles_likes
						WHERE user = {$_SESSION['user_id']}
						AND article = {$id})
					LIMIT 1
				");
			break;
	}
}
header("Location: index.php");
?>