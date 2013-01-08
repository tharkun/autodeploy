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
    protected $xPriority = null;
    protected $notify = null;

    /**
     * @param adapter $adapter
     */
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

    /**
     * @param $to
     * @param null $realName
     * @return mailer
     */
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

    /**
     * @return null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param $subject
     * @return mailer
     */
    public function setSubject($subject)
    {
        $this->subject = (string) $subject;

        return $this;
    }

    /**
     * @return null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $from
     * @param null $realName
     * @return mailer
     */
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

    /**
     * @return null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $replyTo
     * @param null $realName
     * @return mailer
     */
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

    /**
     * @return null
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param $mailer
     * @return mailer
     */
    public function setXMailer($mailer)
    {
        $this->xMailer = (string) $mailer;

        return $this;
    }

    /**
     * @return null
     */
    public function getXMailer()
    {
        return $this->xMailer;
    }

    /**
     * @param string $type
     * @param string $charset
     * @return mailer
     */
    public function setContentType($type = self::CONTENT_TYPE_TEXT_PLAIN, $charset = self::CONTENT_TYPE_CHARSET)
    {
        $this->contentType = array($type, $charset);

        return $this;
    }

    /**
     * @return null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param $xpriority
     * @return mailer
     */
    public function setXPriority($xpriority)
    {
        if (1 <= $xpriority && $xpriority <= 5)
        {
            $this->xPriority = $xpriority;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXPriority()
    {
        return $this->xPriority;
    }

    /**
     * @param $notify
     * @return mailer
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * @return null
     */
    public function getNotify()
    {
        return $this->notify;
    }

    public abstract function send($something);

}
