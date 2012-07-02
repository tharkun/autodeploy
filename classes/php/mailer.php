<?php

namespace autodeploy\php;

use autodeploy\aggregators;

abstract class mailer implements aggregators\php\adapter
{

    const CONTENT_TYPE_TEXT_PLAIN   = 'text/plain';
    const CONTENT_TYPE_CHARSET      = 'utf-8';

    protected $adapter = null;

    protected $to = null;
    protected $from = null;
    protected $xMailer = null;
    protected $replyTo = null;
    protected $subject = null;
    protected $contentType = null;

    public function __construct(adapter $adapter = null)
    {
        $this->setAdapter($adapter ?: new adapter());

        $this->setContentType(self::CONTENT_TYPE_TEXT_PLAIN, self::CONTENT_TYPE_CHARSET);
    }

    /**
     * @param adapter $adapter
     * @return mailer
     */
    public function setAdapter(adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function addTo($to, $realName = null)
    {
        if ($this->to !== null)
        {
            $this->to .= ',';
        }

        if ($realName === null)
        {
            $this->to .= (string) $to;
        }
        else
        {
            $this->to .= (string) $realName . ' <' . (string) $to . '>';
        }

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setSubject($subject)
    {
        $this->subject = (string) $subject;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setFrom($from, $realName = null)
    {
        if ($realName === null)
        {
            $this->from = (string) $from;
        }
        else
        {
            $this->from = (string) $realName . ' <' . (string) $from . '>';
        }

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setReplyTo($replyTo, $realName = null)
    {
        if ($realName === null)
        {
            $this->replyTo = (string) $replyTo;
        }
        else
        {
            $this->replyTo = (string) $realName . ' <' . (string) $replyTo . '>';
        }

        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setXMailer($mailer)
    {
        $this->xMailer = (string) $mailer;

        return $this;
    }

    public function getXMailer()
    {
        return $this->xMailer;
    }

    public function setContentType($type = self::CONTENT_TYPE_TEXT_PLAIN, $charset = self::CONTENT_TYPE_CHARSET)
    {
        $this->contentType = array($type, $charset);

        return $this;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public abstract function send($something);

}
