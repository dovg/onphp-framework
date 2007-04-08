<?php
	/* $Id$ */
	
	class DAOTest extends TestTables
	{
		public function testSchema()
		{
			$this->create();
			$this->drop();
		}
		
		public function testData()
		{
			$this->create();
			
			foreach (DBTestPool::me()->getPool() as $connector => $db) {
				DBPool::me()->setDefault($db);
				$this->fill();
				
				$this->getSome(); // 41!
				Cache::me()->clean();
				$this->getSome();
			}
			
			$this->drop();
		}
		
		protected function fill()
		{
			$moscow =
				TestCity::create()->
				setName('Moscow');
			
			$piter =
				TestCity::create()->
				setName('Saint-Peterburg');
			
			$mysqler =
				TestUser::create()->
				setCity($moscow)->
				setCredentials(
					Credentials::create()->
					setNickname('mysqler')->
					setPassword(sha1('mysqler'))
				)->
				setLastLogin(
					Timestamp::create(time())
				)->
				setRegistered(
					Timestamp::create(time())->modify('-1 day')
				);
			
			$postgreser = clone $mysqler;
			
			$postgreser->
				setCredentials(
					Credentials::create()->
					setNickName('postgreser')->
					setPassword(sha1('postgreser'))
				)->
				setCity($piter);
			
			$piter = TestCity::dao()->add($piter);
			$moscow = TestCity::dao()->add($moscow);
			
			$this->assertEqual($piter->getId(), 1);
			$this->assertEqual($moscow->getId(), 2);
			
			$postgreser = TestUser::dao()->add($postgreser);
			$mysqler = TestUser::dao()->add($mysqler);
			
			$this->assertEqual($postgreser->getId(), 1);
			$this->assertEqual($mysqler->getId(), 2);
			
			Cache::me()->clean();
			
			$this->assertTrue(
				($postgreser == TestUser::dao()->getById(1))
			);
			
			$this->assertTrue(
				($mysqler == TestUser::dao()->getById(2))
			);
		}
		
		protected function getSome()
		{
			for ($i = 1; $i < 3; ++$i) {
				$this->assertTrue(
					TestUser::dao()->getByLogic(
						Expression::eq('city_id', $i)
					)
					== TestUser::dao()->getById($i)
				);
			}
			
			$this->assertEqual(
				count(TestUser::dao()->getPlainList()),
				count(TestCity::dao()->getPlainList())
			);
		}
	}
?>