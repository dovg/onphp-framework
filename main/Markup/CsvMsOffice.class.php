<?php

	/**
	 * Optimal csv for MS Office
	 * @author Artur Baltaev <a.baltaev@co.wapstart.ru>
	 * @copyright Copyright (c) 2013, WapStart
	 */
	final class CsvMsOffice extends Csv
	{
		const SEPARATOR = "\t";
		const CODE_PAGE_TO = 'UTF-16';
		const CODE_PAGE_FROM = 'UTF-8';

		private $copePageTo = self::CODE_PAGE_TO;
		private $copePageFrom = self::CODE_PAGE_FROM;

		public function render($forceQuotes = true)
		{
			return iconv(
				$this->copePageFrom,
				$this->copePageTo,
				parent::render($forceQuotes)
			);
		}

		public function setCodePageTo($copePageTo)
		{
			$this->copePageTo = $copePageTo;

			return $this;
		}

		public function getCodePageTo()
		{
			return $this->copePageTo;
		}

		public function setCodePageFrom($copePageFrom)
		{
			$this->copePageFrom = $copePageFrom;

			return $this;
		}

		public function getCodePageFrom()
		{
			return $this->copePageFrom;
		}
	}
