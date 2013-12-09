<?php
/***************************************************************************
 *   Copyright (C) 2011 by Sergey S. Sergeev                               *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

	namespace Onphp;

	final class AMQPPeclChannel extends AMQPBaseChannel
	{
		const NIL = 'nil';
		const AMQP_NONE = AMQP_NOPARAM;

		protected $exchangeList = array();
		protected $queueList = array();
		protected $opened = false;


        /**
<<<<<<< HEAD
         * @var \AMQPChannel
=======
         * @var AMQPChannel
>>>>>>> 1.0-dovg
         */
        protected $link = null;

		/**
		 * @var \Onphp\AMQPConsumer
		**/
		protected $consumer = null;

        public function __construct($id, AMQPInterface $transport)
        {
            parent::__construct($id, $transport);
        }

		public function isOpen()
		{
			return $this->opened === true;
		}

		/**
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function open()
		{
			$this->opened = true;

			return $this;
		}

		/**
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function close()
		{
			$this->opened = false;

			return $this;
		}

		/**
<<<<<<< HEAD
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @param sting $deliveryTag
		 * @param bool $multiple
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @throws AMQPServerException|AMQPServerConnectionException
		 * @param sting $deliveryTag
		 * @param bool $multiple
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function basicAck($deliveryTag, $multiple = false)
		{
			try {
				$obj = $this->lookupQueue(self::NIL);
				$result = $obj->ack(
					$deliveryTag,
					$multiple === true
						? AMQP_MULTIPLE
						: self::AMQP_NONE
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not ack message"
			);

			return $this;
		}

		/**
		 * can't get $consumerTag
		 * @throws AMQPServerQueueException|AMQPServerConnectionException|WrongStateException
		 * @param string $consumerTag
<<<<<<< HEAD
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function basicCancel($consumerTag)
		{
			if (!$this->consumer instanceof AMQPConsumer)
				throw new WrongStateException();

			try {
				$obj = $this->lookupQueue($consumerTag);

				$result = $obj->cancel($consumerTag);

<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();
				
				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not cancel queue"
			);

			return $this;
		}

		/**
<<<<<<< HEAD
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function basicConsume($queue, $autoAck, AMQPConsumer $callback)
		{
			Assert::isInstance($callback, '\Onphp\AMQPPeclQueueConsumer');
=======
		 * @return AMQPChannelInterface
		**/
		public function basicConsume($queue, $autoAck, AMQPConsumer $callback)
		{
			Assert::isInstance($callback, 'AMQPPeclQueueConsumer');
>>>>>>> 1.0-dovg

			try {
				$this->consumer = $callback->
					setQueueName($queue)->
					setAutoAcknowledge($autoAck === true);

				$obj = $this->lookupQueue($queue);

				$this->consumer->handleConsumeOk(
					$this->consumer->getConsumerTag()
				);

				/**
				 * blocking function
				 */
				$obj->consume(
					array($callback, 'handlePeclDelivery'),
					$autoAck
						? AMQP_AUTOACK
						: self::AMQP_NONE
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException|ObjectNotFoundException
		 * @return \Onphp\AMQPIncomingMessage
		**/
		public function basicGet($queue, $autoAck = true)
		{
			try {
				$obj = $this->lookupQueue($queue);
				$message = $obj->get(
					($autoAck === true)
						? AMQP_AUTOACK
						: self::AMQP_NONE
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			if (!$message)
				throw new ObjectNotFoundException(
					"AMQP queue with name '{$queue}' is empty"
				);

			return AMQPPeclIncomingMessageAdapter::convert($message);
		}

		/**
		 * @throws AMQPServerExchangeException|AMQPServerConnectionException
		 * @param string $exchange
		 * @param string $routingKey
<<<<<<< HEAD
		 * @param \Onphp\AMQPOutgoingMessage $msg
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @param AMQPOutgoingMessage $msg
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function basicPublish(
			$exchange, $routingKey, AMQPOutgoingMessage $msg
		) {
			try {
				$obj = $this->lookupExchange($exchange);

				$result = $obj->publish(
					$msg->getBody(),
					$routingKey,
					$msg->getBitmask(new AMQPPeclOutgoingMessageBitmask()),
					$msg->getProperties()
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not publish to exchange"
			);

			return $this;
		}

		/**
		 * @param int $prefetchSize
		 * @param int $prefetchCount
<<<<<<< HEAD
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function basicQos($prefetchSize, $prefetchCount)
		{
			try {
				$result = $this->getChannelLink()->qos(
					$prefetchSize,
					$prefetchCount
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not publish to exchange"
			);

			return $this;
		}

		/**
<<<<<<< HEAD
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @param string $destinationName
		 * @param string $sourceName
		 * @param string $routingKey
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @throws AMQPServerException|AMQPServerConnectionException
		 * @param string $destinationName
		 * @param string $sourceName
		 * @param string $routingKey
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function exchangeBind($destinationName, $sourceName, $routingKey)
		{
			try {
				$obj = $this->lookupExchange($destinationName);
				
				$result = $obj->bind(
					$sourceName,
					$routingKey
				);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not bind exchange"
			);

			return $this;
		}

		public function exchangeUnbind($destinationName, $sourceName, $routingKey)
		{
			throw new UnimplementedFeatureException();
		}

		/**
<<<<<<< HEAD
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @param string $name
		 * @param \Onphp\AMQPExchangeConfig $conf
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @throws AMQPServerException|AMQPServerConnectionException
		 * @param string $name
		 * @param AMQPExchangeConfig $conf
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		public function exchangeDeclare($name, AMQPExchangeConfig $conf)
		{
			$this->checkConnection();

			if (!$conf->getType() instanceof AMQPExchangeType)
				throw new WrongArgumentException(
					"AMQP exchange type is not set"
				);

			try {
				$this->exchangeList[$name] =
<<<<<<< HEAD
					new \AMQPExchange($this->getChannelLink());
=======
					new AMQPExchange($this->getChannelLink());
>>>>>>> 1.0-dovg

				$obj = $this->exchangeList[$name];

                $obj->setName($name);
                $obj->setType($conf->getType()->getName());
                $obj->setFlags(
					$conf->getBitmask(new AMQPPeclExchangeBitmask())
				);
				$obj->setArguments($conf->getArguments());

<<<<<<< HEAD
				$result = $obj->declare();
			} catch (\Exception $e) {
=======
				$result = $obj->declareExchange();
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not declare exchange"
			);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function exchangeDelete(
			$name, $ifUnused = false
		) {
			$bitmask = self::AMQP_NONE;

			if ($ifUnused)
				$bitmask = $bitmask | AMQP_IFUNUSED;

			try {
				$obj = $this->lookupExchange($name);
				$result = $obj->delete($name, $bitmask);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not delete exchange"
			);

			$this->unsetExchange($name);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function queueBind($name, $exchange, $routingKey)
		{
			try {
				$obj = $this->lookupQueue($name);
				$result = $obj->bind($exchange, $routingKey);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not bind queue"
			);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return integer - the message count in queue
		**/
		public function queueDeclare($name, AMQPQueueConfig $conf)
		{
			$this->checkConnection();

			try {
				if (isset($this->queueList[$name]))
					unset($this->queueList[$name]);
				
				$this->queueList[$name] =
<<<<<<< HEAD
					new \AMQPQueue($this->getChannelLink());
=======
					new AMQPQueue($this->getChannelLink());
>>>>>>> 1.0-dovg

				$obj = $this->queueList[$name];
				$obj->setName($name);
				$obj->setFlags(
					$conf->getBitmask(new AMQPPeclQueueBitmask())
				);
				$obj->setArguments($conf->getArguments());
				
<<<<<<< HEAD
				$result = $obj->declare();
			} catch (\Exception $e) {
=======
				$result = $obj->declareQueue();
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				is_int($result),
				"Could not declare queue"
			);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function queueDelete($name)
		{
			try {
				$obj = $this->lookupQueue($name);
				$result = $obj->delete();
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not delete queue"
			);

			$this->unsetQueue($name);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function queuePurge($name)
		{
			try {
				$obj = $this->lookupQueue($name);
				$result = $obj->purge();
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();

				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not purge queue"
			);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerException|AMQPServerConnectionException
		 * @return \Onphp\AMQPChannelInterface
		**/
		public function queueUnbind($name, $exchange, $routingKey)
		{
			try {
				$obj = $this->lookupQueue($name);
				$result = $obj->unbind($exchange, $routingKey);
<<<<<<< HEAD
			} catch (\Exception $e) {
=======
			} catch (Exception $e) {
>>>>>>> 1.0-dovg
				$this->clearConnection();
				
				throw new AMQPServerException(
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}

			$this->checkCommandResult(
				$result,
				"Could not unbind queue"
			);

			return $this;
		}

		/**
<<<<<<< HEAD
		 * @throws \Onphp\AMQPServerConnectionException
		 * @return \AMQPExchange
=======
		 * @throws AMQPServerConnectionException
		 * @return AMQPExchange
>>>>>>> 1.0-dovg
		**/
		protected function lookupExchange($name)
		{
			$this->checkConnection();

			if (!isset($this->exchangeList[$name])) {
				$this->exchangeList[$name] =
<<<<<<< HEAD
					new \AMQPExchange($this->getChannelLink());
=======
					new AMQPExchange($this->getChannelLink());
>>>>>>> 1.0-dovg
				$this->exchangeList[$name]->setName($name);
			}

			return $this->exchangeList[$name];
		}

		/**
		 * @return \Onphp\AMQPPeclChannel
		**/
		protected function unsetExchange($name)
		{
			if (isset($this->exchangeList[$name]))
				unset($this->exchangeList[$name]);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerConnectionException
		 * @return \AMQPQueue
		**/
		protected function lookupQueue($name)
		{
			$this->checkConnection();

			if (!isset($this->queueList[$name])) {
<<<<<<< HEAD
				$this->queueList[$name] = new \AMQPQueue($this->getChannelLink());
=======
				$this->queueList[$name] = new AMQPQueue($this->getChannelLink());
>>>>>>> 1.0-dovg
				if ($name != self::NIL)
						$this->queueList[$name]->setName($name);
			}

			return $this->queueList[$name];
		}

		/**
		 * @return \Onphp\AMQPPeclChannel
		**/
		protected function unsetQueue($name)
		{
			if (isset($this->queueList[$name]))
				unset($this->queueList[$name]);

			return $this;
		}

		/**
		 * @throws \Onphp\AMQPServerConnectionException
		 * @return \Onphp\AMQPPeclChannel
		**/
		protected function checkCommandResult($boolean, $message)
		{
			if ($boolean !== true) {
				//link is not alive!!!
				$this->transport->getLink()->disconnect();
				throw new AMQPServerConnectionException($message);
			}

			return $this;
		}

		protected function clearConnection()
		{
			unset($this->link);
			$this->link = null;

			$this->exchangeList = array();
			$this->queueList = array();

			return $this;
		}

		protected function getChannelLink()
		{
			if (!$this->link) {
<<<<<<< HEAD
				$this->link = new \AMQPChannel(
=======
				$this->link = new AMQPChannel(
>>>>>>> 1.0-dovg
					$this->getTransport()->getLink()
				);
			}

			return $this->link;
		}

		/**
		 * we dont know if connection is boken until request is made
<<<<<<< HEAD
		 * @return \Onphp\AMQPPeclChannel
=======
		 * @return AMQPPeclChannel
>>>>>>> 1.0-dovg
		 */
		protected function checkConnection()
		{
			return $this;
		}
	}
?>