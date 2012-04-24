<?php

namespace autodeploy\aggregators\php;

use autodeploy\php;

interface locale
{

    /**
     * @abstract
     * @param \autodeploy\php\locale $locale
     * @return void
     */
    public function setLocale(php\locale $locale);

    /**
     * @abstract
     * @return void
     */
    public function getLocale();

}
