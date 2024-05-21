<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once('../core/initialize.php');

$dbname = 'unibendb';
$collection = 'publications';

$db = new DbManager();
$conn = $db->getConnection();

$filter = [];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

$result = $conn->executeQuery("$dbname.$collection", $read)->toArray();

$articles = [];

if (count($result) > 0) {
    $articlesData = $result[0]->articles;

    foreach ($articlesData as $article) {
        if ($article->copy_right_access === "closed access") {
            //unset($article->upload);
            $article->upload = "";
        }
        $articles[] = $article;
    }
}

echo json_encode($result);
?>
