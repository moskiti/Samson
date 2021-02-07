<?php

//Задание 1
function findSimple($a, $b)
{
    $arr = [];

    if (!function_exists('is_prime')) {

        function is_prime($num)
        {
            if ($num == 1)
                return false;
            for ($i = 2; $i <= $num / 2; $i++) {
                if ($num % $i == 0) {
                    return false;
                }
            }
            return true;
        }
    }

    for ($i = $a; $i <= $b; $i++) {
        if (is_prime($i)) {
            $arr[] = $i;
        }

    }

    return $arr;
}

// Задание 2
function createTrapeze($arr)
{
    $keys = ['a', 'b', 'c'];
    $keysCnt = count($keys);
    $out = [];
    foreach ($arr as $j => $val) {
        $key1 = floor($j / $keysCnt);
        $key2 = $j % $keysCnt;
        $out[$key1][$keys[$key2]] = $val;

    }
    return $out;
}

// Задание 3
function squareTrapeze(&$arr)
{
    foreach ($arr as & $keys) {
        $s = (($keys['a'] + $keys['b']) / 2) * $keys['c'];
        $keys['s'] = $s;

    }
    return $arr;
}

$a = array(1, 8, 3, 6, 6, 6, 6, 6, 6, 1, 6, 4, 4, 5, 6);
$trapeze = createTrapeze($a);
squareTrapeze($trapeze);


// Задание 4
$b = 80;
function getSizeForLimit($arr, $b)
{
    $array = [];
    $max = 0;
    foreach ($arr as $tr) {
        $s = $tr['s'];
        if ($s <= $b and $s > $max) {
            $max = $s;
            $array = $tr;
        }

    }
    return $array;
}

getSizeForLimit($trapeze, $b);
// Задание 5

function getMin($a)
{
    $min = null;
    foreach ($a as $key => $item) {
        if ($min == null || $item < $min) {
            $min = $item;

        }
    }


}

$x = array(1 => 1, 3 => 2, 4 => 0);
getMin($x);

// Задание 6
function printTrapeze($a)
{
    echo "<table border='4' class='stats' cellspacing='0'>
            <tr>
            <th>a</th>
            <th>b</th>
            <th>c</th>
            <th>s</th>

            </tr>";
    foreach ($a as $key => $item) {
        echo "<tr>";
        foreach ($item as $value) {
            if ($item['s'] % 2 != 0) {
                echo "<td style='color: #e30b3d'>" . "$value" . "</td>";
            } else echo "<td>" . "$value" . "</td>";


        }

        echo "</tr>";
    }

}

printTrapeze($trapeze);

// Задание 7

abstract class BaseMath
{

    abstract protected function getValue();

    public function exp1($a, $b, $c)
    {
        return $a * pow($b, $c);
    }

    public function exp2($a, $b, $c)
    {
        return pow($a / $b, $c);
    }
}

// Задание 8

class F1 extends BaseMath
{
    private $a, $b, $c;

    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    function getValue()
    {
        return $this->exp1($this->a, $this->b, $this->c) + pow($this->exp2($this->a, $this->b, $this->c) % 3, min($this->a, $this->b, $this->c));
    }

}

$obj = new F1(2, 3, 4);
$a = $obj->getValue();
echo $a;