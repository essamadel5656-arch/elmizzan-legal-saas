<?php

function replaceArabicStrings($content) {
    $map = [
        'إضافة ملف قضية جديد' => "Add New Case File",
        'سجل بيانات القضية والطرف الموكل، الخصم، والماليات المترتبة عليها بنظام Livewire التفاعلي' => "Register the case details, principal party, opponent, and resulting financials using the interactive Livewire system",
        'رجوع للقائمة' => "Back to List",
        'يرجى مراجعة الأخطاء التالية:' => "Please review the following errors:",
        'بيانات العميل الموكل' => "Client/Principal Data",
        'عميل جديد' => "New Client",
        'اختر العميل الموكل' => "Select Client/Principal",
        '-- اختر العميل من القائمة --' => "-- Select Client from List --",
        'بدون هاتف' => "No Phone",
        'سيتم السحب تلقائياً' => "Will be fetched automatically",
        'العنوان المسجل' => "Registered Address",
        'الفريق القانوني المسؤول' => "Responsible Legal Team",
        'المحامي المسؤول عن القضية' => "Lawyer Responsible for Case",
        'اختر المحامي المسؤول...' => "Select Responsible Lawyer...",
        'تفاصيل القضية والماليات' => "Case Details and Financials",
        'مثال: 2525 أو 2026/123' => "Example: 2525 or 2026/123",
        'حالة القضية الحالية' => "Current Case Status",
        'اختر الجهة...' => "Select Jurisdiction...",
        'درجة التقاضي / المحكمة' => "Court Level / Court",
        'اختر درجة التقاضي...' => "Select Court Level...",
        'اختر المحكمة...' => "Select Court...",
        'الدائرة' => "Circuit",
        'مثال: الدائرة الثالثة مدني' => "Example: Third Civil Circuit",
        'الإجراء السابق أو الموقف الحالي للدعوى' => "Previous Procedure or Current Case Status",
        'مثال: تقديم مستندات، إعادة إعلان...' => "Example: Submit documents, re-announce...",
        'الأتعاب المتفق عليها (رسمية)' => "Agreed Legal Fees (Official)",
        'مثال: 1500' => "Example: 1500",
        'مثال: 1000' => "Example: 1000",
        'مثال: 300' => "Example: 300",
        'المصاريف الإدارية والرسوم (ر.ع.)' => "Administrative Expenses and Fees (OMR)",
        'مثال: 50' => "Example: 50",
        'موضوع الدعوى والخصم' => "Case Subject and Opponent",
        'ملخص وقائع الدعوى' => "Summary of Case Facts",
        'شرح تفصيلي للوقائع...' => "Detailed explanation of facts...",
        'اسم الخصم بالكامل' => "Opponent Full Name",
        'رقم هاتف الخصم' => "Opponent Phone Number",
        'الرقم القومي للخصم' => "Opponent National ID",
        'عنوان الخصم' => "Opponent Address",
        'المرفقات والتوكيلات' => "Attachments and Procurations",
        'بيانات أو رقم التوكيل الرسمي الخاص بالقضية' => "Data or official procuration number for the case",
        'مثال: توكيل رقم 1234 ص توثيق النموذجي' => "Example: Procuration No. 1234 Model Notarization",
        'الحكم النهائي أو القرار (في حال كانت منتهية)' => "Final Judgment or Decision (if closed)",
        'مثال: قبول الدعوى شكلاً وفي الموضوع...' => "Example: Accept lawsuit in form and substance...",
        'ملاحظات إضافية على القضية' => "Additional Notes on Case",
        'أي تفاصيل أو ملاحظات أخرى للمكتب...' => "Any other details or notes for the office...",
        'تم تجهيز الملف:' => "File prepared:",
        'انقر لتغيير الملف المرفق' => "Click to change attached file",
        'اضغط لرفع المرفقات (PDF, Word, Images)' => "Click to upload attachments (PDF, Word, Images)",
        'الحد الأقصى 2 ميجابايت' => "Maximum 2 MB",
        'جاري رفع ومعالجة الملف...' => "Uploading and processing file...",
        'إلغاء' => "Cancel",
        'حفظ القضية في النظام' => "Save Case in System",
        'جاري الحفظ...' => "Saving...",
        'تعديل ملف القضية' => "Edit Case File",
        'تحديث بيانات القضية والطرف الموكل والخصم وتغيير حالتها في النظام' => "Update case details, principal party, opponent, and change its status in the system",
        'حفظ التعديلات' => "Save Changes",
    ];

    $en = file_exists('lang/en.json') ? json_decode(file_get_contents('lang/en.json'), true) : [];
    $ar = file_exists('lang/ar.json') ? json_decode(file_get_contents('lang/ar.json'), true) : [];

    foreach ($map as $k => $v) {
        $en[$k] = $v;
        $ar[$k] = $k;
        
        // Match string either in HTML >...< or placeholder="..." or value="..."
        // This is safe if done precisely. We'll use exact replacement but with Laravel __()
        $content = str_replace(">".$k."<", ">{{ __('".$k."') }}<", $content);
        $content = str_replace(">'".$k."'<", ">{{ __('".$k."') }}<", $content);
        $content = str_replace(" placeholder=\"".$k."\"", " placeholder=\"{{ __('".$k."') }}\"", $content);
        $content = str_replace(" value=\"".$k."\"", " value=\"{{ __('".$k."') }}\"", $content);
        // Also handle the raw string outside tags if necessary, but str_replace should be careful.
    }
    
    // Additional exact str_replaces for specific lines:
    $content = str_replace("{{ \$selectedClient->phone ?? 'سيتم السحب تلقائياً' }}", "{{ \$selectedClient->phone ?? __('سيتم السحب تلقائياً') }}", $content);
    $content = str_replace("{{ \$selectedClient->nid ?? 'سيتم السحب تلقائياً' }}", "{{ \$selectedClient->nid ?? __('سيتم السحب تلقائياً') }}", $content);
    $content = str_replace("{{ \$selectedClient->address ?? 'سيتم السحب تلقائياً' }}", "{{ \$selectedClient->address ?? __('سيتم السحب تلقائياً') }}", $content);
    $content = str_replace("{{ \$c->name }} ({{ \$c->phone ?: 'بدون هاتف' }})", "{{ \$c->name }} ({{ \$c->phone ?: __('بدون هاتف') }})", $content);
    
    file_put_contents('lang/en.json', json_encode($en, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    file_put_contents('lang/ar.json', json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    return $content;
}

$files = [
    'resources/views/livewire/cases/case-create.blade.php',
    'resources/views/livewire/cases/case-edit.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = replaceArabicStrings($content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
