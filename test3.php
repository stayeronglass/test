<?php

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    $message = "Случился нежданчик \"$message\" в файле \"$file\" на строчке \"$line\" ";
    throw new ErrorException($message, 0, $severity, $file, $line);
}//function exception_error_handler($severity, $message, $file, $line)

function exception_handler(\Exception $exception)
{
    throw $exception;
}//function exception_handler(\Exception $exception)

set_exception_handler('exception_handler');
set_error_handler("exception_error_handler");

class routeException extends \Exception
{
}

;

/**
 * Class Route
 *
 * Напишите на PHP функцию поиска самого дешевого маршрута. Функция должна получать на входе три параметра:
 * название населенного пункта отправления, название населенного пункта прибытия, а также список,
 * каждый элемент которого представляет собой названия неких двух населенных пунктов и стоимость
 * проезда от одного населенного пункта до другого. На выходе функция должна возвращать
 * самый дешевый маршрут между населенными пунктами отправления и прибытия, в виде списка
 * транзитных населенных пунктов (в порядке следования), а также общую стоимость проезда.
 *
 *
 * Дана вырожденная матрица инцидентности в которой уже нет нулевых элементов.
 * Найти кратчайший маршрут и длину/стоимость этого маршрута
 *
 */
class Route
{

    /**
     * @var array
     *
     * список, каждый элемент которого представляет собой названия неких
     * двух населенных пунктов и стоимость проезда от одного населенного пункта до другого
     */
    private $routes = [];
    /**
     * @var string
     * название населенного пункта отправления
     */
    private $start = '';
    /**
     * @var string
     * название населенного пункта прибытия
     */
    private $finish = '';


    /**
     * Route constructor.
     * @param array $routes
     * @param $start
     * @param $finish
     *
     * валидатор явных неправильностей ну и конструктор класса
     */

    public function __construct(array $routes, $start, $finish)
    {
        $start = trim($start);
        if (empty($start))
            throw new routeException("Пустое место отправления!");
        if (preg_match('#^а-яё+\-$#iu', $start))
            throw new routeException("Неверное название места отправления \"$start\"!");


        $finish = trim($finish);
        if (empty($finish))
            throw new routeException("Пустое место прибытия!");
        if (preg_match('#^а-яё+\-$#iu', $finish))
            throw new routeException("Неверное название места прибытия \"$finish\"!");

        if ($start == $finish)
            throw new routeException("Одинаковые города прибытия и отправления!");

        $this->start  = $start;
        $this->finish = $finish;
        $this->routes = $routes;

    }//public function __construct(array $routes)


    /**
     * @throws routeException
     *
     * провалидировать пришедшие роуты
     * здесь представлен джентельменский набор. предполагаются города с русскими названиями
     *
     */
    private function checkRoutes()
    {

        if (empty($this->routes))
            throw new routeException("А что маршрутов совсем нет?");

        foreach ($this->routes as $key => $route) {

            if (intval($route['cost']) < 0)
                throw new routeException("Найдена отрицательная стоимость маршрута {$route['cost']} для индекса $key!");


            $route['start'] = trim($route['start']);
            if (empty($route['start']))
                throw new routeException("Пустой город отправления для индекса $key!");
            if (preg_match('#^а-яё+\-$#iu', $route['start']))
                throw new routeException("Неверное название города отправления \"{$route['start']}\" для индекса $key!");


            $route['finish'] = trim($route['finish']);
            if (empty($route['finish']))
                throw new routeException("Пустой город прибытия для индекса $key!");
            if (preg_match('#^а-яё+\-$#iu', $route['finish']))
                throw new routeException("Неверное название города \"{$route['finish']}\" для индекса $key!");

        }//foreach ($this->routes as $firstKey => $firstRoute)

        return true;
    }//private function sanitizeRouts(){


    /**
     * @param $start string название населенного пункта отправления
     * @param $finish string название населенного пункта прибытия
     * @param $routes array массив роутов извне
     * @param int $cost текущая минимальная стоимось
     * @param array $route промежуточный результат вычислений маршрута
     * @param array $result промежуточный результат вычислений
     * @return array то что надо!
     *
     * рекурсивный обход в глубину, только чуть проще.
     * формат приходящих данных позволяет сделать такой фокус совсем простым.
     *
     */
    function calculateShortestRoute($start, $finish, $routes, $cost = 0, $route = [], $result = [[], 0])
    {
        foreach ($routes as $path) {
            if ($path['start'] == $start) {
                if ($path['finish'] == $finish) {
                    if ($result[1] > $cost || !$result[1]) {
                        $result = [array_merge($route, [$path]), $cost + $path['cost']];
                    }
                } else {
                    $result = $this->calculateShortestRoute($path['finish'], $finish, $routes, $cost + $path['cost'], array_merge($route, [$path]), $result);
                }
            }
        }
        return $result;
    }


    /**
     * @param $start
     * @param $finish
     * @param $list
     *
     * просто вывести на экран результат функции выше.
     *
     */
    private function prettyPrint($start, $finish, $list)
    {
        echo 'Поездка <b>' . $start . ' - ' . $finish . '</b><br />';

        if (!empty($list[0])) {

            foreach ($list[0] as $key => $value) {
                echo $value['start'] . '->';
            }
            echo $value['finish'] . '<br />';

            echo 'Общая стоимость поездки ' . $list[1] . '<br />';

        } else {
            echo 'Маршрут не найден!';
        }
    }


    /**
     * @throws routeException
     *
     * собрать всё вместе
     */
    public function showRoute()
    {

        $this->checkRoutes();
        $list = $this->calculateShortestRoute($this->start, $this->finish, $this->routes);
        $this->prettyPrint($this->start, $this->finish, $list);
    }

}//class Route{


$graph = [

    [
        'start' => 'Питер',
        'finish' => 'Выборг',
        'cost' => 10,
    ],
    [
        'start' => 'Москва',
        'finish' => 'Питер',
        'cost' => 100,
    ],
    [
        'start' => 'Питер',
        'finish' => 'Псков',
        'cost' => 40,
    ],
    [
        'start' => 'Выборг',
        'finish' => 'Хельсинки',
        'cost' => 100,
    ],
    [
        'start' => 'Питер',
        'finish' => 'Хельсинки',
        'cost' => 20,
    ],
];

$route = new Route($graph, 'Москва', 'Хельсинки');
$route->showRoute();

echo '<br />';
$route = new Route($graph, 'Москва', 'Питер');
$route->showRoute();
echo '<br />';
$route = new Route($graph, 'Москва', 'Магадан');
$route->showRoute();
echo '<br />';

