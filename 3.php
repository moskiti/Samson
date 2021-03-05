<?php
namespace Test3;

class newBase
{
    static private $count = 0;
    static private $arSetName = [];
    /**
     * @param string $name
     */
    function __construct(int $name = 0)
    {

        if (empty($name)) {
            while (array_search(self::$count, self::$arSetName) !== false)  { // результат этой функции нужно проверять без приведения типов
                ++self::$count;
            }

            $name = self::$count;
        }
        $this->name = $name;
        self::$arSetName[] = $this->name;
    }
    protected  $name; //поменял на protected вместо private
    /**
     * @return string
     */
    public function getName(): string
    {
        return '*' . $this->name  . '*';

    }
    protected $value; //поменял на protected вместо private
    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function getSize()
    {
//        return strlen($size) + $size; - не понятно зачем здесь strlen($size)
        return strlen(serialize($this));
    }
    public function __sleep()
    {
        return ['name','value'];
    }
    /**
     * @return string
     */
    public function getSave(): string
    {
       return serialize($this);
//        return $this->name . ':' . strlen($value) . ':' . $value;// serialize сам всё сделает
    }
    /**
     * @return newBase
     */
    static public function load(string $value): newBase
    {

//        $arValue = explode(':', $value);
//        return (new newBase($arValue[0]))
//            ->setValue(unserialize(substr($value, strlen($arValue[0]) + 1
//                + strlen($arValue[1]) + 1), $arValue[1]));
        return unserialize($value);// unserialize сам всё сделает
    }
}
class newView extends newBase
{
    private $type = null;
    private $size = 0;
    private $property = null;
    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->setType();
        $this->setSize();
    }
    public function setProperty($value)
    {
        $this->property = $value;
        return $this;
    }
    private function setType()
    {
        $this->type = gettype($this->value);
    }
    private function setSize()
    {

       $this->size = strlen(serialize($this));// используем serialize
//        if (is_subclass_of($this->getValue(), "Test3\\newView")) {  не правельно записано экранирующий символ
//            $this->size = parent::getSize() + 1 + strlen($this->property);
//        } else if ($this->type == 'test') {
//         else {
//            $this->size = strlen($this->getValue());
//        }
    }
    /**
     * @return string
     */
    public function __sleep()
    {
        $arr = parent::__sleep();
        array_push($arr,'property','size','type');
        return $arr; // добавили поля для сериализации
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        if (empty($this->name)) {
            throw new Exception('The object doesn\'t have name');
        }
        return '"' . $this->name  . '": ';
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return ' type ' . $this->type  . ';';
    }
    /**
     * @return string
     */
    public function getSize(): string
    {
        return ' size ' . $this->size . ';';
    }
    public function getInfo()
    {
        try {
            echo $this->getName()
                . $this->getType()
                . $this->getSize()
                . "\r\n";
        } catch (Exception $exc) {
            echo 'Error: ' . $exc->getMessage();
        }
    }
    /**
     * @return string
     */
    public function getSave(): string
    {
//        if ($this->type == 'test') {
//            $this->value = $this->value->getSave();
//
//        }
        return serialize($this);// использовали сериализацию
    }
    /**
     * @return newView
     */
    static public function load(string $value): newBase
    {
//        return (new newBase($arValue[0]))
//            ->setValue(unserialize(substr($value, strlen($arValue[0]) + 1
//                + strlen($arValue[1]) + 1), $arValue[1]))
//            ->setProperty(unserialize(substr($value, strlen($arValue[0]) + 1
//                + strlen($arValue[1]) + 1 + $arValue[1])))
//            ;
        return unserialize($value);// использовали десериализацию
    }
}
function gettype($value): string
{
    $type = '';
    if (is_object($value)) {
        $type = get_class($value);
        do {
           if (strpos($type, "Test3\\newBase") !== false) { //поправил запись экранирующего символа
                return 'test';
           }
        } while ($type = get_parent_class($type));
    }
   return $type;// бесконечный вызов функции
}

$obj = new newBase('12345');
$obj->setValue('text');

$obj2 = new newView('09876');//не O, а 0
$obj2->setValue($obj);
$obj2->setProperty('field');
$obj2->getInfo();


$save = $obj2->getSave();


$obj3 = newView::load($save);

var_dump($obj2->getSave() == $obj3->getSave());

