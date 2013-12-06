<?php

	final class PeclMemcacheCasTest extends TestCase
	{
		public function testPositive()
		{
			$cache = new PeclMemcached();
			
			$cas = null;
			$cache->set('cas_test_1', 42);
			$cache->getc('cas_test_1', $cas);
			
			$this->assertTrue($cache->cas('cas_test_1', '43', Cache::EXPIRES_MEDIUM, $cas));
		}
		
		public function testNegative()
		{
			$cache = new PeclMemcached();
			
			$cas = null;
			$cache->set('cas_test_2', 42);
			$cache->getc('cas_test_2', $cas);
			
			$anotherCache = new PeclMemcached();
			
			$anotherCache->set('cas_test_2', '666');
			
			$this->assertFalse($cache->cas('cas_test_1', '43', Cache::EXPIRES_MEDIUM, $cas));
		}
	}
?>