<?php

declare(strict_types=1);

return [
    'missing_headers' => 'رأس التوقيع أو الطابع الزمني (HMAC) مفقود.',
    'expired_timestamp' => 'الطابع الزمني للطلب خارج نطاق التسامح المسموح به.',
    'invalid_signature' => 'توقيع HMAC غير مطابق.',
    'replay_detected' => 'تمت معالجة هذا الطلب من قبل.',
];
