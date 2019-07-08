<?php
/**
 * Copyright © 2015 Creatuity. All rights reserved.
 */

namespace Creatuity\Monitor\Model;

class Exception extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Creatuity\Monitor\Model\Resource\Exception');
    }

    const STATE_NEW = 1;
    const STATE_KNOWN = 2;
    const STATE_FIXED = 3;

    public static function getStateFromString($string)
    {
        switch ($string) {
            case 'new':
                return static::STATE_NEW;
            case 'known':
                return static::STATE_KNOWN;
            case 'fixed':
                return static::STATE_FIXED;
            default:
                return 'unknown';
        }
    }

    public function getStateString()
    {
        switch ($this->getState()) {
            case static::STATE_NEW:
                return ($this->getTotalCount() == $this->getCurrentCount()) ? 'new' : 'recurring';
            case static::STATE_KNOWN:
                return 'known';
            case static::STATE_FIXED:
                return 'fixed';
            default:
                return 'unknown';
        }
    }

    public function getSortOrder()
    {
        return $this->getState() * $this->getCurrentCount();
    }

}
