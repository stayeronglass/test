<?php



class MemcacheClientException extends \Exception{};

/**
 * Class MemcacheClient
 *
 * Сделать асинхронную библиотеку для мемкеша(текстовый протокол) + unit тесты
 * set, get методы
 * Входные данные передаются в любом формате кроме объектов(string, array)
 *
 * сделанно по аналогии с обычными phpшными/мемкешовыми фенкциями поэтому возвращает true или
 * количество
 */
class MemcacheClient{
    /*
     * флаг о сериализации содержиомого в/из мемкеша
     */
    const SERIALIZED = 1;

    private $address = '';
    private $port    = '';
    /**
     * @var resource хэндл сокета для синхронного соединения
     */
    private $fp      = null;
    private $timeout = 1;

    /*
     * очередь асинхронных задачек
     * 'key' мемкешовый ключ
     * 'callback' коллбэк который будет вызван по получению данных
     * 'stream'  хэндл стрима для fсинхронного соединения
     * 'command'  команда на выполнение - пример "get test\r\n",
     */
    private $tasks   = [];



    /**
     * MemcacheClient constructor.
     * @param $address
     * @param $port
     */
    public function __construct($address, $port){

        if (empty($address))
            throw new MemcacheClientException('Адрес не может быть пустым!');
        if(!is_scalar($address))
            throw new MemcacheClientException('Неверный формат адреса!');

        if(!is_scalar($port))
            throw new MemcacheClientException('Неверный формат порта!');
        if (empty($port))
            throw new MemcacheClientException('Порт не может быть пустым!');

        $this->address = (string) $address;
        $this->port    = (string) $port;

    }//public function __construct()

    /**
     * @param $socket resource вкуда писать, актуально для асинхронного режима
     * @param $command string
     * @return int
     * @throws MemcacheClientException
     *
     * записать команду в мемкеш
     *
     * возвращает количество записанных байт
     */
    private function __write($socket, $command){
        $result = @fwrite($socket, $command);

        if ((false === $result) || ($result < strlen($command))){
            throw new MemcacheClientException('Ошибка записи в сокет!');
        }

        return $result;
    }//private function __write($socket, $command){


    /**
     * @param $socket resource
     * @param $command
     * @return string
     * @throws MemcacheClientException
     */
    protected function __writeAndGetAnswer($socket, $command){
        $this->__write($socket, $command);
        $result = $this->__read($socket);

        return $result;
    }

    /**
     * @param $socket resource откуда читать, актуально для асинхронного режима
     * @return string
     * @throws MemcacheClientException
     *
     * прочитать из мемкеша
     */

    private function __read($socket){
        $result = fread($socket, 1024);
        if (false === $result){
            throw new MemcacheClientException('Ошибка чтения из сокета!');
        }

        return trim($result);
    }//public function __read($socket){



    /**
     * @return bool
     * @throws MemcacheClientException
     *
     * создает синхронное соединение. возвращает true если оно было создано
     * и false если оно уже было
     */
    public function syncConnect(){
        if ($this->fp) return false;
        $errno  = '';
        $errstr = '';
        $this->fp = @stream_socket_client($this->address . ':' . $this->port, $errno, $errstr, $this->timeout);

        if (!$this->fp){
            $this->fp = null;
            throw new MemcacheClientException("Ошибка подключения с мемкешу ($errstr:$errno)!");
        }

        return true;
    }// public function connect()



    /**
     * @return bool
     * @throws MemcacheClientException
     * закрытие синхронного соединения
     */
    public function syncDisconnect(){
        if(!$this->fp) return true;

        $command = "quit\r\n";
        $this->__write($this->fp, $command);
        $this->fp = null;

        return true;
    }//public function disconnect()



    /**
     * @param $key
     * @return bool|mixed
     * @throws MemcacheClientException
     *
     * синхронное получение данных из мемкеша по ключу с десериалицией если надо
     * возвращает либо, данные либо false, например, если ключ протух
     */
    public function get($key){
        $this->syncConnect();

        $result = false;

        if(empty($key))
            throw new MemcacheClientException('Ключ не может быть пустым!');
        if(!is_scalar($key))
            throw new MemcacheClientException('Неверный формат ключа!');
        $key = (string) $key;

        $command = "get $key\r\n";
        $data = $this->__writeAndGetAnswer($this->fp, $command);

        if (preg_match('#VALUE (.*) (\d+) \d+\R(.*)\REND#', $data, $matches )){
            $result = $matches[3];
            if(self::SERIALIZED == $matches[2])
                $result  = unserialize($result);
        }else{
            $result = $data;
        }

        return $result;

    }//public function get($key){



