<?php

namespace LibraryTest\Traits;

/**
 * Class DoctrineTestCaseTrait
 * @package ApiTest\BaseTestCase
 */
trait DoctrineTestCaseTrait
{

    protected $query;

    /**
     * @param \Doctrine\ORM\Entity $entityClass
     * @param $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntityMock($entityClass, $id)
    {
        //FIXME It's not true 'mock' object, but it is better than entity 'setId' method implementation
        $entityMock = new $entityClass();

        $entityMockReflection = new \ReflectionClass($entityClass);
        $entityMockReflectionId = $entityMockReflection->getProperty('id');
        $entityMockReflectionId->setAccessible(true);
        $entityMockReflectionId->setValue($entityMock, $id);

        return $entityMock;
    }

    /**
     * @return \Doctrine\ORM\EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEntityManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getRepository',
                    'getConfiguration',
                    'getConnection',
                    'getClassMetadata',
                    'createQueryBuilder',
                    'close',
                )
            )
            ->getMock();
        $mock->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($this->getQueryBuilderMock()));

        $mock->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($this->getConfigurationMock()));
        $mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->getConnectionMock()));
        $mock->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($this->getClassMetadataMock()));

        return $mock;
    }

    /**
     * @return mixed
     */
    public function getQueryBuilderMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'select',
                    'from',
                    'join',
                    'where',
                    'setParameter',
                    'getQuery'
                )
            )
            ->getMock();

        $mock->expects($this->any())
            ->method('select')
            ->will($this->returnSelf());

        $mock->expects($this->any())
            ->method('from')
            ->will($this->returnSelf());

        $mock->expects($this->any())
            ->method('join')
            ->will($this->returnSelf());

        $mock->expects($this->any())
            ->method('where')
            ->will($this->returnSelf());

        $mock->expects($this->any())
            ->method('setParameter')
            ->will($this->returnSelf());

        $mock->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($this->getQueryMock()));

        return $mock;
    }

    public function getQueryMock()
    {
        if ($this->query) {
            return $this->query;
        }

        $mock = $this->getMockBuilder('\Doctrine\ORM\AbstractQuery')
            ->setMethods(array('getSingleResult'))
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->query = $mock;

        return $mock;
    }

    /**
     * @return mixed
     */
    public function getConfigurationMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\Configuration')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getDefaultQueryHints'
                )
            )
            ->getMock();
        $mock->expects($this->any())
            ->method('getDefaultQueryHints')
            ->will($this->returnValue(array()));


        return $mock;
    }

    /**
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getClassMetadataMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadataInfo')
            ->disableOriginalConstructor()
            ->setMethods(array('getTableName'))
            ->getMock();
        $mock->expects($this->any())
            ->method('getTableName')
            ->will($this->returnValue('{{tablename}}'));
        return $mock;
    }

    /**
     * @return \Doctrine\DBAL\Platforms\AbstractPlatform|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDatabasePlatformMock()
    {
        $mock = $this->getAbstractMock(
            'Doctrine\DBAL\Platforms\AbstractPlatform',
            array(
                'getName',
                'getTruncateTableSQL',
            )
        );
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('mysql'));
        $mock->expects($this->any())
            ->method('getTruncateTableSQL')
            ->with($this->anything())
            ->will($this->returnValue('#TRUNCATE {table}'));
        return $mock;
    }

    /**
     * @return \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getConnectionMock()
    {
        $mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'beginTransaction',
                    'commit',
                    'rollback',
                    'prepare',
                    'query',
                    'executeQuery',
                    'executeUpdate',
                    'getDatabasePlatform',
                )
            )
            ->getMock();
        $mock->expects($this->any())
            ->method('prepare')
            ->will($this->returnValue($this->getStatementMock()));
        $mock->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->getStatementMock()));
        $mock->expects($this->any())
            ->method('getDatabasePlatform')
            ->will($this->returnValue($this->getDatabasePlatformMock()));
        return $mock;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Statement|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getStatementMock()
    {
        $mock = $this->getAbstractMock(
            'Doctrine\DBAL\Driver\Statement', // In case you run PHPUnit <= 3.7, use 'Mocks\DoctrineDbalStatementInterface' instead.
            array(
                'bindValue',
                'execute',
                'rowCount',
                'fetchColumn',
            )
        );
        $mock->expects($this->any())
            ->method('fetchColumn')
            ->will($this->returnValue(1));
        return $mock;
    }

    /**
     * @param string $class The class name
     * @param array $methods The available methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAbstractMock($class, array $methods)
    {
        return $this->getMockForAbstractClass(
            $class,
            array(),
            '',
            true,
            true,
            true,
            $methods,
            false
        );
    }
}