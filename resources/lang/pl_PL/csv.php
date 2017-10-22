<?php
/**
 * csv.php
 * Copyright (C) 2016 thegrumpydictator@gmail.com
 *
 * This software may be modified and distributed under the terms of the
 * Creative Commons Attribution-ShareAlike 4.0 International License.
 *
 * See the LICENSE file for details.
 */

declare(strict_types=1);

return [

    // initial config
    'initial_title'                 => 'Konfiguracja importu (1/3) - Podstawowa konfiguracja importu CSV',
    'initial_text'                  => 'Abyś mógł poprawnie zaimportować plik, sprawdź poprawność poniższych opcji.',
    'initial_box'                   => 'Podstawowa konfiguracja importu CSV',
    'initial_box_title'             => 'Basic CSV import setup options',
    'initial_header_help'           => 'Check this box if the first row of your CSV file are the column titles.',
    'initial_date_help'             => 'Date time format in your CSV. Follow the format like <a href="https://secure.php.net/manual/en/datetime.createfromformat.php#refsect1-datetime.createfromformat-parameters">this page</a> indicates. The default value will parse dates that look like this: :dateExample.',
    'initial_delimiter_help'        => 'Choose the field delimiter that is used in your input file. If not sure, comma is the safest option.',
    'initial_import_account_help'   => 'If your CSV file does NOT contain information about your asset account(s), use this dropdown to select to which account the transactions in the CSV belong to.',
    'initial_submit'                => 'Przejdź do kroku 2/3',

    // roles config
    'roles_title'                   => 'Konfiguracja importu (2/3) - Określ rolę każdej z kolumn',
    'roles_text'                    => 'Each column in your CSV file contains certain data. Please indicate what kind of data the importer should expect. The option to "map" data means that you will link each entry found in the column to a value in your database. An often mapped column is the column that contains the IBAN of the opposing account. That can be easily matched to IBAN\'s present in your database already.',
    'roles_table'                   => 'Tabela',
    'roles_column_name'             => 'Nazwa kolumny',
    'roles_column_example'          => 'Przykładowe dane w kolumnie',
    'roles_column_role'             => 'Znaczenie danych w kolumnie',
    'roles_do_map_value'            => 'Zmapuj te wartości',
    'roles_column'                  => 'Kolumna',
    'roles_no_example_data'         => 'Brak przykładowych danych',
    'roles_submit'                  => 'Przejdź do kroku 3/3',
    'roles_warning'                 => 'At the very least, mark one column as the amount-column. It is advisable to also select a column for the description, date and the opposing account.',

    // map data
    'map_title'                     => 'Konfiguracja importu (3/3) - Połącz importowane dane z danymi w Firefly III',
    'map_text'                      => 'In the following tables, the left value shows you information found in your uploaded CSV file. It is your task to map this value, if possible, to a value already present in your database. Firefly will stick to this mapping. If there is no value to map to, or you do not wish to map the specific value, select nothing.',
    'map_field_value'               => 'Wartość pola',
    'map_field_mapped_to'           => 'Zmapowane na',
    'map_do_not_map'                => '(nie mapuj)',
    'map_submit'                    => 'Rozpocznij Importowanie',

    // map things.
    'column__ignore'                => '(ignoruj tę kolumnę)',
    'column_account-iban'           => 'Konto aktywów (IBAN)',
    'column_account-id'             => 'ID konta aktywów (taki sam jak w Firefly)',
    'column_account-name'           => 'Konto aktywów (nazwa)',
    'column_amount'                 => 'Kwota',
    'column_amount-comma-separated' => 'Kwota (przecinek jako separator dziesiętny)',
    'column_bill-id'                => 'ID rachunku (taki sam jak w Firefly)',
    'column_bill-name'              => 'Nazwa rachunku',
    'column_budget-id'              => 'ID budżetu (taki sam jak w Firefly)',
    'column_budget-name'            => 'Nazwa budżetu',
    'column_category-id'            => 'ID kategorii (taki sam jak w Firefly)',
    'column_category-name'          => 'Nazwa kategorii',
    'column_currency-code'          => 'Kod waluty (ISO 4217)',
    'column_currency-id'            => 'ID waluty (taki sam jak w Firefly)',
    'column_currency-name'          => 'Nazwa waluty (taka sama jak w Firefly)',
    'column_currency-symbol'        => 'Symbol waluty (taki sam jak w Firefly)',
    'column_date-interest'          => 'Interest calculation date',
    'column_date-book'              => 'Data księgowania transakcji',
    'column_date-process'           => 'Transaction process date',
    'column_date-transaction'       => 'Data',
    'column_description'            => 'Opis',
    'column_opposing-iban'          => 'Opposing account (IBAN)',
    'column_opposing-id'            => 'Opposing account ID (matching Firefly)',
    'column_external-id'            => 'Zewnętrzne ID',
    'column_opposing-name'          => 'Opposing account (name)',
    'column_rabo-debet-credit'      => 'Rabobank specific debet/credit indicator',
    'column_ing-debet-credit'       => 'ING specific debet/credit indicator',
    'column_sepa-ct-id'             => 'SEPA Credit Transfer end-to-end ID',
    'column_sepa-ct-op'             => 'SEPA Credit Transfer opposing account',
    'column_sepa-db'                => 'SEPA Direct Debet',
    'column_tags-comma'             => 'Tagi (oddzielone przecinkami)',
    'column_tags-space'             => 'Tagi (oddzielone spacjami)',
    'column_account-number'         => 'Konto aktywów (numer konta)',
    'column_opposing-number'        => 'Opposing account (account number)',
];
