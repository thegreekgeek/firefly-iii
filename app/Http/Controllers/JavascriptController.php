<?php
/**
 * JavascriptController.php
 * Copyright (c) 2017 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Http\Controllers;

use Carbon\Carbon;
use FireflyIII\Exceptions\FireflyException;
use FireflyIII\Models\Account;
use FireflyIII\Models\AccountType;
use FireflyIII\Models\TransactionCurrency;
use FireflyIII\Repositories\Account\AccountRepositoryInterface;
use FireflyIII\Repositories\Currency\CurrencyRepositoryInterface;
use Illuminate\Http\Request;
use Log;
use Navigation;
use Preferences;

/**
 * Class JavascriptController
 *
 * @package FireflyIII\Http\Controllers
 */
class JavascriptController extends Controller
{
    /**
     * @param AccountRepositoryInterface  $repository
     * @param CurrencyRepositoryInterface $currencyRepository
     *
     * @return \Illuminate\Http\Response
     */
    public function accounts(AccountRepositoryInterface $repository, CurrencyRepositoryInterface $currencyRepository)
    {
        $accounts   = $repository->getAccountsByType([AccountType::DEFAULT, AccountType::ASSET]);
        $preference = Preferences::get('currencyPreference', config('firefly.default_currency', 'EUR'));
        $default    = $currencyRepository->findByCode($preference->data);

        $data = ['accounts' => [],];


        /** @var Account $account */
        foreach ($accounts as $account) {
            $accountId                    = $account->id;
            $currency                     = intval($account->getMeta('currency_id'));
            $currency                     = $currency === 0 ? $default->id : $currency;
            $entry                        = ['preferredCurrency' => $currency, 'name' => $account->name];
            $data['accounts'][$accountId] = $entry;
        }


        return response()
            ->view('javascript.accounts', $data, 200)
            ->header('Content-Type', 'text/javascript');
    }

    /**
     * @param CurrencyRepositoryInterface $repository
     *
     * @return \Illuminate\Http\Response
     */
    public function currencies(CurrencyRepositoryInterface $repository)
    {
        $currencies = $repository->get();
        $data       = ['currencies' => [],];
        /** @var TransactionCurrency $currency */
        foreach ($currencies as $currency) {
            $currencyId                      = $currency->id;
            $entry                           = ['name' => $currency->name, 'code' => $currency->code, 'symbol' => $currency->symbol];
            $data['currencies'][$currencyId] = $entry;
        }

        return response()
            ->view('javascript.currencies', $data, 200)
            ->header('Content-Type', 'text/javascript');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function variables(Request $request)
    {
        $localeconv                = localeconv();
        $accounting                = app('amount')->getJsConfig($localeconv);
        $localeconv                = localeconv();
        $defaultCurrency           = app('amount')->getDefaultCurrency();
        $localeconv['frac_digits'] = $defaultCurrency->decimal_places;
        $pref                      = Preferences::get('language', config('firefly.default_language', 'en_US'));
        $lang                      = $pref->data;
        $dateRange                 = $this->getDateRangeConfig();

        $data = [
            'currencyCode'    => app('amount')->getCurrencyCode(),
            'currencySymbol'  => app('amount')->getCurrencySymbol(),
            'accounting'      => $accounting,
            'localeconv'      => $localeconv,
            'language'        => $lang,
            'dateRangeTitle'  => $dateRange['title'],
            'dateRangeConfig' => $dateRange['configuration'],
        ];
        $request->session()->keep(['two-factor-secret']);

        return response()
            ->view('javascript.variables', $data, 200)
            ->header('Content-Type', 'text/javascript');
    }

    /**
     * @return array
     */
    private function getDateRangeConfig(): array
    {
        $viewRange = Preferences::get('viewRange', '1M')->data;
        $start     = session('start');
        $end       = session('end');
        $first     = session('first');
        $title     = sprintf('%s - %s', $start->formatLocalized($this->monthAndDayFormat), $end->formatLocalized($this->monthAndDayFormat));
        $isCustom  = session('is_custom_range');
        $ranges    = [
            // first range is the current range:
            $title => [$start, $end],
        ];
        Log::debug(sprintf('viewRange is %s', $viewRange));

        // get the format for the ranges:
        $format = $this->getFormatByRange($viewRange);

        // when current range is a custom range, add the current period as the next range.
        if ($isCustom) {
            Log::debug('Custom is true.');
            $index             = $start->formatLocalized($format);
            $customPeriodStart = Navigation::startOfPeriod($start, $viewRange);
            $customPeriodEnd   = Navigation::endOfPeriod($customPeriodStart, $viewRange);
            $ranges[$index]    = [$customPeriodStart, $customPeriodEnd];
        }
        // then add previous range and next range
        $previousDate   = Navigation::subtractPeriod($start, $viewRange);
        $index          = $previousDate->formatLocalized($format);
        $previousStart  = Navigation::startOfPeriod($previousDate, $viewRange);
        $previousEnd    = Navigation::endOfPeriod($previousStart, $viewRange);
        $ranges[$index] = [$previousStart, $previousEnd];

        $nextDate       = Navigation::addPeriod($start, $viewRange, 0);
        $index          = $nextDate->formatLocalized($format);
        $nextStart      = Navigation::startOfPeriod($nextDate, $viewRange);
        $nextEnd        = Navigation::endOfPeriod($nextStart, $viewRange);
        $ranges[$index] = [$nextStart, $nextEnd];

        // everything
        $index          = strval(trans('firefly.everything'));
        $ranges[$index] = [$first, new Carbon];

        $return = [
            'title'         => $title,
            'configuration' => [
                'apply'       => strval(trans('firefly.apply')),
                'cancel'      => strval(trans('firefly.cancel')),
                'from'        => strval(trans('firefly.from')),
                'to'          => strval(trans('firefly.to')),
                'customRange' => strval(trans('firefly.customRange')),
                'start'       => $start->format('Y-m-d'),
                'end'         => $end->format('Y-m-d'),
                'ranges'      => $ranges,
            ],
        ];


        return $return;

    }

    private function getFormatByRange(string $viewRange): string
    {
        switch ($viewRange) {
            default:
                throw new FireflyException(sprintf('The date picker does not yet support "%s".', $viewRange)); // @codeCoverageIgnore
            case '1D':
            case 'custom':
                $format = (string)trans('config.month_and_day');
                break;
            case '3M':
                $format = (string)trans('config.quarter_in_year');
                break;
            case '6M':
                $format = (string)trans('config.half_year');
                break;
            case '1Y':
                $format = (string)trans('config.year');
                break;
            case '1M':
                $format = (string)trans('config.month');
                break;
            case '1W':
                $format = (string)trans('config.week_in_year');
                break;
        }

        return $format;
    }
}
