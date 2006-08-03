<?php
/***************************************************************************
 *   Copyright (C) 2004-2006 by Dmitry E. Demidov                          *
 *   Dmitry.Demidov@noussoft.com                                           *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	class ErrorDrawer
	{
		const FONT_SIZE	= 4;
		
		public function __construct($turingImage)
		{
			$this->turingImage = $turingImage;
		}
	
		public function draw($string = 'ERROR!')
		{
			$y = round(
				$this->turingImage->getHeight() / 2
				- imagefontheight(ErrorDrawer::FONT_SIZE) / 2
			);
			
			$textWidth = imagefontwidth(ErrorDrawer::FONT_SIZE) * strlen($string);
			
			if ($this->turingImage->getWidth() > $textWidth)
				$x = round(($this->turingImage->getWidth() - $textWidth) / 2);
			else
				$x = 0;
				
			$color = $this->turingImage->getOneCharacterColor();
			
			imagestring(
				$this->turingImage->getImageId(),
				ErrorDrawer::FONT_SIZE,
				$x,
				$y,
				$string,
				$color
			);
			
			return $this;
		}
	}
?>