    /**
     * @param $key
     * @param $value
     * @return bool
     * @throws MemcacheClientException
     *
     * синхронная запись данных в мемкеш. возвращает true если записали, false если не записали
     */
    public function set($key, $value){
        $this->syncConnect();

        if(empty($value))
            throw new MemcacheClientException('Нельзя сохранять пустое значение!');
        if(empty($key))
            throw new MemcacheClientException('Ключ не может быть пустым!');
        if(!is_scalar($key))
            throw new MemcacheClientException('Неверный формат ключа!');
        $key = (string) $key;

        $serialized = 0;
        if(is_scalar($value)) {
            $value = (string) $value;
        }elseif(is_array($value)){
            $value      = serialize($value);
            $serialized = self::SERIALIZED;
        }else{
            throw new MemcacheClientException('Неподдерживаемый формат данных!');
        }

        $size    = strlen($value);
        $command = "set $key $serialized 600 $size\r\n{$value}\r\n";
        $result = $this->__writeAndGetAnswer($this->fp, $command);

        return 'STORED' === $result;

    }//public function get($key){




    /**
     * @return resource
     * @throws MemcacheClientException
     *
     * асинхронное соединение с мемкешом. возвращает сокет, потом что с ним потом работать на итерациях
     */
    public function asyncConnect(){
        $errno  = '';
        $errstr = '';
        $fp = @stream_socket_client($this->address . ':' . $this->port, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT | STREAM_CLIENT_ASYNC_CONNECT);

        if (!$fp){
            throw new MemcacheClientException("Ошибка асинхронного подключения с мемкешу ($errstr:$errno)!");
        }
        if (!stream_set_blocking($fp, 0)) { // ASYNC для получения данных
            throw new MemcacheClientException("Ошибка утановки неблокирующего чтения!");
        }

        return $fp;
    }// public function connect()



    /**
     * @param $key
     * @param callable $callback
     * @return bool
     * @throws MemcacheClientException
     *
     * поставка задачи на чтение из мемкеша в очередь.
     * пихает в очередь элемент
     * 'key' мемкешовый ключ
     * 'callback' коллбэк который будет вызван по получению данных
     * 'stream'  хэндл сокета для асинхронного соединения
     * 'command'  команда на выполнение - пример "get test\r\n",
     *
     * номером в очереди является приведенный к инту сокет (int) $task['stream']
     */
    public function aget($key, callable $callback){

        if(empty($key))
            throw new MemcacheClientException('Ключ не может быть пустым!');
        if(!is_scalar($key))
            throw new MemcacheClientException('Неверный формат ключа!');
        $key = (string) $key;

        $task = [
            'key'      => $key,
            'callback' => $callback,
            'stream'   => $this->asyncConnect(),
            'command'  => "get $key\r\n",
        ];

        $this->tasks[(int) $task['stream']] = $task;

        return true;
    }//public function aget($key, callable $callback){}



    /**
     * @return int|bool
     * @throws MemcacheClientException
     *

     * возвращает количество элементов очереди обработанных на итерации, false если очередь пустая
     */
    public function process(){
        if(!$this->hasTasks()) return false;

        $read = $write = [];
        foreach ($this->getTasks() as $key => $task) {
            $read[]  = $task['stream'];
            $write[] = $task['stream'];
        }

        if (false === @stream_select($read, $write, $except = null, $this->timeout)){
            $message = error_get_last();
            //throw new MemcacheClientException("Ошибка stream_select \"{$message['message']}\"! ");
        }

        $this->processWriteTasks($write);
        $taskCount = $this->processReadTasks($read);

        return $taskCount;
    }//public function process(){}


    /**
     * @param array $read массив сокетов из которых можно читать
     * @return int количество элементов обработанных на итерации
     *
     * итерация очереди на чтение
     * читает из сокетов в которых есть данные. и все асинхронно, да.
     * вызывает коллбэки у элементов очереди для которых есть данные.
     *
     * возвращает количество элементов обработанных на итерации
     */
    protected function processReadTasks(array $read){
        $taskCount = 0;

        foreach ($read as $key => $readTask) {

            $task = $this->tasks[(int) $readTask];

            $data = $this->__read($task['stream']);

            $result = false;
            if (preg_match('#VALUE (.*) (\d+) \d+\R(.*)\REND#', $data, $matches )){
                $result = $matches[3];

                if(self::SERIALIZED == $matches[2])
                    $result = unserialize($result);
            }

            call_user_func_array($task['callback'], [$result]);

            $taskCount++;

            unset($this->tasks[(int) $readTask]);

        }// foreach ($read as $key => $readTask)

        return $taskCount;
    }// private function processReadTasks(array $read)



    /**
     * @param array $write массив сокетов в которых можно писать
     * @throws MemcacheClientException
     * @return int количество элементов обработанных на итерации
     *
     * итерация очереди на запись.
     * пихает в сокет команду из очереди
     *
     * возвращает количество элементов обработанных на итерации
     */
    protected function processWriteTasks(array $write){

        $taskCount = 0;
        foreach ($write as  $writeTask) {
            $task = $this->tasks[(int) $writeTask];
            $this->__write($task['stream'],$task['command']);

            $taskCount++;
        }//foreach ($write as  $writeTask)

        return $taskCount;
    }//private function processWriteTasks(array $write)


    /**
     * @return array
     */

    public function getTasks(){
        return $this->tasks;
    }
    /**
     * @return bool
     * есть ли еще задания в очереди
     */
    public function hasTasks(){

        return !empty($this->tasks);

    }//public function hasTasks(){

}//class MemcacheClient{

