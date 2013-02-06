<?php
/***************************************************************************
 *   Copyright (C) 2013 by Alexander A. Zaytsev                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	/**
	 * Mapping array of non-quoted postgres data types like integer, float, etc
	 *
	 * @ingroup Helpers
	**/
	class PostgresArray implements Stringable, DialectString
	{
		protected $delim = ',';
		
		private $value = array();
		
		/**
		 * Create array by raw string including non-quoted postgres data types
		 *
		 * @return PostgresArray
		**/
		public static function create($string, $delim = ',')
		{
			return new self($string, $delim);
		}
		
		public function __construct($string, $delim = ',')
		{
			$this->delim = $delim;
			
			$self->toValue($string);
		}
		
		/**
		 * @return PostgresArray
		**/
		public function toValue($raw)
		{
			$this->value = $this->parseString($raw);
			
			return $this;
		}
		
		public function toString()
		{
			return $this->convertToString($this->value);
		}
		
		public function toDialectString(Dialect $dialect)
		{
			return $dialect->quoteValue($this->toString());
		}
		
		protected function convertRawToJson($rawData)
		{
			return strtr($rawData, '{}'.$this->delim, '[],');
		}
		
		protected function convertToString($val)
		{
			if (is_array($val)) {
				$stringList = array();
				
				foreach ($val as $subVal)
					$stringList[] = $this->convertToString($subVal);
				
				return '{'.implode($this->delim, $stringList).'}';
			}
			
			return $val;
		}
		
		private function parseString($rawData)
		{
			if (!$rawData)
				return null;
			
			$resultArray =
				json_decode(
					$this->convertRawToJson($rawData)
				);
			
			if (!$resultArray) {
				throw new WrongArgumentException(
					'json_decode() failed with code: '.json_last_error().'; '
					.'Raw data value was '.$rawData
				);
			}
			
			return $resultArray;
		}
	}
?>