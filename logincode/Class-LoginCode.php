<?php

namespace Sinergi\BrowserDetector;

class Browser
{
	const VIVALDI = 'Vivaldi';
	const OPERA = 'Opera';
	const OPERA_MINI = 'Opera Mini';
	const WEBTV = 'WebTV';
	const IE = 'Internet Explorer';
	const POCKET_IE = 'Pocket Internet Explorer';
	const KONQUEROR = 'Konqueror';
	const ICAB = 'iCab';
	const OMNIWEB = 'OmniWeb';
	const FIREBIRD = 'Firebird';
	const FIREFOX = 'Firefox';
	const SEAMONKEY = 'SeaMonkey';
	const ICEWEASEL = 'Iceweasel';
	const SHIRETOKO = 'Shiretoko';
	const MOZILLA = 'Mozilla';
	const AMAYA = 'Amaya';
	const LYNX = 'Lynx';
	const WKHTMLTOPDF = 'wkhtmltopdf';
	const SAFARI = 'Safari';
	const SAMSUNG_BROWSER = 'SamsungBrowser';
	const CHROME = 'Chrome';
	const NAVIGATOR = 'Navigator';
	const GOOGLEBOT = 'GoogleBot';
	const SLURP = 'Yahoo! Slurp';
	const W3CVALIDATOR = 'W3C Validator';
	const BLACKBERRY = 'BlackBerry';
	const ICECAT = 'IceCat';
	const NOKIA_S60 = 'Nokia S60 OSS Browser';
	const NOKIA = 'Nokia Browser';
	const MSN = 'MSN Browser';
	const MSNBOT = 'MSN Bot';
	const NETSCAPE_NAVIGATOR = 'Netscape Navigator';
	const GALEON = 'Galeon';
	const NETPOSITIVE = 'NetPositive';
	const PHOENIX = 'Phoenix';
	const GSA = 'Google Search Appliance';
	const YANDEX = 'Yandex';
	const EDGE = 'Edge';
	const DRAGON = 'Dragon';

	protected static $browsersList = [
		// well-known, well-used
		// Special Notes:
		// (1) Opera must be checked before FireFox due to the odd
		//     user agents used in some older versions of Opera
		// (2) WebTV is strapped onto Internet Explorer so we must
		//     check for WebTV before IE
		// (3) Because of Internet Explorer 11 using
		//     "Mozilla/5.0 ([...] Trident/7.0; rv:11.0) like Gecko"
		//     as user agent, tests for IE must be run before any
		//     tests checking for "Mozilla"
		// (4) (deprecated) Galeon is based on Firefox and needs to be
		//     tested before Firefox is tested
		// (5) OmniWeb is based on Safari so OmniWeb check must occur
		//     before Safari
		// (6) Netscape 9+ is based on Firefox so Netscape checks
		//     before FireFox are necessary
		// (7) Microsoft Edge must be checked before Chrome and Safari
		// (7) Vivaldi must be checked before Chrome
		'WebTv',
		'InternetExplorer',
		'Edge',
		'Opera',
		'Vivaldi',
		'Dragon',
		'Galeon',
		'NetscapeNavigator9Plus',
		'SeaMonkey',
		'Firefox',
		'Yandex',
		'Samsung',
		'Chrome',
		'OmniWeb',
		// common mobile
		'Android',
		'BlackBerry',
		'Nokia',
		'Gsa',
		// wkhtmltopdf before Safari
		'Wkhtmltopdf',
		// WebKit base check (post mobile and others)
		'Safari',
		// everyone else
		'NetPositive',
		'Firebird',
		'Konqueror',
		'Icab',
		'Phoenix',
		'Amaya',
		'Lynx',
		'Shiretoko',
		'IceCat',
		'Iceweasel',
		'Mozilla', /* Mozilla is such an open standard that you must check it last */
	];

	private string $name = '';
	private string $version = '';

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	public function __construct(string $userAgent = null)
	{
		$userAgent = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? null;

		if ($userAgent !== null)
			foreach (self::$browsersList as $browserName)
				if (call_user_func([$this, 'detect' . $browserName], $userAgent))
					break;
	}

