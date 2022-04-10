<?php

namespace Itoufo\Notifynder\Contracts;

use Itoufo\Notifynder\Exceptions\NotificationLanguageNotFoundException;
use Itoufo\Notifynder\Exceptions\NotificationTranslationNotFoundException;

/**
 * Class NotifynderTranslator.
 *
 * The Translator is responsible to translate the text
 * of the notifications with the custom languages that
 * you define in the configuration file. Translations
 * are cached in storage/app/notifynder in a json format.
 *
 * Usage:
 *
 * [
 *  'it' => [
 *      'name.category' => 'text to translate'
 *   ]
 * ]
 */
interface NotifynderTranslator
{
    /**
     * Translate the given category.
     *
     * @param $language
     * @param $nameCategory
     * @return mixed
     * @throws NotificationLanguageNotFoundException
     * @throws NotificationTranslationNotFoundException
     */
    public function translate($language, $nameCategory);

    /**
     * Get selected language of translations.
     *
     * @param $language
     * @return mixed
     * @throws NotificationLanguageNotFoundException
     */
    public function getLanguage($language);

    /**
     * Get translations.
     *
     * @return array|mixed
     */
    public function getTranslations();
}
