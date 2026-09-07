<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيصال مصروف #{{ $expense->id }}</title>
    <style>
        @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f1f5f9; margin: 0; padding: 2rem; color: #1e293b; direction: rtl; }
        .voucher { max-width: 680px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
        .voucher-header { background: linear-gradient(135deg, #1e293b, #0f172a); padding: 2rem; text-align: center; }
        .voucher-header h1 { color: #d4af37; font-size: 1.6rem; margin: 0 0 4px; }
        .voucher-header p  { color: #94a3b8; margin: 0; font-size: .9rem; }
        .voucher-badge { display:inline-block; margin-top:.75rem; background:rgba(34,197,94,.15); color:#4ade80; padding:.3rem 1rem; border-radius:20px; font-size:.85rem; font-weight:700; }
        .voucher-body { padding: 2rem; }
        .voucher-row { display: flex; justify-content: space-between; padding: .7rem 0; border-bottom: 1px solid #f1f5f9; }
        .voucher-row:last-child { border-bottom: none; }
        .voucher-label { font-size: .85rem; color: #64748b; }
        .voucher-value { font-size: .9rem; font-weight: 700; color: #1e293b; }
        .amount-box { background: linear-gradient(135deg, #1e293b, #0f172a); color: #d4af37; padding: 1.5rem; text-align: center; margin: 1.5rem 0; border-radius: 12px; }
        .amount-box .amount { font-size: 2.2rem; font-weight: 900; }
        .amount-box .label { font-size: .85rem; color: #94a3b8; margin-top: .25rem; }
        .voucher-footer { background: #f8fafc; padding: 1.25rem 2rem; font-size: .8rem; color: #94a3b8; text-align: center; }
        .btn-print { display: none; }
        @media screen { .btn-print { display: inline-block; background: #1e293b; color: #d4af37; border: none; padding: .75rem 2rem; border-radius: 8px; font-size: .95rem; font-weight: 700; cursor: pointer; margin: 1rem auto; font-family: inherit; } }
    </style>
</head>
<body>
    <div class="voucher">
        <div class="voucher-header">
            <h1>⚖️ {{ firm_name() }}</h1>
            <p>إيصال مصروف رسمي</p>
            <div class="voucher-badge">{{ $expense->status === 'approved' ? '✅ معتمد' : ($expense->status === 'pending' ? '⏳ بانتظار الموافقة' : '❌ مرفوض') }}</div>
        </div>
        <div class="voucher-body">
            <div class="amount-box">
                <div class="amount">{{ number_format($expense->amount, 2) }} ج.م.</div>
                <div class="label">مبلغ المصروف</div>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">رقم الإيصال</span>
                <span class="voucher-value">#{{ $expense->id }}</span>
            </div>
            <div class="voucher-row">
                <span class="voucher-label">القضية</span>
                <span class="voucher-value">{{ $expense->case?->case_number ?? '—' }}</span>
            </div>
            <div class="voucher-row">
                <span class="voucher-label">تصنيف المصروف</span>
                <span class="voucher-value">{{ $expense->category }}</span>
            </div>
            <div class="voucher-row">
                <span class="voucher-label">مقدَّم بواسطة</span>
                <span class="voucher-value">{{ $expense->submittedBy?->name ?? '—' }}</span>
            </div>
            @if($expense->approved_by)
            <div class="voucher-row">
                <span class="voucher-label">تمت الموافقة بواسطة</span>
                <span class="voucher-value">{{ $expense->approvedBy?->name ?? '—' }}</span>
            </div>
            @endif
            @if($expense->notes)
            <div class="voucher-row">
                <span class="voucher-label">ملاحظات</span>
                <span class="voucher-value">{{ $expense->notes }}</span>
            </div>
            @endif
            <div class="voucher-row">
                <span class="voucher-label">تاريخ الإصدار</span>
                <span class="voucher-value">{{ $expense->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        <div class="voucher-footer">
            هذا الإيصال صادر إلكترونياً من نظام {{ firm_name() }} · {{ date('Y') }}
        </div>
    </div>
    <div style="text-align:center;margin-top:1rem;">
        <button class="btn-print" onclick="window.print()">🖨️ طباعة الإيصال</button>
    </div>
</body>
</html>
