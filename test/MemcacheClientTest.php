<?php
require_once(dirname(__FILE__) . '/../src/MemcacheClient.php');
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;


class MemcacheClientTest extends TestCase
{

    private $port  = 11211;
    private $address  = '127.0.0.1';
    /**
     * @var MemcacheClient
     */
    private $m = null;

    protected function setUp()
    {
        $this->m = new MemcacheClient($this->address, $this->port);

    }//protected function setUp()

    protected function tearDown()
    {
        $this->m = null;

    }//protected function tearDown()


    public function testProcess(){
        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['hasTasks','processWriteTasks', 'processReadTasks', 'getTasks'])
            ->setConstructorArgs(['127.0.0.1', '11211'])
            ->getMock();

        $object->expects($this->once())
            ->method('hasTasks')
            ->will($this->returnValue(true));

        $object->expects($this->once())
            ->method('processWriteTasks')
            ->will($this->returnValue(1));

        $object->expects($this->once())
            ->method('processReadTasks')
            ->will($this->returnValue(12));

        $object->expects($this->any())
            ->method('getTasks')
            ->will($this->returnValue(
                [
                'stream' => 1,
                ]
            ));


        try{
            $this->assertEquals(12, $object->process());
        }catch (\MemcacheClientException $e){//стрим селект пришлось бы выносить в приватный метод а их все равно не замокаешь
            $this->markTestSkipped();
        }

    }


    public function testAget(){
        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['asyncConnect'])
            ->disableOriginalConstructor()
            ->getMock();
        $object->expects($this->any())
            ->method('asyncConnect')
            ->will($this->returnValue(true));

        $this->assertSame(true, $object->aget('mykey', function($data){var_dump($data);}));

    }



    public function testSet(){

        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['__writeAndGetAnswer', 'syncConnect'])
            ->disableOriginalConstructor()
            ->getMock();

        $object->expects($this->any())
            ->method('syncConnect')
            ->will($this->returnValue(true));

        $object->expects( $this->exactly(2))
            ->method( '__writeAndGetAnswer' )
            ->will( $this->onConsecutiveCalls( 'STORED', 'NOT_STORED' ) );

        $this->assertSame(true,  $object->set('test', 'Hello World!'));
        $this->assertSame(false, $object->set('test', 'Hello World!'));

        $this->expectException(MemcacheClientException::class);

        $object->set(array(1), '1');
    }





    public function testGetExceptionsEmptyKey(){
        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['__writeAndGetAnswer', 'syncConnect'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(MemcacheClientException::class);
        $object->get('');
    }





    public function testGetExceptionsNotScalarKey()
    {
        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['__writeAndGetAnswer', 'syncConnect'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(MemcacheClientException::class);

        $object->get(array(1));
    }





    public function testGet(){

        $object = $this->getMockBuilder(MemcacheClient::class)
            ->setMethods(['__writeAndGetAnswer', 'syncConnect'])
            ->disableOriginalConstructor()
            ->getMock();

        $object->expects($this->any())
            ->method('syncConnect')
            ->will($this->returnValue(true));

        $object->expects( $this->exactly(2))
            ->method( '__writeAndGetAnswer' )
            ->will( $this->onConsecutiveCalls( 'Hello World!', "VALUE test 1 14\r\na:1:{i:0;i:1;}\r\nEND"  ) );


        $this->assertEquals('Hello World!', $object->get('mykey'));
        $this->assertEquals(array(1), $object->get('mykey'));
    }





    public function testCreate(){

        $reflectionClass = new ReflectionClass('MemcacheClient');

        $address  = $reflectionClass->getProperty('address');
        $port = $reflectionClass->getProperty('port');

        $address->setAccessible(true);
        $port->setAccessible(true);

        $this->assertEquals('11211', $port->getValue($this->m));
        $this->assertEquals('127.0.0.1', $address->getValue($this->m));
    }




    public function testSyncConnectFalse(){
        $this->m = new MemcacheClient($this->address, $this->port);

        $reflectionClass = new ReflectionClass('MemcacheClient');
        $fp = $reflectionClass->getProperty('fp');
        $fp->setAccessible(true);
        $fp->setValue($this->m, true);

        $this->assertSame(false, $this->m->syncConnect());

    }



    public function testSyncConnect(){
        try {
            $this->assertSame(true, $this->m->syncConnect());

        }catch (\MemcacheClientException $e){//не запущен мемкеш
            $this->markTestSkipped();
        }
    }



    public function testSyncConnectException(){
        $this->expectException(MemcacheClientException::class);

        $this->m = new MemcacheClient($this->address, 100500);
        $this->m->syncConnect();
    }



    public function testsyncDisconnect(){
        try {
            $this->assertEquals(true, $this->m->syncDisconnect());

            $this->m->syncConnect();
            $this->assertSame(true, $this->m->syncDisconnect());

        }catch (\MemcacheClientException $e){//не запущен мемкеш
            $this->markTestSkipped();
        }
    }


}//class MemcacheClientTest extends TestCase