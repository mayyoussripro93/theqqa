<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\Traits;

use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Language as LanguageLocalization;
use App\Models\Country;
use Illuminate\Support\Facades\Config;

trait LocalizationTrait
{
	/**
	 * Get Localization
	 * Get Locale from Browser or from Country spoken Language
	 * and get Country by User IP
	 */
	private function loadLocalizationData()
	{
		// Language
		$langObj = new LanguageLocalization();
		$lang = $langObj->find();
		// Country
		$countryObj = new CountryLocalization();

		$countryObj->find();
		$countryObj->setCountryParameters();
        $countryObj->country=Country::where('code','SA')->first();

		// Fix for the vars
		$lang = (!empty($lang)) ? $lang : collect([]);
		$country = (!empty($countryObj->country)) ? $countryObj->country : collect([]);
		$ipCountry = (!empty($countryObj->ipCountry)) ? $countryObj->ipCountry : collect([]);

        // Translate the Country name (If translation exists)
//		if (!empty($country) && !$lang->isEmpty()) {
//			$country = CountryLocalizationHelper::trans($country, $lang->get('abbr'));
//		}

		// Session: Set Country Code
		// Config: Country
		if (!empty($country) && $country->code) {
			session(['country_code' =>  $country->code]);
			$countryLangExists =$lang->get('abbr') && $lang->get('abbr');
			Config::set('country.locale', ($countryLangExists) ? $lang->get('abbr') : config('app.locale'));
			Config::set('country.lang', ($lang->get('abbr') ? $lang->get('abbr') : []));
			Config::set('country.code', $country->code);
			Config::set('country.icode', $country->icode);
			Config::set('country.name', $country->name);
			Config::set('country.currency', $country->currency_code);
			Config::set('country.admin_type', $country->admin_type);
			Config::set('country.admin_field_active', $country->admin_field_active);
			Config::set('country.background_image', $country->background_image);
		}
        Config::set('currency.symbol', $country->currency_code);
		// Config: IP Country
		if (!$ipCountry->isEmpty() && $ipCountry->has('code')) {
			Config::set('ipCountry.code', $ipCountry->get('code'));
		}

		// Config: Currency
		if (!empty($country) && $country->currency_code && !empty($country->currency_code)) {
			Config::set('currency', $country->currency_code);
		}
		// Config: Set TimeZome
//		if (!$country->isEmpty() && $country->has('timezone') && !empty($country->get('timezone'))) {
//			Config::set('timezone.id', $country->get('timezone')->time_zone_id);
//		}
		// Config: Language
		if (!$lang->isEmpty()) {
			session(['language_code' => $lang->get('abbr')]);
			Config::set('lang.abbr', $lang->get('abbr'));
			Config::set('lang.locale', $lang->get('locale'));
			Config::set('lang.direction', $lang->get('direction'));
			Config::set('lang.russian_pluralization', $lang->get('russian_pluralization'));
		}
		// Config: Currency Exchange Plugin
		if (config('plugins.currencyexchange.installed')) {
			Config::set('country.currencies', ($country->currency_code) ? $country->currency_code : '');
		} else {
			Config::set('selectedCurrency', config('currency'));
		}
		// Config: Domain Mapping Plugin
		if (config('plugins.domainmapping.installed')) {
			applyDomainMappingConfig(config('country.code'));
		}
	}
}
