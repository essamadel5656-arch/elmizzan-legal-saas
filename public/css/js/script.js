/* ============================================
   نظام المكتب القانوني — script.js
   يتولى: تبديل المظهر، تخزين القضايا (localStorage)،
   عرض الجدول، البحث/التصفية، التحقق من النموذج.
   ============================================ */

/* --------------------------------------------
   مساعدات التخزين: قراءة وحفظ مصفوفة القضايا
   -------------------------------------------- */
const STORAGE_KEY = 'qadi_cases_v2';

function loadCases() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return seedDefault();
    const parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? parsed : seedDefault();
  } catch {
    return seedDefault();
  }
}

function saveCases(cases) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(cases));
}

/* --------------------------------------------
   بيانات افتراضية: قضايا أمثلة عند أول زيارة
   -------------------------------------------- */
function seedDefault() {
  const seed = [
    { id: cryptoId(), caseName: 'محمد ضد أحمد',          clientName: 'سارة محمد',       caseType: 'Civil',     status: 'Active',  startDate: '2026-01-12', notes: 'نزاع على حدود الملكية.' },
    { id: cryptoId(), caseName: 'اندماج شركة آكمي',     clientName: 'شركة آكمي',       caseType: 'Corporate', status: 'Pending', startDate: '2026-02-04', notes: 'في انتظار الموافقة التنظيمية.' },
    { id: cryptoId(), caseName: 'تركة المرحوم خالد',    clientName: 'مريم خالد',        caseType: 'Family',    status: 'Closed',  startDate: '2025-09-22', notes: 'تم توزيع الميراث.' }
  ];
  saveCases(seed);
  return seed;
}

function cryptoId() {
  if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
  return 'id-' + Math.random().toString(36).slice(2, 10);
}

/* --------------------------------------------
   تبديل المظهر: حفظ تفضيل المستخدم بين الفاتح والداكن
   -------------------------------------------- */
const THEME_KEY = 'qadi_theme';

function applyTheme(theme) {
  if (theme === 'light') {
    document.body.classList.add('light-mode');
  } else {
    document.body.classList.remove('light-mode');
  }
  const label = document.getElementById('themeLabel');
  if (label) label.textContent = theme === 'light' ? 'الوضع الداكن' : 'الوضع الفاتح';
}

function initTheme() {
  const saved = localStorage.getItem(THEME_KEY) || 'dark';
  applyTheme(saved);

  const btn = document.getElementById('themeToggle');
  if (!btn) return;
  btn.addEventListener('click', () => {
    const next = document.body.classList.contains('light-mode') ? 'dark' : 'light';
    localStorage.setItem(THEME_KEY, next);
    applyTheme(next);
  });
}

/* --------------------------------------------
   إشعار صغير: عرض رسالة منبثقة قصيرة
   -------------------------------------------- */
function toast(message) {
  let el = document.querySelector('.toast');
  if (!el) {
    el = document.createElement('div');
    el.className = 'toast';
    document.body.appendChild(el);
  }
  el.textContent = message;
  requestAnimationFrame(() => el.classList.add('show'));
  clearTimeout(el._t);
  el._t = setTimeout(() => el.classList.remove('show'), 2200);
}

/* --------------------------------------------
   تنسيق التاريخ + ترجمة الحالة وأنواع القضايا
   -------------------------------------------- */
function formatDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso);
  if (isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
}

const STATUS_AR = {
  Active:  'نشطة',
  Pending: 'معلّقة',
  Closed:  'مغلقة'
};

const TYPE_AR = {
  'Civil':                 'مدنية',
  'Criminal':              'جنائية',
  'Family':                'أحوال شخصية',
  'Corporate':             'شركات',
  'Real Estate':           'عقارية',
  'Labor':                 'عمالية',
  'Intellectual Property': 'ملكية فكرية'
};

/* --------------------------------------------
   حماية HTML: تجنّب الحقن في الجدول
   -------------------------------------------- */
