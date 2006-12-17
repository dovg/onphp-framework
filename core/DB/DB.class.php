<?php
/***************************************************************************
 *   Copyright (C) 2004-2006 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * DB-connector's implementation basis.
	 * 
	 * @ingroup DB
	**/
	abstract class DB
	{
		const FULL_TEXT_AND		= 1;
		const FULL_TEXT_OR		= 2;

		protected $link			= null;

		protected $persistent	= false;
		
		/**
		 * flag to indicate whether we're in transaction
		**/
		private $transaction	= false;
		
		private $queue			= array();
		private $toQueue		= false;
		
		abstract public function connect(
			$user, $pass, $host,
			$base = null, $persistent = false
		);
		abstract public function disconnect();

		abstract public function queryRaw($queryString);

		abstract public function queryRow(Query $query);
		abstract public function queryObjectRow(Query $query, GenericDAO $dao);

		abstract public function querySet(Query $query);
		abstract public function queryObjectSet(Query $query, GenericDAO $dao);

		abstract public function queryColumn(Query $query);
		abstract public function queryCount(Query $query);
		
		abstract public function getTableInfo($table);

		public function __destruct()
		{
			if ($this->isConnected()) {
				if ($this->transaction)
					$this->rollback();

				if (!$this->persistent)
					$this->disconnect();
			}
		}
		
		public static function getDialect()
		{
			throw new UnimplementedFeatureException('implement me, please');
		}
		
		/**
		 * transaction handling
		 * @deprecated by Transaction class
		**/
		
		/**
		 * @return DB
		**/
		public function begin($level = null, $mode = null)
		{
			$begin = 'start transaction';
			
			if ($level && $level instanceof IsolationLevel)
				$begin .= ' '.$level->toString();
			
			if ($mode && $mode instanceof AccessMode)
				$begin .= ' '.$mode->toString();

			if ($this->toQueue)
				$this->queue[] = $begin;
			else
				$this->queryRaw("{$begin};\n");
			
			$this->transaction = true;
			
			return $this;
		}
		
		/**
		 * @return DB
		**/
		public function commit()
		{
			if ($this->toQueue)
				$this->queue[] = "commit;";
			else
				$this->queryRaw("commit;\n");
			
			$this->transaction = false;
			
			return $this;
		}
		
		/**
		 * @return DB
		**/
		public function rollback()
		{
			if ($this->toQueue)
				$this->queue[] = "rollback;";
			else
				$this->queryRaw("rollback;\n");
			
			$this->transaction = false;
			
			return $this;
		}
		
		public function inTransaction()
		{
			return $this->transaction;
		}
		
		/**
		 * queue handling
		 * @deprecated by Queue class
		**/

		/**
		 * @return DB
		**/
		public function queueStart()
		{
			if ($this->hasQueue())
				$this->toQueue = true;
			
			return $this;
		}
		
		/**
		 * @return DB
		**/
		public function queueStop()
		{
			$this->toQueue = false;
			
			return $this;
		}
		
		/**
		 * @return DB
		**/
		public function queueDrop()
		{
			$this->queue = array();
			
			return $this;
		}
		
		/**
		 * @return DB
		**/
		public function queueFlush()
		{
			if ($this->queue)
				$this->queryRaw(
					implode(";\n", $this->queue)
				);
			
			$this->toQueue = false;
			
			return $this->queueDrop();
		}
		
		/**
		 * base queries
		**/
		
		public function query(Query $query)
		{
			return $this->queryRaw($query->toDialectString($this->getDialect()));
		}

		public function queryNull(Query $query)
		{
			if ($query instanceof SelectQuery)
				throw new WrongArgumentException(
					'only non-select queries supported'
				);
			
			if ($this->toQueue) {
				$this->queue[] = $query->toDialectString($this->getDialect());
				return true;
			} else
				return $this->query($query);
		}
		
		public function isConnected()
		{
			return is_resource($this->link);
		}
		
		public function hasSequences()
		{
			return false;
		}
		
		public function hasQueue()
		{
			return true;
		}

		public function isPersistent()
		{
			return $this->persistent;
		}
	}
?>