	/**
	 * Determine if the user is using a BlackBerry.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectBlackBerry(string $userAgent): bool
	{
		if (stripos($userAgent, 'blackberry') !== false)
		{
			if (stripos($userAgent, 'Version/') !== false)
			{
				$aresult = explode('Version/', $userAgent);
				if (isset($aresult[1]))
				{
					$aversion = explode(' ', $aresult[1]);
					$this->version = $aversion[0];
				}
			}
			else
			{
				$aresult = explode('/', stristr($userAgent, 'BlackBerry'));
				if (isset($aresult[1]))
				{
					$aversion = explode(' ', $aresult[1]);
					$this->version = $aversion[0];
				}
			}
			$this->name = self::BLACKBERRY;
		}
		elseif (stripos($userAgent, 'BB10') !== false)
		{
			$aresult = explode('Version/10.', $userAgent);
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = '10.' . $aversion[0];
			}
			$this->name = self::BLACKBERRY;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Internet Explorer.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectInternetExplorer(string $userAgent): bool
	{
		// Test for v1 - v1.5 IE
		if (stripos($userAgent, 'microsoft internet explorer') !== false)
		{
			$this->name = self::IE;
			$this->version = '1.0';
			$aresult = stristr($userAgent, '/');
			if (preg_match('/308|425|426|474|0b1/i', $aresult))
				$this->version = '1.5';
		} // Test for versions > 1.5 and < 11 and some cases of 11
		else
		{
			if (stripos($userAgent, 'msie') !== false && stripos($userAgent, 'opera') === false)
			{
				// See if the browser is the odd MSN Explorer
				if (stripos($userAgent, 'msnb') !== false)
				{
					$aresult = explode(' ', stristr(str_replace(';', '; ', $userAgent), 'MSN'));
					$this->name = self::MSN;
					if (isset($aresult[1]))
						$this->version = str_replace(['(', '', ';'], '', $aresult[1]);

					return true;
				}
				$aresult = explode(' ', stristr(str_replace(';', '; ', $userAgent), 'msie'));
				$this->name = self::IE;
				if (isset($aresult[1]))
					$this->version = str_replace(['(', '', ';'], '', $aresult[1]);
				// See https://msdn.microsoft.com/en-us/library/ie/hh869301%28v=vs.85%29.aspx
				// Might be 11, anyway !
				if (stripos($userAgent, 'trident') !== false)
				{
					preg_match('/rv:(\d+\.\d+)/', $userAgent, $matches);
					if (isset($matches[1]))
						$this->version = $matches[1];

					// At this point in the method, we know the MSIE and Trident
					// strings are present in the $userAgent. If we're in
					// compatibility mode, we need to determine the true version.
					// If the MSIE version is 7.0, we can look at the Trident
					// version to *approximate* the true IE version. If we don't
					// find a matching pair, ( e.g. MSIE 7.0 && Trident/7.0 )
					// we're *not* in compatibility mode and the browser really
					// is version 7.0.
					if (stripos($userAgent, 'MSIE 7.0;'))
					{
						if (stripos($userAgent, 'Trident/7.0;'))
						{
							// IE11 in compatibility mode
							$this->version = '11.0';
						}
						elseif (stripos($userAgent, 'Trident/6.0;'))
						{
							// IE10 in compatibility mode
							$this->version = '10.0';
						}
						elseif (stripos($userAgent, 'Trident/5.0;'))
						{
							// IE9 in compatibility mode
							$this->version = '9.0';
						}
						elseif (stripos($userAgent, 'Trident/4.0;'))
						{
							// IE8 in compatibility mode
							$this->version = '8.0';
						}
					}
				}

				return true;
			} // Test for versions >= 11
			else
			{
				if (stripos($userAgent, 'trident') !== false)
				{
					$this->name = self::IE;

					preg_match('/rv:(\d+\.\d+)/', $userAgent, $matches);
					if (isset($matches[1]))
					{
						$this->version = $matches[1];

						return true;
					}
					else
						return $this->name != '';
				} // Test for Pocket IE
				else
				{
					if (stripos($userAgent, 'mspie') !== false ||
						stripos(
							$userAgent,
							'pocket'
						) !== false
					)
					{
						$aresult = explode(' ', stristr($userAgent, 'mspie'));
						$this->name = self::POCKET_IE;

						if (stripos($userAgent, 'mspie') !== false)
						{
							if (isset($aresult[1]))
								$this->version = $aresult[1];
						}
						else
						{
							$aversion = explode('/', $userAgent);
							if (isset($aversion[1]))
								$this->version = $aversion[1];
						}

						return true;
					}
				}
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Opera.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOpera(string $userAgent): bool
	{
		if (stripos($userAgent, 'opera mini') !== false)
		{
			$resultant = stristr($userAgent, 'opera mini');
			if (preg_match('/\//', $resultant))
			{
				$aresult = explode('/', $resultant);
				if (isset($aresult[1]))
				{
					$aversion = explode(' ', $aresult[1]);
					$this->version = $aversion[0];
				}
			}
			else
			{
				$aversion = explode(' ', stristr($resultant, 'opera mini'));
				if (isset($aversion[1]))
					$this->version = $aversion[1];
			}
			$this->name = self::OPERA_MINI;
		}
		elseif (stripos($userAgent, 'OPiOS') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'OPiOS'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::OPERA_MINI;
		}
		elseif (stripos($userAgent, 'opera') !== false)
		{
			$resultant = stristr($userAgent, 'opera');
			if (preg_match('/Version\/(1[0-2].*)$/', $resultant, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
			}
			elseif (preg_match('/\//', $resultant))
			{
				$aresult = explode('/', str_replace('(', ' ', $resultant));
				if (isset($aresult[1]))
				{
					$aversion = explode(' ', $aresult[1]);
					$this->version = $aversion[0];
				}
			}
			else
			{
				$aversion = explode(' ', stristr($resultant, 'opera'));
				$this->version = $aversion[1] ?? '';
			}
			$this->name = self::OPERA;
		}
		elseif (stripos($userAgent, ' OPR/') !== false)
		{
			$this->name = self::OPERA;
			if (preg_match('/OPR\/([\d\.]*)/', $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Samsung.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSamsung(string $userAgent): bool
	{
		if (stripos($userAgent, 'SamsungBrowser') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'SamsungBrowser'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::SAMSUNG_BROWSER;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Chrome.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectChrome(string $userAgent): bool
	{
		if (stripos($userAgent, 'Chrome') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Chrome'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::CHROME;
		}
		elseif (stripos($userAgent, 'CriOS') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'CriOS'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::CHROME;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Vivaldi.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectVivaldi(string $userAgent): bool
	{
		if (stripos($userAgent, 'Vivaldi') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Vivaldi'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::VIVALDI;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Microsoft Edge.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectEdge(string $userAgent): bool
	{
		if (stripos($userAgent, 'Edge') !== false)
		{
			$version = explode('Edge/', $userAgent);
			if (isset($version[1]))
				$this->version = (float) $version[1];
			$this->name = self::EDGE;
		}
		elseif (stripos($userAgent, 'Edg') !== false)
		{
			$version = explode('Edg/', $userAgent);
			if (isset($version[1]))
				$this->version = trim($version[1]);
			$this->name = self::EDGE;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Google Search Appliance.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectGsa(string $userAgent): bool
	{
		if (stripos($userAgent, 'GSA') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'GSA'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::GSA;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is WebTv.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectWebTv(string $userAgent): bool
	{
		if (stripos($userAgent, 'webtv') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'webtv'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::WEBTV;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is NetPositive.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectNetPositive(string $userAgent): bool
	{
		if (stripos($userAgent, 'NetPositive') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'NetPositive'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = str_replace(['(', '', ';'], '', $aversion[0]);
			}
			$this->name = self::NETPOSITIVE;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Galeon.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectGaleon(string $userAgent): bool
	{
		if (stripos($userAgent, 'galeon') !== false)
		{
			$aresult = explode(' ', stristr($userAgent, 'galeon'));
			$aversion = explode('/', $aresult[0]);
			if (isset($aversion[1]))
				$this->version = $aversion[1];
			$this->name = self::GALEON;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Konqueror.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectKonqueror(string $userAgent): bool
	{
		if (stripos($userAgent, 'Konqueror') !== false)
		{
			$aresult = explode(' ', stristr($userAgent, 'Konqueror'));
			$aversion = explode('/', $aresult[0]);
			if (isset($aversion[1]))
				$this->version = $aversion[1];
			$this->name = self::KONQUEROR;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is iCab.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIcab(string $userAgent): bool
	{
		if (stripos($userAgent, 'icab') !== false)
		{
			$aversion = explode(' ', stristr(str_replace('/', ' ', $userAgent), 'icab'));
			if (isset($aversion[1]))
				$this->version = $aversion[1];
			$this->name = self::ICAB;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is OmniWeb.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOmniWeb(string $userAgent): bool
	{
		if (stripos($userAgent, 'omniweb') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'omniweb'));
			$aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : '');
			$this->version = $aversion[0];
			$this->name = self::OMNIWEB;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Phoenix.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectPhoenix(string $userAgent): bool
	{
		if (stripos($userAgent, 'Phoenix') !== false)
		{
			$aversion = explode('/', stristr($userAgent, 'Phoenix'));
			if (isset($aversion[1]))
				$this->version = $aversion[1];
			$this->name = self::PHOENIX;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Firebird.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectFirebird(string $userAgent): bool
	{
		if (stripos($userAgent, 'Firebird') !== false)
		{
			$aversion = explode('/', stristr($userAgent, 'Firebird'));
			if (isset($aversion[1]))
				$this->version = $aversion[1];
			$this->name = self::FIREBIRD;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Netscape Navigator 9+.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectNetscapeNavigator9Plus(string $userAgent): bool
	{
		if (stripos($userAgent, 'Firefox') !== false &&
			preg_match('/Navigator\/([^ ]*)/i', $userAgent, $matches)
		)
		{
			if (isset($matches[1]))
				$this->version = $matches[1];
			$this->name = self::NETSCAPE_NAVIGATOR;
		}
		elseif (stripos($userAgent, 'Firefox') === false &&
			preg_match('/Netscape6?\/([^ ]*)/i', $userAgent, $matches)
		)
		{
			if (isset($matches[1]))
				$this->version = $matches[1];
			$this->name = self::NETSCAPE_NAVIGATOR;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Shiretoko.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectShiretoko(string $userAgent): bool
	{
		if (stripos($userAgent, 'Mozilla') !== false &&
			preg_match('/Shiretoko\/([^ ]*)/i', $userAgent, $matches)
		)
		{
			if (isset($matches[1]))
				$this->version = $matches[1];
			$this->name = self::SHIRETOKO;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Ice Cat.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIceCat(string $userAgent): bool
	{
		if (stripos($userAgent, 'Mozilla') !== false &&
			preg_match('/IceCat\/([^ ]*)/i', $userAgent, $matches)
		)
		{
			if (isset($matches[1]))
				$this->version = $matches[1];
			$this->name = self::ICECAT;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Nokia.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectNokia(string $userAgent): bool
	{
		if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $userAgent, $matches))
		{
			$this->version = $matches[2];
			if (stripos($userAgent, 'Series60') !== false ||
				strpos($userAgent, 'S60') !== false
			)
				$this->name = self::NOKIA_S60;
			else
				$this->name = self::NOKIA;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Firefox.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectFirefox(string $userAgent): bool
	{
		if (stripos($userAgent, 'safari') === false)
		{
			if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
				$this->name = self::FIREFOX;

				return true;
			}
			elseif (preg_match('/Firefox$/i', $userAgent, $matches))
			{
				$this->version = '';
				$this->name = self::FIREFOX;

				return true;
			}
		}
		elseif (stripos($userAgent, 'FxiOS') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'FxiOS'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::FIREFOX;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is SeaMonkey.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSeaMonkey(string $userAgent): bool
	{
		if (stripos($userAgent, 'safari') === false)
		{
			if (preg_match("/SeaMonkey[\/ \(]([^ ;\)]+)/i", $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
				$this->name = self::SEAMONKEY;

				return true;
			}
			elseif (preg_match('/SeaMonkey$/i', $userAgent, $matches))
			{
				$this->version = '';
				$this->name = self::SEAMONKEY;

				return true;
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Iceweasel.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIceweasel(string $userAgent): bool
	{
		if (stripos($userAgent, 'Iceweasel') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Iceweasel'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::ICEWEASEL;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Mozilla.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectMozilla(string $userAgent): bool
	{
		if (stripos($userAgent, 'mozilla') !== false &&
			preg_match('/rv:[0-9].[0-9][a-b]?/i', $userAgent) &&
			stripos($userAgent, 'netscape') === false
		)
		{
			$aversion = explode(' ', stristr($userAgent, 'rv:'));
			preg_match('/rv:[0-9].[0-9][a-b]?/i', $userAgent, $aversion);
			$this->version = str_replace('rv:', '', $aversion[0]);
			$this->name = self::MOZILLA;
		}
		elseif (stripos($userAgent, 'mozilla') !== false &&
			preg_match('/rv:[0-9]\.[0-9]/i', $userAgent) &&
			stripos($userAgent, 'netscape') === false
		)
		{
			$aversion = explode('', stristr($userAgent, 'rv:'));
			$this->version = str_replace('rv:', '', $aversion[0]);
			$this->name = self::MOZILLA;
		}
		elseif (stripos($userAgent, 'mozilla') !== false &&
			preg_match('/mozilla\/([^ ]*)/i', $userAgent, $matches) &&
			stripos($userAgent, 'netscape') === false
		)
		{
			if (isset($matches[1]))
				$this->version = $matches[1];
			$this->name = self::MOZILLA;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Lynx.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectLynx(string $userAgent): bool
	{
		if (stripos($userAgent, 'lynx') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Lynx'));
			$aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ''));
			$this->version = $aversion[0];
			$this->name = self::LYNX;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Amaya.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectAmaya(string $userAgent): bool
	{
		if (stripos($userAgent, 'amaya') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Amaya'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::AMAYA;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Safari.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectWkhtmltopdf(string $userAgent): bool
	{
		if (stripos($userAgent, 'wkhtmltopdf') !== false)
			$this->name = self::WKHTMLTOPDF;

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Safari.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSafari(string $userAgent): bool
	{
		if (stripos($userAgent, 'Safari') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Version'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::SAFARI;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Yandex.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectYandex(string $userAgent): bool
	{
		if (stripos($userAgent, 'YaBrowser') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'YaBrowser'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::YANDEX;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Comodo Dragon / Ice Dragon / Chromodo.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectDragon(string $userAgent): bool
	{
		if (stripos($userAgent, 'Dragon') !== false)
		{
			$aresult = explode('/', stristr($userAgent, 'Dragon'));
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = $aversion[0];
			}
			$this->name = self::DRAGON;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the browser is Android.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectAndroid(string $userAgent): bool
	{
		// Navigator
		if (stripos($userAgent, 'Android') !== false)
		{
			if (preg_match('/Version\/([\d\.]*)/i', $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
			}
			$this->name = self::NAVIGATOR;
		}

		return $this->name != '';
	}
}

class Device
{
	const IPAD = 'iPad';
	const IPHONE = 'iPhone';
	const WINDOWS_PHONE = 'Windows Phone';
	const ANDROID_PHONE = 'Android Phone';

	private string $name = '';
	private string $version = '';

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	public function __construct(string $userAgent = null)
	{
		$userAgent = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? null;

		if ($userAgent !== null)
			$this->detectIpad($userAgent) ||
			$this->detectIphone($userAgent) ||
			$this->detectWindowsPhone($userAgent) ||
			$this->detectSamsungPhone($userAgent) ||
			$this->detectAndroidPhone($userAgent);
	}

	/**
	 * Determine if the device is iPad.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIpad(string $userAgent): bool
	{
		if (stripos($userAgent, 'ipad') !== false)
			$this->name = self::IPAD;

		return $this->name != '';
	}

	/**
	 * Determine if the device is iPhone.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIphone(string $userAgent): bool
	{
		if (stripos($userAgent, 'iphone;') !== false)
			$this->name = self::IPHONE;

		return $this->name != '';
	}

	/**
	 * Determine if the device is Windows Phone.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectWindowsPhone(string $userAgent): bool
	{
		if (stripos($userAgent, 'Windows Phone') !== false)
		{
			if (preg_match('/Microsoft; (Lumia [^)]*)\)/', $userAgent, $matches))
				$this->name = $matches[1];
			else
				$this->name = self::WINDOWS_PHONE;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the device is Samsung Phone.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSamsungPhone(string $userAgent): bool
	{
		if (preg_match('/SAMSUNG SM-([^ ]*)/i', $userAgent, $matches))
			$this->name = str_ireplace('SAMSUNG', 'Samsung', $matches[0]);

		return $this->name != '';
	}


	/**
	 * Determine if the device is an Android Phone if Chrome is in use, and returns the Model-Code in $device setName
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectAndroidPhone(string $userAgent): bool
	{
		if (stripos($userAgent, 'Android') !== false)
		{
			if (preg_match('/Linux; (Android [0-9\.]*); (?<modell>.*)\) AppleWebKit/', $userAgent, $matches))
				$this->name = preg_replace('/ Build.*$/', '', $matches["modell"]);
			else
				$this->name = self::ANDROID_PHONE;
		}

		return $this->name != '';
	}
}

class Os
{
	const OSX = 'OS X';
	const IOS = 'iOS';
	const SYMBOS = 'SymbOS';
	const WINDOWS = 'Windows';
	const ANDROID = 'Android';
	const LINUX = 'Linux';
	const NOKIA = 'Nokia';
	const BLACKBERRY = 'BlackBerry';
	const FREEBSD = 'FreeBSD';
	const OPENBSD = 'OpenBSD';
	const NETBSD = 'NetBSD';
	const OPENSOLARIS = 'OpenSolaris';
	const SUNOS = 'SunOS';
	const OS2 = 'OS2';
	const BEOS = 'BeOS';
	const WINDOWS_PHONE = 'Windows Phone';
	const CHROME_OS = 'Chrome OS';

	private string $name = '';
	private string $version = '';

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	public function __construct(string $userAgent = null)
	{
		$userAgent = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? null;

		if ($userAgent !== null)
			// Chrome OS before OS X
			$this->detectChromeOs($userAgent) ||
			// iOS before OS X
			$this->detectIOS($userAgent) ||
			$this->detectOSX($userAgent) ||
			$this->detectSymbOS($userAgent) ||
			$this->detectWindows($userAgent) ||
			$this->detectWindowsPhone($userAgent) ||
			$this->detectFreeBSD($userAgent) ||
			$this->detectOpenBSD($userAgent) ||
			$this->detectNetBSD($userAgent) ||
			$this->detectOpenSolaris($userAgent) ||
			$this->detectSunOS($userAgent) ||
			$this->detectOS2($userAgent) ||
			$this->detectBeOS($userAgent) ||
			// Android before Linux
			$this->detectAndroid($userAgent) ||
			$this->detectLinux($userAgent) ||
			$this->detectNokia($userAgent) ||
			$this->detectBlackBerry($userAgent);
	}

	/**
	 * Determine if the user's operating system is iOS.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectIOS(string $userAgent): bool
	{
		if (stripos($userAgent, 'CPU OS') !== false ||
			stripos($userAgent, 'iPhone OS') !== false &&
			stripos($userAgent, 'OS X')
		)
		{
			$this->name = self::IOS;
			if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', $userAgent, $matches))
				$this->version = str_replace('_', '.', $matches[2]);
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Chrome OS.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectChromeOs(string $userAgent): bool
	{
		if (stripos($userAgent, ' CrOS') !== false ||
			stripos($userAgent, 'CrOS ') !== false
		)
		{
			$this->name = self::CHROME_OS;
			if (preg_match('/Chrome\/([\d\.]*)/i', $userAgent, $matches))
				$this->version = $matches[1];
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is OS X.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOSX(string $userAgent): bool
	{
		if (stripos($userAgent, 'OS X') !== false)
		{
			$this->name = self::OSX;
			if (preg_match('/OS X ([\d\._]*)/i', $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = str_replace('_', '.', $matches[1]);
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Windows.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectWindows(string $userAgent): bool
	{
		if (stripos($userAgent, 'Windows NT') !== false)
		{
			$this->name = self::WINDOWS;

			if (preg_match('/Windows NT ([\d\.]*)/i', $userAgent, $matches))
			{
				if (isset($matches[1]))
				{
					switch (str_replace('_', '.', $matches[1]))
					{
						case '6.3':
							$this->version = '8.1';
							break;
						case '6.2':
							$this->version = '8';
							break;
						case '6.1':
							$this->version = '7';
							break;
						case '6.0':
							$this->version = 'Vista';
							break;
						case '5.2':
						case '5.1':
							$this->version = 'XP';
							break;
						case '5.01':
						case '5.0':
							$this->version = '2000';
							break;
						case '4.0':
							$this->version = 'NT 4.0';
							break;
						default:
							if ((float) $matches[1] >= 10.0)
								$this->version = $matches[1];
							break;
					}
				}
			}
		}
		elseif (preg_match(
			'/(Windows (?:98; Win 9x 4\.90|98|95|CE))/i',
			$userAgent,
			$matches
		))
		{
			$this->name = self::WINDOWS;
			switch (strtolower($matches[0]))
			{
				case 'windows 98; win 9x 4.90':
					$this->version = 'Me';
					break;
				case 'windows 98':
					$this->version = '98';
					break;
				case 'windows 95':
					$this->version = '95';
					break;
				case 'windows ce':
					$this->version = 'CE';
					break;
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Windows Phone.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectWindowsPhone(string $userAgent): bool
	{
		if (stripos($userAgent, 'Windows Phone') !== false)
		{
			$this->name = self::WINDOWS_PHONE;
			if (preg_match('/Windows Phone ([\d\.]*)/i', $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = (float) $matches[1];
			}
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is SymbOS.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSymbOS(string $userAgent): bool
	{
		if (stripos($userAgent, 'SymbOS') !== false)
			$this->name = self::SYMBOS;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Linux.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectLinux(string $userAgent): bool
	{
		if (stripos($userAgent, 'Linux') !== false)
			$this->name = self::LINUX;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Nokia.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectNokia(string $userAgent): bool
	{
		if (stripos($userAgent, 'Nokia') !== false)
			$this->name = self::NOKIA;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is BlackBerry.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectBlackBerry(string $userAgent): bool
	{
		if (stripos($userAgent, 'BlackBerry') !== false)
		{
			if (stripos($userAgent, 'Version/') !== false)
			{
				$aresult = explode('Version/', $userAgent);
				if (isset($aresult[1]))
				{
					$aversion = explode(' ', $aresult[1]);
					$this->version = $aversion[0];
				}
			}
			$this->name = self::BLACKBERRY;
		}
		elseif (stripos($userAgent, 'BB10') !== false)
		{
			$aresult = explode('Version/10.', $userAgent);
			if (isset($aresult[1]))
			{
				$aversion = explode(' ', $aresult[1]);
				$this->version = '10.' . $aversion[0];
			}
			else
				$this->version = '10';
			$this->name = self::BLACKBERRY;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is Android.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectAndroid(string $userAgent): bool
	{
		if (stripos($userAgent, 'Android') !== false)
		{
			if (preg_match('/Android ([\d\.]*)/i', $userAgent, $matches))
			{
				if (isset($matches[1]))
					$this->version = $matches[1];
			}
			$this->name = self::ANDROID;
		}

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is FreeBSD.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectFreeBSD(string $userAgent): bool
	{
		if (stripos($userAgent, 'FreeBSD') !== false)
			$this->name = self::FREEBSD;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is OpenBSD.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOpenBSD(string $userAgent): bool
	{
		if (stripos($userAgent, 'OpenBSD') !== false)
			$this->name = self::OPENBSD;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is SunOS.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectSunOS(string $userAgent): bool
	{
		if (stripos($userAgent, 'SunOS') !== false)
			$this->name = self::SUNOS;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is NetBSD.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectNetBSD(string $userAgent): bool
	{
		if (stripos($userAgent, 'NetBSD') !== false)
			$this->name = self::NETBSD;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is OpenSolaris.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOpenSolaris(string $userAgent): bool
	{
		if (stripos($userAgent, 'OpenSolaris') !== false)
			$this->name = self::OPENSOLARIS;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is OS2.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectOS2(string $userAgent): bool
	{
		if (stripos($userAgent, 'OS/2') !== false)
			$this->name = self::OS2;

		return $this->name != '';
	}

	/**
	 * Determine if the user's operating system is BeOS.
	 *
	 * @param string $userAgent
	 *
	 * @return bool
	 */
	private function detectBeOS(string $userAgent): bool
	{
		if (stripos($userAgent, 'BeOS') !== false)
			$this->name = self::BEOS;

		return $this->name != '';
	}
}
