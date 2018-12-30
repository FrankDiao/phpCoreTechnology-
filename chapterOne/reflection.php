<?php

/**
 * 反射API示例
 * 演示获取一个类的所有成员
 */
class Person{
    const birthday = "1998/11/24";
    public $name = "frank";
    protected $age = "21";
    private $career = "coder";

    public static function hello($test){
        echo "hello";
    }

    /**
     * 获取姓名
     */
    public function getName(){
        echo "My name is {$this->name}.";
    }

    protected function getAge(){
        echo "I am {$this->age} years old";
    }

    private function getCareer(){
        echo "My occupation is a {$this->career}";
    }
}

$frank = new Person();

$obj = new ReflectionObject($frank);

//获取类命
$className = $obj->getName();

//获取所有类常量
$const = $obj->getConstants();

// 获取属性
$property = $obj->getProperties();

//获取方法
$method = $obj->getMethods();

$props = $mets = array();

foreach ($property as $key => $val){
    //获取属性名
    $name = $val->getName();

    //是否为静态属性
    $isStatic = $val->isStatic()?"static":"";

    //属性的权限修饰符
    $purview = $val->isPrivate()?"private":$val->isProtected()?"protected":"public";

    //如果属性不是public 需要调用setAccessible()设置为可访问 才能获取属性值
    $purview !== 'public' && $val->setAccessible(true);

    //如果属性不是static 需要传一个对象才能获取属性值
    $value = $val->isStatic() ? $val->getValue() : $val->getValue($frank);

    $props[$name] = "{$purview} {$isStatic} \${$name} = {$value};";
}

foreach ($method as $val){
    //获取方法名
    $name = $val->getName();

    //是否为静态方法
    $isStatic = $val->isStatic()?"static":"";

    //方法的权限修饰符
    $purview = $val->isPrivate()?"private":$val->isProtected()?"protected":"public";

    //将方法设置为可访问
    $purview !== 'public' && $val->setAccessible(true);

    //获取方法注释
    $doc = $val->getDocComment();

    //获取方法参数
    $args = "";
    foreach ($val->getParameters() as $v ){
        $args .= "\$".$v->name.",";
    }
    $args = substr($args,1,(strlen($args)-2));

    $mets[$name] = $doc."\n";
    $mets[$name] .= "{$purview} {$isStatic} {$name}({$args}){}";
}


echo "class {$className} {";
foreach ($const as $key => $val){
    echo "\n \t \$".$key." = ".$val;
}
foreach ($props as $key => $val){
    echo "\n \t ".$val;
}
foreach ($mets as $key => $val){
    echo "\n \t".$val;
}
echo "\n}";

