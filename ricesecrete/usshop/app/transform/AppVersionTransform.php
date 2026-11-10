<?php

namespace app\transform;

class AppVersionTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'version_code'    => $item['version_code'],
            'version_name'    => $item['version_name'],
            'bulletin'        => $item['bulletin'],
            'platform_type'   => $item['platform_type'],
            'is_force_update' => $item['is_force_update'],
            'is_wgt'          => $item['is_wgt'],
            'url'             => $item['url'],
        ];
    }
}
