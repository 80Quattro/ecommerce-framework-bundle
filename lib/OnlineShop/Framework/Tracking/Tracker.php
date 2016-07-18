<?php
/**
 * Pimcore
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2015 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace OnlineShop\Framework\Tracking;

use Pimcore\Google\Analytics;


abstract class Tracker implements ITracker
{
    /** @var ITrackingItemBuilder */
    protected $trackingItemBuilder;

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @param ITrackingItemBuilder $trackingItemBuilder
     */
    public function __construct(ITrackingItemBuilder $trackingItemBuilder)
    {
        $this->trackingItemBuilder = $trackingItemBuilder;
    }

    /**
     * @return ITrackingItemBuilder
     */
    public function getTrackingItemBuilder()
    {
        return $this->trackingItemBuilder;
    }

    /**
     * Build a view
     *
     * @return \Zend_View
     */
    protected function buildView()
    {
        $view = new \Zend_View();
        $view->setBasePath(PIMCORE_DOCUMENT_ROOT . '/plugins/EcommerceFramework/views');

        return $view;
    }

    /**
     * View script prefix
     *
     * @return mixed
     */
    abstract protected function getViewScriptPrefix();

    /**
     * Get path to view script
     *
     * @param $name
     * @return string
     */
    protected function getViewScript($name)
    {
        return sprintf('tracking/%s/%s.js.php', $this->getViewScriptPrefix(), $name);
    }

    /**
     * Remove null values from an object, keep protected keys in any case
     *
     * @param $data
     * @param array $protectedKeys
     * @return array
     */
    protected function filterNullValues($data, $protectedKeys = [])
    {
        $result = [];
        foreach ($data as $key => $value) {
            $isProtected = in_array($key, $protectedKeys);
            if (null !== $value || $isProtected) {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    private $dependenciesIncluded = false;

    /**
     * Include all defined dependencies of this tracker.
     */
    public function includeDependencies()
    {
        if (!$this->dependenciesIncluded) {
            if ($dependencies = $this->dependencies) {
                foreach ($dependencies as $dependency) {
                    Analytics::addAdditionalCode("ga('require', '" . $dependency . "')", "beforePageview");
                }
            }
            $this->dependenciesIncluded = true;
        }
    }

}
