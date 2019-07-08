<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 12.04.19
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\Api\Data;


use Creatuity\Monitor\Model\Exception;
use Creatuity\Monitor\Model\Resource\Exception\Collection;

class Status
{
    /** @var \Creatuity\Monitor\Model\Resource\Exception\Collection */
    protected $collection;


	public function __construct(
		\Creatuity\Monitor\Model\Resource\Exception\Collection $collection
	) {
		$this->collection = $collection;
	}


    private function getExceptions() {
        $total = 0;
        foreach ($this->collection->addFilter('state', Exception::STATE_NEW)->setOrder('current_count', Collection::SORT_ORDER_DESC)->getItems() as $exception) {
            /** @var $exception \Creatuity\Monitor\Model\Exception */
            $total += $exception->getCurrentCount();
        }
        return $total;
    }

    /**
     * @return array
     */
    public function getStatus() {

        return [
            'exceptions' => $this->getExceptions() < 10
        ];
    }

}