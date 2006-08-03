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

	class CellBackgroundDrawer extends BackgroundDrawer
	{
		private $step = null;
	
		public function __construct($step)
		{
			$this->step = $step;
		}

		public function draw()
		{
			$x = mt_rand(-$this->step, $this->step);
			$width = $this->getTuringImage()->getWidth();
			
			while ($x < $width) {
				$color = $this->getColor();
				$colorId = $this->getTuringImage()->getColorIdentifier($color);

				imageline(
					$this->getTuringImage()->getImageId(),
					$x,
					0,
					$x,
					$this->getTuringImage()->getHeight(),
					$colorId
				);
				
				$x += $this->step;
			}
	
			$y = mt_rand(-$this->step, $this->step);
			$height = $this->getTuringImage()->getHeight();
			
			while ($y < $height) {
				$color = $this->getColor();
				$colorId = $this->getTuringImage()->getColorIdentifier($color);

				imageline(
					$this->getTuringImage()->getImageId(),
					0,
					$y,
					$this->getTuringImage()->getWidth(),
					$y,
					$colorId
				);
				
				$y += $this->step;
			}
			
			return $this;
		}
	}
?>