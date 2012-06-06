<?php
	final class DBMock extends DB
	{
		public function getHostName()
		{
			return $this->hostname;
		}
		
		public function connect()
		{
			return $this;
		}

		public function disconnect()
		{
			return $this;
		}

		public function getTableInfo($table)
		{
			return null;
		}

		public function queryRaw($queryString)
		{
			throw new DatabaseException($this->hostname);
		}

		public function queryRow(Query $query)
		{
			throw new DatabaseException($this->hostname);
		}

		public function querySet(Query $query)
		{
			throw new DatabaseException($this->hostname);
		}

		public function queryColumn(Query $query)
		{
			throw new DatabaseException($this->hostname);
		}

		public function queryCount(Query $query)
		{
			throw new DatabaseException($this->hostname);
		}

		public function setDbEncoding()
		{
			throw new DatabaseException($this->hostname);
		}
	}

	final class SlavePoolTest
	{
		public function slavePoolDropLinkTest()
		{
			$slavePool =
				SlaveDBPool::create()->
					addLink('test1', DBMock::spawn('test', '', '', 'test1'), 10)->
					addLink('test2', DBMock::spawn('test', '', '', 'test2'), 1);
			
			$slavePool->dropLink('test1');
			
			$link = $slavePool->getSlaveLink();
			
			$this->assertNotNull($link);
			$this->assertEqual('test2', $link->getHostName());
			
			$slavePool->dropLink('test2');
			
			$this->assertNull($slavePool->getSlaveLink());
		}
	}