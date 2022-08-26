<?php

$url = 'https://www.apple.com/sitemap.xml';
$dbUsername = 'root';
$dbPassword = 'root';
$dbName = 'mod4';
$dbHost = '127.0.0.1';

$xmlFilename = 'sitemap.xml';

try {
    $dbConnection = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}

$ch = curl_init($url);


$fp = fopen($xmlFilename, "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
if (curl_error($ch)) {
    fwrite($fp, curl_error($ch));
}
curl_close($ch);
fclose($fp);

$xml = simplexml_load_file($xmlFilename, 'SimpleXMLElement', LIBXML_NOCDATA);

//main loop for categories.
// //idk mb i'll create another for links db
foreach ($xml as $item) {
    //item->loc['path'] is third part of url
    //$item->loc by itself is a completed link
    $url = (parse_url($item->loc))['path'];
    if(stristr($url, '.') === false){
        if(substr($url, -1) === '/') {
            $url = substr($url, 0, -1);
        }
    }
    //exploding by slashes. $urlExplode now is cat array
    $urlExplode = explode('/', $url);

    //

    $level = 0;
    foreach ($urlExplode as $cat) {
        if($cat !== '') {
            $level++;

            //check if there is title in db already
            $data = $dbConnection->prepare('SELECT * FROM categories WHERE title =:title');
            $data->bindValue('title', $cat, PDO::PARAM_STR);
            $data->execute();

            $row = $data->fetch(PDO::FETCH_ASSOC);

            //if there is no such title
            if (!$row) {
                $parent_id = 'null';

                //if it's not level 1
                if ($level > 1) {
                    //we need to find parent_id before insert row
                    $data = $dbConnection->prepare('SELECT id FROM categories WHERE title =:title');
                    $data->bindValue('title', $urlExplode[$level - 1], PDO::PARAM_STR);
                    $data->execute();

                    $parentCheck = $data->fetch(PDO::FETCH_ASSOC);
                    if ($parentCheck) {
                        $parent_id = $parentCheck['id'];
                    }
                }

                //if it's not .html - add cat
                if(stristr($cat, '.html') === false) {
                    $data = $dbConnection->prepare('INSERT INTO categories(level, parent_id, title) VALUES (:level, :parent_id, :title)');
                    $data->bindValue('level', $level, PDO::PARAM_INT);
                    if ($parent_id !== 'null') {
                        $data->bindValue('parent_id', $parent_id, PDO::PARAM_INT);
                    } else {
                        $data->bindValue('parent_id', $parent_id, PDO::PARAM_NULL);
                    }
                    $data->bindValue('title', $cat, PDO::PARAM_STR);
                    $data->execute();
                }


                //after cat add it's time to do smth about link
                //if it is last part of path
                if (!array_key_exists($level + 1, $urlExplode)) {
                    $data = $dbConnection->prepare('SELECT * FROM links WHERE url =:url');
                    $data->bindValue('url', $item->loc, PDO::PARAM_STR);
                    $data->execute();

                    $urlExists = $data->fetch(PDO::FETCH_ASSOC);

                    //if there is no such url in db
                    if(!$urlExists){
                        $categoryForUrl = '';

                        //if url === .html
                        if(!stristr($cat, '.html') === false) {
                            $categoryForUrl = $urlExplode[$level - 1];
                        } else { //if url is not .html
                            $categoryForUrl = $cat;
                        }

                        $data = $dbConnection->prepare('SELECT id FROM categories WHERE title =:title');
                        $data->bindValue('title', $categoryForUrl, PDO::PARAM_STR);
                        $data->execute();

                        $cat_id = $data->fetch(PDO::FETCH_ASSOC);

                        if($cat_id){
                            $id = $cat_id['id'];

                            $data = $dbConnection->prepare('INSERT INTO links(url, cat_id) VALUES(:url, :cat_id)');
                            $data->bindValue('url', $item->loc, PDO::PARAM_STR);
                            $data->bindValue('cat_id', $id, PDO::PARAM_INT);
                            $data->execute();
                        }
                    }
                }
            }
        }
    }
}

//get list of categories links

$main = $dbConnection->prepare('SELECT title FROM categories ORDER BY id');
$main->execute();

$titles = $main->fetchAll(PDO::FETCH_ASSOC);
foreach($titles as $item) {
    echo '<br>'. $item['title'] . ': <br>';
    $rec = $dbConnection->prepare('with recursive cat (id, parent_id, title) as (
            SELECT
                id, parent_id, title
            from
                categories
            WHERE title = :title

            UNION ALL

            SELECT
                c.id, c.parent_id, c.title

            FROM categories c

            inner join cat
              on c.parent_id = cat.id
        )
        select * from cat');
    $rec->bindValue('title', $item['title']);
    $rec->execute();

    $row = $rec->fetchAll(PDO::FETCH_ASSOC);

    if ($row) {
        foreach ($row as $col) {
            $urlData = $dbConnection->prepare('SELECT url FROM links WHERE cat_id = :cat_id');
            $urlData->bindValue('cat_id', $col['id'], PDO::PARAM_INT);
            $urlData->execute();

            $res = $urlData->fetchAll(PDO::FETCH_ASSOC);

            foreach ($res as $value){
                echo "<a href=''>". $value['url'] . '</a>';
                echo '<br>';
            }
        }
    }

}