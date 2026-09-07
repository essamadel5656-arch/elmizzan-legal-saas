<?php
$en = file_exists('lang/en.json') ? json_decode(file_get_contents('lang/en.json'), true) : [];
$ar = file_exists('lang/ar.json') ? json_decode(file_get_contents('lang/ar.json'), true) : [];

$translations = [
    'غير محدد' => 'N/A',
    'لم يتم تسجيل أي قضايا في النظام حتى الآن.' => 'No cases have been registered yet.',
    'إضافة قضية جديدة' => 'Add New Case',
    'التقويم الشهري' => 'Monthly Calendar',
    'إضافة موعد' => 'Add appointment',
    'الشهر السابق' => 'Previous month',
    'الشهر التالي' => 'Next month',
    'أحد' => 'Sun',
    'إثنين' => 'Mon',
    'ثلاثاء' => 'Tue',
    'أربعاء' => 'Wed',
    'خميس' => 'Thu',
    'جمعة' => 'Fri',
    'سبت' => 'Sat',
    'طلبات المصاريف بانتظار الموافقة' => 'Pending Expense Claims',
    'القضية' => 'Case',
    'المبلغ' => 'Amount',
    'التصنيف' => 'Category',
    'مقدَّم بواسطة' => 'Submitted By',
    'ملاحظات' => 'Notes',
    'الإجراء' => 'Action',
    'قبول' => 'Approve',
    'رفض' => 'Reject',
    'أفضل المحامين أداءً' => 'Top Performing Lawyers',
    'نقطة' => 'pts',
    'نشطة' => 'active',
    'منتهية' => 'closed',
    'موكل' => 'clients',
    'معدل تسجيل القضايا خلال العام' => 'Annual Case Registration',
    'توزيع القضايا حسب الحالة' => 'Cases by Status',
    'يناير' => 'January',
    'فبراير' => 'February',
    'مارس' => 'March',
    'أبريل' => 'April',
    'مايو' => 'May',
    'يونيو' => 'June',
    'يوليو' => 'July',
    'أغسطس' => 'August',
    'سبتمبر' => 'September',
    'أكتوبر' => 'October',
    'نوفمبر' => 'November',
    'ديسمبر' => 'December',
    'لا توجد بيانات لعرضها حتى الآن' => 'No data available yet',
    'عدد القضايا المضافة' => 'Cases Added'
];

foreach ($translations as $arText => $engText) {
    if (!isset($ar[$arText])) {
        $ar[$arText] = $arText;
    }
    if (!isset($en[$arText])) {
        $en[$arText] = $engText;
    }
}

file_put_contents('lang/en.json', json_encode($en, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
file_put_contents('lang/ar.json', json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo "Translations updated.\n";
