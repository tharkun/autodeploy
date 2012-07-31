<?php

namespace autodeploy\writers;

use
    autodeploy,
    autodeploy\definitions\writers,
    autodeploy\php,
    autodeploy\reports
;

class mail extends autodeploy\writer implements writers\asynchronous
{
    protected $mailer = null;

    /**
     * @param \autodeploy\php\mailer|null $mailer
     * @param \autodeploy\php\adapter|null $adapter
     * @param \autodeploy\php\locale|null $locale
     */
    public function __construct(php\mailer $mailer = null, php\adapter $adapter = null, php\locale $locale = null)
    {
        parent::__construct($adapter, $locale);

        $this->setMailer($mailer ?: new php\mailers\mail());
    }

    /**
     * @param \autodeploy\php\mailer $mailer
     * @return mail
     */
    public function setMailer(php\mailer $mailer)
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * @return null
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param $something
     * @return mail
     */
    public function write($something)
    {
        $this->mailer->send($something);

        return $this;
    }

    /**
     * @param \autodeploy\reports\asynchronous $report
     * @return mail
     */
    public function writeAsynchronous(reports\asynchronous $report)
    {
        return $this->write((string) $report);
    }
}
