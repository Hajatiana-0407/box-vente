<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright	Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/userguide3/general/controllers.html
 */
class CI_Controller
{

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance = &$this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class) {
			$this->$var = &load_class($class);
		}

		$this->load = &load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}



	// pagination 
	public  function  pagination($uri = '', $total = 0)
	{
		$config = array();
		$config["base_url"] = base_url() . $uri;
		$config["total_rows"] = $total;
		$config["per_page"] = PAGINATION;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);

		return  $this->pagination->create_links();
	}
	public  function  pagination_search($uri = '', $total = 0)
	{
		$config = array();
		$config["base_url"] = base_url() .  $uri;
		$config["total_rows"] = $total;
		$config["per_page"] = PAGINATION;
		$config['enable_query_strings'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['reuse_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}

	// jail 
	public  function jail()
	{
		if (!isset($_SESSION['user_type'])) {
			redirect('/');
		}
	}


	// convertion 
	public function covertion($all_unite = [], $min_qte = 0)
	{
		$by_unite = null;
		$reste = 0;
		// ajouter la valeur pour l'unité la plus petite


		$data = [];
		if (count($all_unite) > 0) {
			$data = [[
				'unite' => end($all_unite)->denomination,
				'quantite' => $min_qte,
				'reste' => $reste
			]];
		} else {
			$data = [[
				'unite' => '',
				'quantite' => $min_qte,
				'reste' => $reste
			]];
		}
		for ($i = count($all_unite) - 2; $i >= 0; $i--) {
			if (isset($all_unite[$i + 1])) {
				$element = $all_unite[$i];
				$unite = $element->denomination;

				// vérification si il y a un reste
				$reste = $min_qte % $all_unite[$i + 1]->formule;
				if ($reste != 0) {
					if ($reste > 1) {
						$reste = $reste . ' ' . $all_unite[$i + 1]->denomination . '(s)';
					} else {
						$reste = $reste . ' ' . $all_unite[$i + 1]->denomination;
					}
				}

				$min_qte = intval($min_qte / $all_unite[$i + 1]->formule);

				$by_unite = [
					'unite' => $unite,
					'quantite' => $min_qte,
					'reste' => $reste
				];

				array_unshift($data, $by_unite);
			}
		}
		return $data;
	}


	public function stock_texte($unite = [], $id = 0)
	{
		$texte = '0';
		$concat = '+';

		for ($i = $id; $i < count($unite); $i++) {
			$element = $unite[$i];

			if ($i == $id) {
				// on affiche rien si la quantité est 0 
				if ($element["quantite"] > 0) {
					$texte = $element["quantite"] . ' ' . $element["unite"];
					if ($element["quantite"] > 1) {
						// pour mettre le s
						$texte = $element["quantite"] . ' ' . $element["unite"] . '(s)';
					}
					// sans unite 
					if (empty($element["unite"])) {
						$texte = $element["quantite"];
					}
				}
			}

			if ($element["reste"] != 0) {
				// reste
				if ($element["quantite"] > 0) {
					$texte .= $concat . ' ' . $element["reste"];
				} else {
					$texte .= $element["reste"];
				}
			}
		}
		return $texte;
	}
}
