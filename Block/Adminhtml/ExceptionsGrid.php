<?php
/**
 * Created by PhpStorm.
 * User: fwawrzak
 * Date: 11.12.18
 * @license http://creatuity.com/license
 * @copyright Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 */

namespace Creatuity\Monitor\Block\Adminhtml;


class ExceptionsGrid extends \Magento\Backend\Block\Widget\Grid
{
    protected function _setCollectionOrder($column)
    {
        $collection = $this->getCollection();
        if ($collection) {
            $collection->setOrder('state', 'ASC');
            $collection->setOrder('current_count', 'DESC');
        }
        return $this;
    }

}