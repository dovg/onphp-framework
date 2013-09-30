<?php
/***************************************************************************
 *   Copyright (C) 2006-2007 by Anton E. Lebedevich                        *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	/**
	 * @ingroup Flow
	**/
	class ModelAndView implements HttpResponse
	{
		/**
		 * @var Model
		**/
		private $model 	          = null;

		/**
		 * @var View
		**/
		private $view	          = null;

		/**
		 * @var HttpStatus
		**/
		private $status           = null;

		/**
		 * @var HttpHeaderCollection
		**/
		private $headerCollection = null;

		/**
		 * @var CookieCollection
		**/
		private $cookieCollection = null;

		/**
		 * @return ModelAndView
		**/
		public static function create(array $headers = array(), array $cookies = array())
		{
			return new self($headers, $cookies);
		}
		
		public function __construct(array $headers = array(), array $cookies = array())
		{
			$this->model = new Model();
			$this->status = new HttpStatus(HttpStatus::CODE_200);
			$this->headerCollection = new HttpHeaderCollection($headers);
			$this->cookieCollection = new CookieCollection();

			foreach ($cookies as $cookie)
				$this->cookieCollection->add($cookie);
		}
		
		/**
		 * @return Model
		**/
		public function getModel()
		{
			return $this->model;
		}
		
		/**
		 * @return ModelAndView
		**/
		public function setModel(Model $model)
		{
			$this->model = $model;
			
			return $this;
		}
		
		public function getView()
		{
			return $this->view;
		}
		
		/**
		 * @return ModelAndView
		**/
		public function setView($view)
		{
			Assert::isTrue(
				($view instanceof View)	|| is_string($view),
				'do not know, what to do with such view'
			);
			
			$this->view = $view;
			
			return $this;
		}
		
		public function viewIsRedirect()
		{
			return
				($this->view instanceof CleanRedirectView)
				|| (
					is_string($this->view)
					&& strpos($this->view, 'redirect') === 0
				);
		}
		
		public function viewIsNormal()
		{
			return (
				!$this->viewIsRedirect()
				&& $this->view !== View::ERROR_VIEW
			);
		}

		public function setStatus(HttpStatus $status)
		{
			$this->status = $status;

			return $this;
		}

		/**
		 * @return HttpStatus
		**/
		public function getStatus()
		{
			return $this->status;
		}

		/**
		 * @return HttpHeaderCollection
		 */
		public function getHeaderCollection()
		{
			return $this->headerCollection;
		}

		/**
		 * @return CookieCollection
		 */
		public function getCookieCollection()
		{
			return $this->cookieCollection;
		}

		public function getReasonPhrase()
		{
			return $this->status->getName();
		}

		public function getHeaders()
		{
			return $this->headerCollection->getAll();
		}

		public function hasHeader($name)
		{
			return $this->headerCollection->has($name);
		}

		public function getHeader($name)
		{
			return $this->headerCollection->get($name);
		}

		public function getBody()
		{
			if (!$this->view)
				return null;

			ob_start();

			$this->view->render($this->model);

			return ob_get_clean();
		}

		public function render()
		{
			$content = $this->getBody();
			$this->headerCollection->set('Content-Length', strlen($content));
			$this->sendHeaders();

			echo $content;

			return $this;
		}

		public function sendHeaders()
		{
			if (headers_sent($file, $line)) {
				throw new LogicException(
					sprintf('Headers are gone at %s:%d', $file, $line)
				);
			}

			header($this->status->toString());

			foreach ($this->headerCollection->getAll() as $name => $value)
				header($name.': '.$value);

			$this->cookieCollection->httpSetAll();

			return $this;
		}
	}
?>