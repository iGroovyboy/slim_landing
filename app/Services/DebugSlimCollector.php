<?php


namespace App\Services;

use DebugBar\DataCollector\MessagesCollector;
use Psr\Log\LogLevel;
use Slim\Log;

class DebugSlimCollector extends MessagesCollector
{
    protected $slim;

    protected $originalLogWriter;
    protected $defaultLevel;

    public function __construct($logger, $defaultLevel = null)
    {
        parent::__construct($this->getName());
        $this->defaultLevel = $defaultLevel ?: LogLevel::DEBUG;
        call_user_func([$logger, 'setWriter'], $this);
    }

    public function write($message, $level = null)
    {
        $this->addMessage($message, $level ?: $this->defaultLevel);
    }

    public function getName()
    {
        return 'slim';
    }
}
