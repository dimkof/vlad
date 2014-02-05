<?php
namespace gajus\vlad;

/**
 * @link https://github.com/gajus/vlad for the canonical source repository
 * @copyright Copyright (c) 2013-2014, Anuary (http://anuary.com/)
 * @license https://github.com/gajus/vlad/blob/master/LICENSE BSD 3-Clause
 */
class Doctor {
	private
		/**
		 * @var Translator
		 */
		$translator;

	/**
	 * @param Translator $translator
	 */
	final public function __construct (Translator $translator = null) {
		$this->translator = $translator === null ? new Translator() : $translator;
	}
	
	/**
	 * @param array $test_script
	 * @return gajus\vlad\Test
	 */
	public function test (array $test_script) {
		$test = new Test($this->translator);

		$selectors = [];

		foreach ($test_script as $routine) {
			if (!is_array($routine[0])) {
				throw new \gajus\vlad\exception\Invalid_Argument_Exception('Selector must be an array.');
			}

			if (array_intersect($routine[0], $selectors)) {
				throw new \gajus\vlad\exception\Invalid_Argument_Exception('Test has duplicate selector declarations. Each selector can be declared only once per test.');
			}

			$selectors = array_merge($selectors, $routine[0]);

			foreach ($routine[0] as $selector) {
				foreach ($routine[1] as $context1 => $context2) {
					if (is_int($context1)) {
						$validator_name = $context2;
						$options = [];
					} else {
						$validator_name = $context1;
						$options = $context2;

						if (!is_array($options)) {
							throw new \gajus\vlad\exception\Invalid_Argument_Exception('Validator options must be an array.');
						}
					}

					$test->assert($selector, $validator_name, $options);
				}
			}
		}

		return $test;
	}
}