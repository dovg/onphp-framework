<?php
/***************************************************************************
 *   Copyright (C) 2012 by Evgeniya Tekalin                                *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

<<<<<<< HEAD
	namespace Onphp;

=======
>>>>>>> 1.0-dovg
	abstract class AMQPPeclQueueConsumer extends AMQPDefaultConsumer
	{
		protected $cancel = false;
		protected $count = 0;
		protected $limit = 0;

		/**
		 * @param type $cancel
<<<<<<< HEAD
		 * @return \Onphp\AMQPPeclQueueConsumer
=======
		 * @return AMQPPeclQueueConsumer
>>>>>>> 1.0-dovg
		 */
		public function setCancel($cancel)
		{
			$this->cancel = ($cancel === true);
			return $this;
		}

		/**
		 * @param int $limit
<<<<<<< HEAD
		 * @return \Onphp\AMQPPeclQueueConsumer
=======
		 * @return AMQPPeclQueueConsumer
>>>>>>> 1.0-dovg
		 */
		public function setLimit($limit)
		{
			$this->limit = $limit;
			return $this;
		}

		/**
		 * @return int
		 */
		public function getCount()
		{
			return $this->count;
		}

<<<<<<< HEAD
		public function handlePeclDelivery(\AMQPEnvelope $delivery, \AMQPQueue $queue = null)
=======
		public function handlePeclDelivery(AMQPEnvelope $delivery, AMQPQueue $queue = null)
>>>>>>> 1.0-dovg
		{
			$this->count++;

			if ($this->limit && $this->count >= $this->limit)
				$this->setCancel(true);

			return $this->handleDelivery(
				AMQPPeclIncomingMessageAdapter::convert($delivery)
			);
		}

		public function handleDelivery(AMQPIncomingMessage $delivery)
		{
			if ($this->cancel) {
				$this->handleCancelOk('');
				return false;
			}
		}
	}
?>