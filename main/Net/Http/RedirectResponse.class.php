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

	/**
	 * @ingroup Http
	**/
	class RedirectResponse extends ModelAndView
	{
		private $url = null;

		public static function create(array $headers = array(), array $cookies = array())
		{
			throw new UnsupportedMethodException();
		}

		public function __construct($url, HttpStatus $status = null)
		{
			parent::__construct();

			$this->setUrl($url);
			$this->setStatus($status ?: new HttpStatus(HttpStatus::CODE_302));
		}

		public function setUrl($url)
		{
			$this->url = $url;
			$this->getHeaderCollection()->set('Location', $url);

			return $this;
		}

		public function setStatus(HttpStatus $status)
		{
			Assert::isTrue($status->isRedirection());

			return parent::setStatus($status);
		}
	}
?>
