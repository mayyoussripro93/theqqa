<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Helpers\Validator;

use App\Models\Blacklist;
use App\Helpers\Ip;

class BlacklistValidator
{
	/**
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
    public static function checkIp($attribute, $value, $parameters, $validator)
    {
        $ip = Ip::get();
        $res = Blacklist::ofType('ip')->where('entry', $ip)->first();
        if ($res) {
            return false;
        }
        
        return true;
    }
    
    /**
	 * Example: 'email' => 'whitelist_domain|...',
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
    public static function checkDomain($attribute, $value, $parameters, $validator)
    {
		$value = strtolower($value);
		$value = str_replace(['http://', 'www.'], '', $value);
        if (str_contains($value, '/')) {
			$value = strstr($value, '/', true);
        }
        if (str_contains($value, '@')) {
			$value = strstr($value, '@');
			$value = str_replace('@', '', $value);
        }
        $res = Blacklist::ofType('domain')->where('entry', $value)->first();
        if ($res) {
            return false;
        }
        
        return true;
    }
    
    /**
	 * Example: 'email' => 'whitelist_email|...',
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
    public static function checkEmail($attribute, $value, $parameters, $validator)
    {
		$value = strtolower($value);
        $res = Blacklist::ofType('email')->where('entry', $value)->first();
        if ($res) {
            return false;
        }
        
        return true;
    }
    
    /**
	 * Example: 'email' => 'whitelist_word|...',
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
    public static function checkWord($attribute, $value, $parameters, $validator)
    {
		$value = trim(mb_strtolower($value));
        $words = Blacklist::ofType('word')->get();
        if ($words->count() > 0) {
            foreach ($words as $word) {
                // Check if a ban's word is contained in the user entry
                $startPatten = '\s-.,;:=/#\|_<>';
				$endPatten = $startPatten . 's';
                try {
                    if (preg_match('|[' . $startPatten . '\\\]+' . $word->entry . '[' . $endPatten . '\\\]+|i', ' ' . $value . ' ')) {
                        return false;
                    }
                } catch (\Exception $e) {
                    if (preg_match('|[' . $startPatten . ']+' . $word->entry . '[' . $endPatten . ']+|i', ' ' . $value . ' ')) {
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
	 * Example: 'email' => 'whitelist_word_title|...',
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
    public static function checkTitle($attribute, $value, $parameters, $validator)
    {
        if (!self::checkWord($attribute, $value, $parameters, $validator)) {
            return false;
        }
        
        // Banned all domain name from title
        $tlds = config('tlds');
        if (count($tlds) > 0) {
            foreach ($tlds as $tld => $label) {
                if (str_contains($value, '.' . strtolower($tld))) {
                    return false;
                }
            }
        }
        
        return true;
    }
}