function esc(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

/* --------------------------------------------
   صفحة القضايا: عرض الجدول، الإحصائيات، التصفية، الحذف
   -------------------------------------------- */
function initCasesPage() {
  const body = document.getElementById('casesBody');
  if (!body) return; // لسنا في صفحة القضايا

  const empty        = document.getElementById('emptyState');
  const emptyTitle   = document.getElementById('emptyTitle');
  const emptyMsg     = document.getElementById('emptyMsg');

  // مصادر البحث: الشريط العلوي + حقل البحث السريع في الجدول
  const topSearch    = document.getElementById('searchInput');
  const tableSearch  = document.getElementById('filterSearch');
  const filterStatus = document.getElementById('filterStatus');

  const elTotal   = document.getElementById('statTotal');
  const elActive  = document.getElementById('statActive');
  const elPending = document.getElementById('statPending');
  const elClosed  = document.getElementById('statClosed');

  /* ------------------------------------------
     render(): إعادة رسم الجدول بعد كل تغيير
     ------------------------------------------ */
  function render() {
    const cases = loadCases();

    // تحديث عدّادات الإحصائيات (تعتمد على الكل، بغضّ النظر عن الفلتر)
    elTotal.textContent   = cases.length;
    elActive.textContent  = cases.filter(c => c.status === 'Active').length;
    elPending.textContent = cases.filter(c => c.status === 'Pending').length;
    elClosed.textContent  = cases.filter(c => c.status === 'Closed').length;

    // قراءة قيم الفلاتر الحالية
    const q      = ((topSearch?.value || '') + ' ' + (tableSearch?.value || '')).toLowerCase().trim();
    const status = filterStatus?.value || 'all';
    const isFiltering = q || status !== 'all';

    // تطبيق الفلاتر على القضايا
    const visible = cases.filter(c => {
      const matchesStatus = status === 'all' || c.status === status;
      const statusAr = STATUS_AR[c.status] || c.status;
      const typeAr   = TYPE_AR[c.caseType]  || c.caseType || '';
      const matchesSearch = !q
        || c.caseName.toLowerCase().includes(q)
        || c.clientName.toLowerCase().includes(q)
        || statusAr.includes(q)
        || typeAr.toLowerCase().includes(q);
      return matchesStatus && matchesSearch;
    });

    // عرض الحالة الفارغة مع رسالة مناسبة لكل حالة
    if (visible.length === 0) {
      body.innerHTML = '';
      empty.classList.remove('hidden');
      if (isFiltering) {
        // لا توجد نتائج بسبب الفلتر أو البحث
        emptyTitle.textContent = 'لا توجد قضايا بهذه الحالة';
        emptyMsg.textContent   = 'جرّب تغيير فلتر الحالة أو كلمة البحث.';
      } else {
        // لا توجد قضايا أصلاً
        emptyTitle.textContent = 'لا توجد قضايا';
        emptyMsg.textContent   = 'انقر على "إضافة قضية جديدة" لإنشاء أول قضية.';
      }
      return;
    }
    empty.classList.add('hidden');

    // بناء صفوف الجدول مع أيقونات إجراءات مُحسَّنة (view / edit / danger)
    body.innerHTML = visible.map(c => `
      <tr data-id="${esc(c.id)}">
        <td>
          <div style="font-weight:600;">${esc(c.caseName)}</div>
          <div class="muted" style="font-size:12px;">${esc(TYPE_AR[c.caseType] || c.caseType || '')}</div>
        </td>
        <td>${esc(c.clientName)}</td>
        <td><span class="badge ${c.status.toLowerCase()}">${esc(STATUS_AR[c.status] || c.status)}</span></td>
        <td>${formatDate(c.startDate)}</td>
        <td class="ta-right">
          <div class="row-actions">
            <button class="icon-btn view"   title="عرض التفاصيل"  data-action="view">👁</button>
            <button class="icon-btn edit"   title="تعديل الحالة"  data-action="edit">✎</button>
            <button class="icon-btn danger" title="حذف القضية"    data-action="delete">🗑</button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  /* ------------------------------------------
     تفويض النقرات: عرض / تعديل / حذف
     ------------------------------------------ */
  body.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const tr = btn.closest('tr');
    const id = tr?.dataset.id;
    if (!id) return;
    const action = btn.dataset.action;
    const cases = loadCases();
    const item = cases.find(c => c.id === id);
    if (!item) return;

    if (action === 'view') {
      alert(
        `القضية: ${item.caseName}\n` +
        `العميل: ${item.clientName}\n` +
        `النوع: ${TYPE_AR[item.caseType] || item.caseType}\n` +
        `الحالة: ${STATUS_AR[item.status] || item.status}\n` +
        `تاريخ البدء: ${formatDate(item.startDate)}\n\n` +
        `الملاحظات:\n${item.notes || '—'}`
      );
    } else if (action === 'edit') {
      // تعديل سريع: تحديث الحالة فقط
      const current = STATUS_AR[item.status] || item.status;
      const input = prompt('تحديث الحالة (نشطة / معلّقة / مغلقة):', current);
      if (!input) return;
      const map = { 'نشطة': 'Active', 'معلّقة': 'Pending', 'معلقة': 'Pending', 'مغلقة': 'Closed' };
      const next = map[input.trim()];
      if (next) {
        item.status = next;
        saveCases(cases);
        render();
        toast('تم تحديث القضية.');
      }
    } else if (action === 'delete') {
      if (confirm(`هل تريد حذف القضية "${item.caseName}"؟ لا يمكن التراجع عن هذا الإجراء.`)) {
        const next = cases.filter(c => c.id !== id);
        saveCases(next);
        render();
        toast('تم حذف القضية.');
      }
    }
  });

  // ربط جميع الفلاتر بدالة render مباشرةً
  topSearch?.addEventListener('input', render);
  tableSearch?.addEventListener('input', render);
  filterStatus?.addEventListener('change', render);

  render();
}

/* --------------------------------------------
   صفحة إضافة قضية: التحقق وتخزين قضية جديدة
   -------------------------------------------- */
function initAddCasePage() {
  const form = document.getElementById('caseForm');
  if (!form) return; // لسنا في صفحة الإضافة

  function setError(name, message) {
    const field = form.querySelector(`[name="${name}"]`)?.closest('.field');
    const err   = form.querySelector(`[data-error-for="${name}"]`);
    if (field) field.classList.toggle('invalid', !!message);
    if (err)   err.textContent = message || '';
  }

  // مسح الخطأ عند الكتابة
  form.addEventListener('input', (e) => {
    if (e.target.name) setError(e.target.name, '');
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const data = {
      caseName:   form.caseName.value.trim(),
      clientName: form.clientName.value.trim(),
      caseType:   form.caseType.value,
      status:     form.status.value,
      startDate:  form.startDate.value,
      notes:      form.notes.value.trim()
    };

    // التحقق من الحقول المطلوبة
    let ok = true;
    const required = ['caseName','clientName','caseType','status','startDate'];
    required.forEach(name => {
      if (!data[name]) {
        setError(name, 'هذا الحقل مطلوب.');
        ok = false;
      } else {
        setError(name, '');
      }
    });

    if (!ok) {
      toast('يرجى تعبئة الحقول المطلوبة.');
      return;
    }

    // حفظ القضية الجديدة
    const cases = loadCases();
    cases.unshift({ id: cryptoId(), ...data });
    saveCases(cases);

    toast('تم حفظ القضية بنجاح.');

    // العودة إلى قائمة القضايا بعد لحظة
    setTimeout(() => { window.location.href = '/index.html'; }, 700);
  });
}

/* --------------------------------------------
   التشغيل: تهيئة الصفحة عند جاهزية المستند
   -------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initCasesPage();
  initAddCasePage();
});
