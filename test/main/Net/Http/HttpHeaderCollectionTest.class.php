<?php
/***************************************************************************
 *   Copyright (C) 2013 by Nikita V. Konstantinov                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	class HttpHeaderCollectionTest extends TestCase
	{
		public function testSetter()
		{
			$collection =
				new HttpHeaderCollection(
					array('Content-Length' => 42)
				);

			return $collection;
		}

		/**
		 * @depends testSetter
		 */
		public function testAddition(HttpHeaderCollection $collection)
		{
			$collection->add('x-foo', 'bar');

			return $collection;
		}

		/**
		 * @depends testAddition
		 * @expectedException LogicException
		 */
		public function testFailedAddition(HttpHeaderCollection $collection)
		{
			$collection->add('x-fOO', 'bar');

			return $collection;
		}

		/**
		 * @depends testSetter
		 */
		public function testRemoving(HttpHeaderCollection $collection)
		{
			$collection->remove('x-foo');

			return $collection;
		}

		/**
		 * @depends testRemoving
		 * @expectedException MissingElementException
		 */
		public function testFailedRemoving(HttpHeaderCollection $collection)
		{
			$collection->remove('x-foo');

			return $collection;
		}

		/**
		 * @depends testRemoving
		 */
		public function testGetter(HttpHeaderCollection $collection)
		{
			$this->assertEquals(42, $collection->get('content-LeNgTh'));

			return $collection;
		}
	}
?>
