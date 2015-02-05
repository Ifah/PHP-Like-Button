<?php
require_once  'init.php';

$articleQuery = $db->query("
	SELECT 
	articles.id, 
	articles.title,
	COUNT(articles_likes.id) AS likes,
	GROUP_CONCAT(users.username SEPARATOR '|') AS liked_by

	FROM articles

	LEFT JOIN articles_likes
	ON articles.id = articles_likes.article

	LEFT JOIN users
	ON articles_likes.user = users.id

	GROUP BY articles.id
	");

while($row = $articleQuery->fetch_object()){
	$row->liked_by = $row->liked_by ? explode('|', $row->liked_by) : [];
	$articles[] = $row;
}

// echo '<pre>', print_r($articles, true), '</pre>';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Articles</title>
  </head>
  <body>
  	<?php foreach($articles as $article): ?>
  		<div class="article">
  			<h3><?php echo $article->title; ?></h3>
  			<a href="like.php?type=article&id=<?php echo $article->id; ?>">Like</a>

  			<p>No of people liked this (<?php echo $article->likes; ?>).</p>
  			<?php if(!empty($article->liked_by)): ?>
  				<ul>
  					<?php foreach($article->liked_by as $user): ?>
  						<li><?php echo $user; ?></li>
  					<?php endforeach; ?>
  				</ul>
  			<?php endif; ?>
  		</div>
  	<?php endforeach; ?>
  </body>
</html>