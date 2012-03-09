<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Unglaub 2012
 * @author     Leo Unglaub <leo@leo-unglaub.net>
 * @package    short_url_googl
 * @license    LGPL
 */


/**
 * Class ShortUrlProviderGooGl
 * Contain methods to shorten and expand url's
 * TODO: add support for Google OAuth2.0
 */
class ShortUrlProviderGooGl extends ShortUrlAbstract
{
	/**
	 * @see ShortUrlAbstract
	 */
	public $intExpires = 30758400;


	/**
	 * @see ShortUrlAbstract
	 */
	public function getShortUrl($strLongUrl)
	{
		$arrData = array
		(
			'longUrl' => $strLongUrl
		);

		// request the short url
		$objRequest = new Request();

		$objRequest->setHeader('Content-Type', 'application/json');
		$objRequest->data = json_encode($arrData);
		$objRequest->method = 'POST';

		$objRequest->send('https://www.googleapis.com/urlshortener/v1/url');


		// check if everything went right
		if (!$objRequest->hasError())
		{
			$objResponse = json_decode($objRequest->response);
			return $objResponse->id;
		}

		// something went wrong, do some error handling
		$this->handleErrors($objRequest);
	}


	/**
	 * @see ShortUrlAbstract
	 */
	public function getLongUrl($strShortUrl)
	{
		// request the short url
		$objRequest = new Request();
		$objRequest->send('https://www.googleapis.com/urlshortener/v1/url?shortUrl=' . rawurldecode($strShortUrl));


		// check if everything went right
		if (!$objRequest->hasError())
		{
			$objResponse = json_decode($objRequest->response);
			return $objResponse->longUrl;
		}

		// something went wrong, do some error handling
		$this->handleErrors($objRequest);
	}
}

?>