<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class ProfileCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/profile';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/profile/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    public $profileClass = '';

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'class'   => ['format' => $this->profileClass],
        ]);
    }
}
