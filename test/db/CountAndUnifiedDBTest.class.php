<?php
	namespace Onphp\Test;

	class CountAndUnifiedDBTest extends TestCaseDAO
	{
		public function testUnified()
		{
			foreach (DBTestPool::me()->iterator() as $db) {
				$this->getDBCreator()->fillDB();
				
				$this->unified();
				
				\Onphp\Cache::me()->clean();
				
				TestUser::dao()->dropById(1);
				try {
					TestUser::dao()->dropByIds(array(1, 2));
					$this->fail();
				} catch (\Onphp\WrongStateException $e) {
					// ok
				}
			}
		}
		
		public function testCount()
		{
			foreach (DBTestPool::me()->iterator() as $db) {
				$this->getDBCreator()->fillDB();
				
				$count = TestUser::dao()->getTotalCount();
				
				$this->assertGreaterThan(1, $count);
				
				$city =
					TestCity::create()->
					setId(1);
				
				$newUser =
					TestUser::create()->
					setCity($city)->
					setCredentials(
						Credentials::create()->
						setNickname('newuser')->
						setPassword(sha1('newuser'))
					)->
					setLastLogin(
						\Onphp\Timestamp::create(time())
					)->
					setRegistered(
						\Onphp\Timestamp::create(time())
					);
				
				TestUser::dao()->add($newUser);
				
				$newCount = TestUser::dao()->getTotalCount();
				
				$this->assertEquals($count + 1, $newCount);
			}
		}
		
		public function unified()
		{
			$user = TestUser::dao()->getById(1);
			
			$encapsulant = TestEncapsulant::dao()->getPlainList();
			
			$collectionDao = $user->getEncapsulants();
			
			$collectionDao->fetch()->setList($encapsulant);
			
			$collectionDao->save();
			
			unset($collectionDao);
			
			// fetch
			$encapsulantsList = $user->getEncapsulants()->getList();
			
			$piter = TestCity::dao()->getById(1);
			$moscow = TestCity::dao()->getById(2);
			
			for ($i = 0; $i < 10; $i++) {
				$this->assertEquals($encapsulantsList[$i]->getId(), $i + 1);
				$this->assertEquals($encapsulantsList[$i]->getName(), $i);
				
				$cityList = $encapsulantsList[$i]->getCities()->getList();
				
				$this->assertEquals($cityList[0], $piter);
				$this->assertEquals($cityList[1], $moscow);
			}
			
			unset($encapsulantsList);
			
			// lazy fetch
			$encapsulantsList = $user->getEncapsulants(true)->getList();
			
			for ($i = 1; $i < 11; $i++)
				$this->assertEquals($encapsulantsList[$i], $i);
			
			// count
			$user->getEncapsulants()->clean();
			
			$this->assertEquals($user->getEncapsulants()->getCount(), 10);
			
			$criteria = \Onphp\Criteria::create(TestEncapsulant::dao())->
				add(
					\Onphp\Expression::in(
						'cities.id',
						array($piter->getId(), $moscow->getId())
					)
				);
			
			$user->getEncapsulants()->setCriteria($criteria);
			
			$this->assertEquals($user->getEncapsulants()->getCount(), 20);
			
			// distinct count
			$user->getEncapsulants()->clean();
			
			$user->getEncapsulants()->setCriteria(
				$criteria->
					setDistinct(true)
			);
			
			if (\Onphp\DBPool::me()->getLink() instanceof \Onphp\SQLite)
				// TODO: sqlite does not support such queries yet
				return null;
			
			$this->assertEquals($user->getEncapsulants()->getCount(), 10);
		}
	}
?>