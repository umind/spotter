<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':attribute muss akzeptiert werden.',
    'active_url'           => ':attribute ist keine gültige Internet-Adresse.',
    'after'                => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'       => ':attribute muss ein Datum nach dem :date oder gleich dem :date sein.',
    'alpha'                => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash'           => ':attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.',
    'alpha_num'            => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array'                => ':attribute muss ein Array sein.',
    'before'               => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal'      => ':attribute muss ein Datum vor dem :date oder gleich dem :date sein.',
    'between'              => [
        'numeric' => ':attribute muss zwischen :min & :max liegen.',
        'file'    => ':attribute muss zwischen :min & :max Kilobytes groß sein.',
        'string'  => ':attribute muss zwischen :min & :max Zeichen lang sein.',
        'array'   => ':attribute muss zwischen :min & :max Elemente haben.',
    ],
    'boolean'              => ":attribute muss entweder 'true' oder 'false' sein.",
    'confirmed'            => ':attribute stimmt nicht mit der Bestätigung überein.',
    'date'                 => ':attribute muss ein gültiges Datum sein.',
    'date_format'          => ':attribute entspricht nicht dem gültigen Format für :format.',
    'different'            => ':attribute und :other müssen sich unterscheiden.',
    'digits'               => ':attribute muss :digits Stellen haben.',
    'digits_between'       => ':attribute muss zwischen :min und :max Stellen haben.',
    'dimensions'           => ':attribute hat ungültige Bildabmessungen.',
    'distinct'             => 'Das Feld :attribute beinhaltet einen bereits vorhandenen Wert.',
    'email'                => ':attribute muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Der gewählte Wert für :attribute ist ungültig.',
    'file'                 => ':attribute muss eine Datei sein.',
    'filled'               => ':attribute muss ausgefüllt sein.',
    'image'                => ':attribute muss ein Bild sein.',
    'in'                   => 'Der gewählte Wert für :attribute ist ungültig.',
    'in_array'             => 'Der gewählte Wert für :attribute kommt nicht in :other vor.',
    'integer'              => ':attribute muss eine ganze Zahl sein.',
    'ip'                   => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4'                 => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'                 => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json'                 => ':attribute muss ein gültiger JSON-String sein.',
    'max'                  => [
        'numeric' => ':attribute darf maximal :max sein.',
        'file'    => ':attribute darf maximal :max Kilobytes groß sein.',
        'string'  => ':attribute darf maximal :max Zeichen haben.',
        'array'   => ':attribute darf nicht mehr als :max Elemente haben.',
    ],
    'mimes'                => ':attribute muss den Dateityp :values haben.',
    'mimetypes'            => ':attribute muss den Dateityp :values haben.',
    'min'                  => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file'    => ':attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':attribute muss mindestens :min Elemente haben.',
    ],
    'not_in'               => 'Der gewählte Wert für :attribute ist ungültig.',
    'numeric'              => ':attribute muss eine Zahl sein.',
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'regex'                => ':attribute Format ist ungültig.',
    'required'             => ':attribute muss ausgefüllt sein.',
    'required_if'          => ':attribute muss ausgefüllt sein, wenn :other :value ist.',
    'required_unless'      => ':attribute muss ausgefüllt sein, wenn :other nicht :values ist.',
    'required_with'        => ':attribute muss angegeben werden, wenn :values ausgefüllt wurde.',
    'required_with_all'    => ':attribute muss angegeben werden, wenn :values ausgefüllt wurde.',
    'required_without'     => ':attribute muss angegeben werden, wenn :values nicht ausgefüllt wurde.',
    'required_without_all' => ':attribute muss angegeben werden, wenn keines der Felder :values ausgefüllt wurde.',
    'same'                 => ':attribute und :other müssen übereinstimmen.',
    'size'                 => [
        'numeric' => ':attribute muss gleich :size sein.',
        'file'    => ':attribute muss :size Kilobyte groß sein.',
        'string'  => ':attribute muss :size Zeichen lang sein.',
        'array'   => ':attribute muss genau :size Elemente haben.',
    ],
    'string'               => ':attribute muss ein String sein.',
    'timezone'             => ':attribute muss eine gültige Zeitzone sein.',
    'unique'               => ':attribute ist schon vergeben.',
    'uploaded'             => 'Der :attribute konnte nicht hochgeladen werden.',
    'url'                  => 'Das Format von :attribute ist ungültig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],


    'skype_required' => 'Skypename ist erforderlich',
    'choose_package' => 'Bitte wählen Sie das gewünschte Paket aus',
    'mobile_required_with_sms_checked' => 'Dieses Feld ist obligatorisch, wenn Sie SMS Benachrichtgungen erhalten möchten.',

    /* Javascript Validation */
    'max_files' => 'Mindestens 4 Dateien sind erforderlich',
    'required_field' => 'Dieses Feld ist erforderlich',
    'default_package_required' => 'Basis-Paket ist erforderlich',
    'gotm_package_required' => 'Paket Girl des Monats ist erforderlich',
    'lotm_package_required' => 'Paket Lokal des Monats ist erforderlich',
    'url_invalid' => 'URL ist nicht gültig',
    'numeric_error' => 'Dieses Feld muss numerisch sein',
    'string_length' => 'Dieses Feld darf nicht mehr als 200 Zeichen haben',
    'older_than_18' => 'Sie müssen mindestens 18 Jahre alt sein',
    'alpha_numerical' => 'Der Vorname darf nur aus Buchstaben bestehen',
    'max_str_length' => 'Dieses Feld darf nicht mehr als :max Zeichen haben',
    'number_greater_than_zero' => 'Der Wert muss grösser als 0 sein',
    'banner_required' => 'Bitte wählen Sie einen Banner aus',

    /* Upload care validation */
    'file_too_large' => 'Datei ist zu gross',
    'file_too_large_title' => 'Maximale Grösse Fehler',
    'file_type' => 'Ungültiges Dateiformat',
    'file_type_title' => 'Dateiformat Fehler.',
    'min_photo_dimensions' => 'Die minimale Dimension vom Foto ist :dimensions',
    'min_dimensions_title' => 'Minimale Dimension Fehler',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'country'               => 'Land',
        'gender'                => 'Geschlecht',
        'gender_type'                => 'Geschlecht',
        'day'                   => 'Tag',
        'month'                 => 'Monat',
        'year'                  => 'Jahr',
        'hour'                  => 'Stunde',
        'minute'                => 'Minute',
        'second'                => 'Sekunde',
        'title'                 => 'Titel',
        'content'               => 'Inhalt',
        'excerpt'               => 'Auszug',
        'time'                  => 'Uhrzeit',
        'available'             => 'verfügbar',
        'size'                  => 'Größe',
        'photos_1'              => 'Photos',
        'nickname_1'            => 'Nickname',
        'client_photo'           => 'Photo',
        "type" => 'Typ',
        "city" => 'Stadt',
        "radius" => 'Radius',
        "canton" => 'Kanton',
        "services" => 'Services',
        "age" => 'Alter',
        "price_type" => 'Typ',
        "nickname" => 'Nickname',
        "first_name" => 'Vorname',
        "last_name" => 'Nachname',
        "height" => 'Grösse',
        "weight" => 'Gewicht',
        "nationality" => 'Nationalität',
        "sex" => 'Geschlecht',
        "sex_orientation" => 'Sexuelle Orientierung',
        "figure" => 'Figur',
        "breast_size" => 'Körbchengrösse',
        "eye_color" => 'Augenfarbe',
        "hair_color" => 'Haarfarbe',
        "tattoos" => 'Tattoo',
        "piercings" => 'Piercing',
        "body_hair" => 'Körperbehaarung',
        "intimate" => 'Intimbehaarung',
        "smoker" => 'Raucher',
        "alcohol" => 'Alkohol',
        "about_me" => 'Über mich',
        "video" => 'Video',
        "sms_notifications" => 'SMS Benachrichtigungen',
        "email" => 'Email',
        "phone" => 'Telefon',
        "mobile" => 'Mobil',
        "skype_name" => 'Skype Name',
        "zip_code" => 'PLZ',
        "address" => 'Adresse',
        "club_name" => 'Club Name',
        "incall" => 'Incall',
        "outcall" => 'Outcall',
        "service_duration" => 'Dauer',
        "service_price" => 'Preis',
        "service_price_unit" => 'Einheit',
        "service_price_currency" => 'Währung',
        "date" => 'Datum',
        "client_name" => 'Name',
        "client_phone" => 'Telefon',
        "photo" => 'Bild',
        "description" => 'Beschreibung',
        "username" => 'Benutzername',
        "password" => 'Passwort',
        "password_confirmation" => 'Passwort bestätigen',
        "user_type" => 'Typ',
        "ancestry" => 'Herkunft',
        "name" => 'Name',
        "website" => 'Webseite',
        "street" => 'Strasse',
        "zip" => 'PLZ',
        "logo" => 'Logo',
        "photos" => 'Bilder',
        "news_flyer" => 'Flyer',
        "news_title" => 'Titel',
        "news_duration" => 'Dauer',
        "duration" => 'Dauer',
        "news_photo" => 'Bild',
        "events_flyer" => 'Flyer',
        "events_title" => 'Titel',
        "events_venue" => 'Ort',
        "events_date" => 'Datum',
        "events_duration" => 'Dauer',
        "events_photo" => 'Bild',
        "news_description" => 'Beschreibung',
        "events_description" => 'Beschreibung',
        "sexes.1" => 'Typ',
        'subject' => 'Betreff',
        'comment' => 'Kommentar',


        // post an auction
        'category' => 'Kategorie',
        'category_name' => 'Kategorie Name',
        'ad_title' => 'Titel',
        'bid_no' => 'Los-Nummer',
        'auction_no' => 'Auktion-Nummer',
        'price_increaser' => 'Preis erhöhen',
        'ad_description' => 'Beschreibung',
        'seller_phone' => 'Verkäufer Telefon',
        'seller_email' => 'Verkäufer Email',
        'seller_name' => 'Verkäufer Name',
        'price' => 'Startpreis',

        // contact us
        'message' => 'Nachricht',

        // post an event
        'products' => 'Produkte',
        'auctioner' => 'Auktionär',
        'auction_deadline' => 'Gebotsende',

        //change password
        "old_password" => "altes Passwort",
        "new_password" => "neues Passwort",
        "new_password_confirmation" => "neues Passwort bestätigen",
    ],

];
