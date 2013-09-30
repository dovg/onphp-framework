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
	class RawResponse extends ModelAndView
	{
		public static function create(array $headers = array(), array $cookies = array())
		{
			throw new UnsupportedMethodException();
		}

		public function __construct(
			$content = '', HttpStatus $status = null, array $headers = array()
		)
		{
			parent::__construct($headers);

			if ($status)
				$this->setStatus($status);
			
			$this->setView(new RawView($content));
		}
	}
?>
