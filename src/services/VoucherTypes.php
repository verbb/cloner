<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use verbb\giftvoucher\models\VoucherType;

class VoucherTypes extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/voucher-type';
    public static string $id = 'types';
    public static string $matchedRoute = 'gift-voucher/voucher-types';
    public static string $pluginHandle = 'gift-voucher';
    public static string $title = 'Voucher Type';


    // Public Methods
    // =========================================================================

    public function setupClonedVoucherType(VoucherType $oldVoucherType, string $name, string $handle): VoucherType
    {
        $voucherType = clone $oldVoucherType;
        $voucherType->id = null;
        $voucherType->uid = null;
        $voucherType->fieldLayoutId = null;
        $voucherType->name = $name;
        $voucherType->handle = $handle;

        $voucherType->getBehavior('voucherFieldLayout')->setFieldLayout($this->getFieldLayout($oldVoucherType->getVoucherFieldLayout()));

        $allSiteSettings = [];

        foreach ($oldVoucherType->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = clone $oldSiteSettings;
            $siteSettings->id = null;
            $siteSettings->voucherTypeId = null;

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $voucherType->setSiteSettings($allSiteSettings);

        return $voucherType;
    }

}
