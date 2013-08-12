<?php

	final class PrimitiveMultiListTest extends TestCase
	{
		protected static $list =
			array(
				'choice_1'	=> 'bluePill',
				'choice_2'	=> 'redPill',
				3			=> 42,
			);

		protected static $defaultScope = array('choice_2', 3);

		protected static $importScope =
			array(
				'multiChoice' => array('choice_1', 3)
			);

		public function testSetDefault() {
			$choicePrimitive = $this->create();
			$choicePrimitive->setDefault(self::$defaultScope);

			$getDefault = $choicePrimitive->getDefault();
			$getList = $choicePrimitive->getList();

			$this->assertEquals($getDefault, self::$defaultScope);
			$this->assertEquals($getList, self::$list);

			foreach ($getDefault as $index)
				$this->assertEquals($getList[$index], self::$list[$index]);
		}

		public function testImport() {
			$choicePrimitive = $this->create();
			$choicePrimitive->import(self::$importScope);

			$this->assertEquals($choicePrimitive->isImported(), true);
			$this->assertEquals(
				$choicePrimitive->getValue(),
				self::$importScope['multiChoice']
			);

			$choiced = $choicePrimitive->getChoiceValue();

			foreach ($choiced as $key => $value)
				$this->assertEquals(
					$choiced[$key],
					self::$list[$key]
				);
		}

		protected function create() {
			return Primitive::multiChoice('multiChoice')->
				setList(self::$list);
		}
	}
?>