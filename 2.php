<?php
$i = "Hello Hello Hello";
$y = "Hello";
$d = strpos($i,$y);
convertString($i,$y);
function convertString($a, $b)
{
    $lenb = strlen($b);
    $d = strpos($a, $b);
    $reversB = strrev($b);
    if ($d !== false) {
        $d = strpos($a, $b, $lenb);
        if ($d !== false) {
            return substr_replace($a, $reversB, $d, $lenb);
        }
    }
    return $a;
}


$p = [['a' => 2, 'b' => 1], ['a' => 1, 'b' => 3], ['a' => 3, 'b' => 7], ['a' => 0, 'b' => 2]];
$l = 'a';
$sorted = mySortForKey($p, $l);
function mySortForKey($a, $b)
{
    foreach ($a as $key => $value) {

        if (!array_key_exists($b, $value)) {

            throw new Exception("Неправельный массив с идексом  $key");
        }


    }
    usort($a, function ($first, $second) use ($b) {
        if ($first[$b] == $second[$b]) {
            return 0;
        }
        if ($first[$b] > $second[$b]) {
            return 1;
        } else {
            return -1;
        }


    });
    return $a;
}

require_once 'connect_db.php';

$xml = simplexml_load_file('xml1.xml');
exportXml('xml2.xml', 100);
//importXML($xml);

function importXML($xml)
{
    global $connect;
    $result = mysqli_fetch_array(mysqli_query($connect, "SELECT MAX(code) AS max_code FROM a_category"), MYSQLI_NUM);

    $c_code = 100;
    if ($result[0] != null) {
        $c_code = $result[0];
    }
    foreach ($xml as $product) {
        $code = $product['Код'];
        $name = $product['Название'];
        $sql = "INSERT INTO a_product (code, name)
VALUES ('$code','$name')";
        mysqli_query($connect, $sql);
        $product_id = mysqli_insert_id($connect);


        foreach ($product->Цена as $price) {
            $type = $price['Тип'];
            $sql = "INSERT INTO a_price (product_id, type, price)
VALUES ('$product_id','$type','$price')";
            mysqli_query($connect, $sql);
        }

        foreach ($product->Свойства as $properties) {
            foreach ($properties as $property => $value) {
                $unit = $value['ЕдИзм'];
                $sql = "INSERT INTO a_property (product_id, name, property,unit)
VALUES ('$product_id','$property','$value','$unit')";
                mysqli_query($connect, $sql);
            }
        }
        foreach ($product->Разделы->Раздел as $category) {

            $sql = "SELECT * FROM a_category WHERE name LIKE '$category'";
            $result = mysqli_query($connect, $sql);
            $categoryF = null;
            if ($result) {
                $categoryF = mysqli_fetch_assoc($result);
            }
            if ($categoryF != null && $categoryF['name'] == $category) {
                $category_id = $categoryF ['category_id'];
            } else {
                $sql = "INSERT INTO a_category (code, name)
  VALUES ('$c_code','$category')";

                mysqli_query($connect, $sql);
                $category_id = mysqli_insert_id($connect);
            }
            $sql = "INSERT INTO a_product_category (product_id,category_id)
VALUES ('$product_id','$category_id')";
            mysqli_query($connect, $sql);
            $c_code++;
        }


    }
}

function exportXml($a, $b)
{

    global $connect;
    $select = "SELECT * FROM a_category WHERE code = $b ";
    $result = mysqli_query($connect, $select);
    $xml = new DomDocument('1.0', 'utf-8');
    $products = $xml->appendChild($xml->createElement('Товары'));
    $assoc = mysqli_fetch_assoc($result);
    $parent_id = $assoc['category_id'];
    $cat_ids = [];
    appendChilds($parent_id, $cat_ids);
    $cat_ids [] = $parent_id;
    $sql = "SELECT DISTINCT a_product.product_id,a_product.name,a_product.code
        FROM a_product_category JOIN a_product 
        ON a_product_category.product_id = a_product.product_id
         WHERE category_id IN (" . implode(',', $cat_ids) . ")";
    $result = mysqli_query($connect, $sql);
    foreach ($result as $product) {
        $productx = $products->appendChild($xml->createElement('Товар'));
        $productx->setAttribute('Код', $product['code']);
        $productx->setAttribute('Название', $product['name']);
        $select = "SELECT * FROM a_price WHERE product_id = $product[product_id] ";
        $result = mysqli_query($connect, $select);
        foreach ($result as $price) {
            $pricex = $productx->appendChild($xml->createElement('Цена', $price['price']));
            $pricex->setAttribute('Тип', $price['type']);


        }
        $select = "SELECT * FROM a_property WHERE product_id = $product[product_id] ";
        $result = mysqli_query($connect, $select);
        $propertyx = $productx->appendChild($xml->createElement('Свойства'));
        foreach ($result as $property) {
            $property_ch = $propertyx->appendChild($xml->createElement($property['name'], $property['property']));
            if ($property['unit'] != null) {
                $property_ch->setAttribute('ЕдИзм', $property['unit']);
            }


        }
        $select = "SELECT a_category.name FROM a_product_category 
JOIN a_category 
ON  a_product_category.category_id = a_category.category_id 
WHERE product_id = $product[product_id]";
        $result = mysqli_query($connect, $select);
        $categoryx = $productx->appendChild($xml->createElement('Разделы'));

        foreach ($result as $category) {
            $categoryx->appendChild($xml->createElement('Раздел', $category['name']));

        }

    }

    $xml->formatOutput = true;
    $xml->save($a);
}

function appendChilds($parent_id, &$childs)
{
    global $connect;
    $select = "SELECT category_id FROM a_category WHERE parent_id = $parent_id ";
    $result = mysqli_query($connect, $select);
    foreach ($result as $child) {
        $id_child = $child ['category_id'];
        $childs[] = $id_child;
        appendChilds($id_child, $childs);
    }


}




