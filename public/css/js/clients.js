/* ============================================
   clients.js — إدارة العملاء
   ============================================ */

/* --------------------------------------------
   مفتاح التخزين وبيانات تجريبية
   -------------------------------------------- */
const CLIENTS_KEY = 'qadi_clients_v1';

function seedClients() {
  return [
    {
      id: cryptoId(),
      name: 'محمد أحمد السيد',
      type: 'individual',
      phone: '01012345678',
      email: 'mohamed.ahmed@email.com',
      nationalId: '29801011234567',
      company: '',
      address: 'القاهرة، مصر الجديدة',
      status: 'active',
      addedDate: '2025-11-10',
      lastContact: '2026-04-20',
      notes: 'عميل منتظم منذ ٣ سنوات، يُفضّل التواصل عبر الهاتف.',
      cases: [
        { name: 'محمد ضد أحمد', type: 'مدنية', status: 'Active', date: '2026-01-12' },
        { name: 'قضية إرث العائلة', type: 'أحوال شخصية', status: 'Closed', date: '2025-05-01' }
      ],
      interactions: [
        { type: 'call', label: 'مكالمة هاتفية', detail: 'مناقشة آخر جلسات قضية محمد ضد أحمد', date: '2026-04-20', time: '10:30 ص' },
        { type: 'session', label: 'جلسة مع المحامي', detail: 'مراجعة المستندات المقدمة للمحكمة', date: '2026-03-15', time: '02:00 م' },
        { type: 'doc', label: 'رفع مستند', detail: 'رفع صورة الحكم الابتدائي', date: '2026-02-28', time: '09:00 ص' },
        { type: 'payment', label: 'دفع أتعاب', detail: 'سداد أتعاب الشهر الثاني', date: '2026-02-01', time: '11:00 ص' }
      ]
    },
    {
      id: cryptoId(),
      name: 'شركة النور للمقاولات',
      type: 'company',
      phone: '0221234567',
      email: 'info@alnour.com',
      nationalId: '',
      company: 'شركة النور للمقاولات',
      address: 'الجيزة، المهندسين، شارع جامعة الدول',
      status: 'vip',
      addedDate: '2025-06-01',
      lastContact: '2026-05-02',
      notes: 'عميل شركة كبير، يحتاج متابعة دورية كل أسبوعين.',
      cases: [
        { name: 'اندماج شركة آكمي', type: 'شركات', status: 'Pending', date: '2026-02-04' }
      ],
      interactions: [
        { type: 'session', label: 'اجتماع مجلس الإدارة', detail: 'مراجعة عقد الاندماج مع الطرف الثاني', date: '2026-05-02', time: '03:00 م' },
        { type: 'doc', label: 'رفع مستند', detail: 'رفع عقد الاندماج الأولي', date: '2026-04-10', time: '10:00 ص' },
        { type: 'payment', label: 'دفع أتعاب', detail: 'سداد الدفعة الأولى من الأتعاب', date: '2026-03-01', time: '12:00 م' },
        { type: 'call', label: 'مكالمة هاتفية', detail: 'متابعة سير الإجراءات مع الجهات الرقابية', date: '2026-02-20', time: '09:30 ص' }
      ]
    },
    {
      id: cryptoId(),
      name: 'مريم خالد إبراهيم',
      type: 'individual',
      phone: '01198765432',
      email: 'mariam.k@gmail.com',
      nationalId: '30005152345678',
      company: '',
      address: 'الإسكندرية، سيدي بشر',
      status: 'inactive',
      addedDate: '2024-09-15',
      lastContact: '2025-09-22',
      notes: 'تم إغلاق القضية. العميلة لم تتواصل منذ ٧ أشهر.',
      cases: [
        { name: 'تركة المرحوم خالد', type: 'أحوال شخصية', status: 'Closed', date: '2025-09-22' }
      ],
      interactions: [
        { type: 'session', label: 'جلسة ختامية', detail: 'توزيع الميراث وإغلاق الملف', date: '2025-09-22', time: '01:00 م' },
        { type: 'payment', label: 'دفع أتعاب', detail: 'سداد الأتعاب النهائية', date: '2025-09-22', time: '02:30 م' },
        { type: 'doc', label: 'رفع مستند', detail: 'إيداع الحكم النهائي بالمحكمة', date: '2025-08-30', time: '10:00 ص' }
      ]
    },
    {
      id: cryptoId(),
      name: 'مجموعة البنيان العقارية',
      type: 'company',
      phone: '0231112233',
      email: 'legal@albunyan.com',
      nationalId: '',
      company: 'مجموعة البنيان العقارية',
      address: 'القاهرة، التجمع الخامس',
      status: 'active',
      addedDate: '2026-01-20',
      lastContact: '2026-05-08',
      notes: 'شركة عقارية كبيرة، لديها قضايا متعددة مرتقبة.',
      cases: [],
      interactions: [
        { type: 'call', label: 'مكالمة استفسار', detail: 'الاستفسار عن تمثيل قانوني في قضايا الإيجار', date: '2026-05-08', time: '11:00 ص' },
        { type: 'session', label: 'اجتماع تعريفي', detail: 'عرض خدمات المكتب وإبرام عقد الاستشارة', date: '2026-02-05', time: '10:00 ص' }
      ]
    }
  ];
}

/* --------------------------------------------
   التخزين: تحميل وحفظ العملاء
   -------------------------------------------- */
function loadClients() {
  try {
    const raw = localStorage.getItem(CLIENTS_KEY);
    if (!raw) { const s = seedClients(); saveClients(s); return s; }
    const p = JSON.parse(raw);
    return Array.isArray(p) ? p : (saveClients(seedClients()), seedClients());
  } catch { return seedClients(); }
}

function saveClients(data) {
  localStorage.setItem(CLIENTS_KEY, JSON.stringify(data));
}

/* --------------------------------------------
   دوال مساعدة
   -------------------------------------------- */
function cryptoId() {
  return window.crypto?.randomUUID?.() || 'id-' + Math.random().toString(36).slice(2, 10);
}

function formatDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso);
  if (isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
}

function esc(s) {
  return String(s ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

/* التسميات العربية */
const TYPE_LABEL   = { individual: 'فرد', company: 'شركة' };
const STATUS_LABEL = { active: 'نشط', vip: 'مهم ⭐', inactive: 'متوقف' };
const TYPE_CLASS   = { individual: 'badge-blue', company: 'badge-purple' };
const STATUS_CLASS = { active: 'active', vip: 'vip', inactive: 'closed' };

const INTERACTION_ICON = { call: '📞', session: '⚖️', doc: '📄', payment: '💰' };

/* initials لصورة العميل */
function initials(name) {
  const parts = name.trim().split(' ');
  if (parts.length >= 2) return parts[0][0] + parts[1][0];
  return parts[0].slice(0, 2);
}

/* toast (موجود في script.js لكن مؤمّن هنا أيضاً) */
function clientToast(msg) {
  if (typeof toast === 'function') { toast(msg); return; }
  let el = document.querySelector('.toast');
  if (!el) { el = document.createElement('div'); el.className = 'toast'; document.body.appendChild(el); }
  el.textContent = msg;
  requestAnimationFrame(() => el.classList.add('show'));
  clearTimeout(el._t);
  el._t = setTimeout(() => el.classList.remove('show'), 2200);
}

/* ============================================
   صفحة العملاء — initClientsPage
   ============================================ */
function initClientsPage() {
  const body         = document.getElementById('clientsBody');
  if (!body) return; // لسنا في صفحة العملاء

  const emptyEl      = document.getElementById('emptyState');
  const emptyTitle   = document.getElementById('emptyTitle');
  const emptyMsg     = document.getElementById('emptyMsg');
  const searchInput  = document.getElementById('searchInput');
  const filterType   = document.getElementById('filterType');
  const filterStatus = document.getElementById('filterStatus');

  const elTotal     = document.getElementById('statTotal');
  const elActive    = document.getElementById('statActive');
  const elCompanies = document.getElementById('statCompanies');
  const elInactive  = document.getElementById('statInactive');

  /* ------------------------------------------
     render: إعادة رسم جدول العملاء
     ------------------------------------------ */
  function render() {
    const clients = loadClients();

    // إحصائيات
    elTotal.textContent     = clients.length;
    elActive.textContent    = clients.filter(c => c.status === 'active' || c.status === 'vip').length;
    elCompanies.textContent = clients.filter(c => c.type === 'company').length;
    elInactive.textContent  = clients.filter(c => c.status === 'inactive').length;

    // فلترة
    const q      = (searchInput?.value || '').toLowerCase().trim();
    const type   = filterType?.value   || 'all';
    const status = filterStatus?.value || 'all';
    const isFiltering = q || type !== 'all' || status !== 'all';

    const visible = clients.filter(c => {
      const matchType   = type   === 'all' || c.type   === type;
      const matchStatus = status === 'all' || c.status === status;
      const matchSearch = !q
        || c.name.toLowerCase().includes(q)
        || (c.phone  || '').includes(q)
        || (c.company|| '').toLowerCase().includes(q)
        || (c.email  || '').toLowerCase().includes(q);
      return matchType && matchStatus && matchSearch;
    });

    // حالة فارغة
    if (visible.length === 0) {
      body.innerHTML = '';
      emptyEl.classList.remove('hidden');
      emptyTitle.textContent = isFiltering ? 'لا توجد نتائج' : 'لا يوجد عملاء';
      emptyMsg.textContent   = isFiltering ? 'جرّب تغيير الفلتر أو كلمة البحث.' : 'انقر على "إضافة عميل جديد" لإضافة أول عميل.';
      return;
    }
    emptyEl.classList.add('hidden');

    // رسم الصفوف
    body.innerHTML = visible.map(c => `
      <tr data-id="${esc(c.id)}">
        <td>
          <div class="client-name-cell">
            <div class="client-avatar-sm">${esc(initials(c.name))}</div>
            <div>
              <div style="font-weight:600;">${esc(c.name)}</div>
              <div class="muted" style="font-size:11px;">${esc(c.email || '—')}</div>
            </div>
          </div>
        </td>
        <td><span class="badge ${TYPE_CLASS[c.type]}">${TYPE_LABEL[c.type] || c.type}</span></td>
        <td style="direction:ltr;text-align:right;">${esc(c.phone || '—')}</td>
        <td>
          <span class="case-count">${c.cases?.length || 0}</span>
        </td>
        <td><span class="badge ${STATUS_CLASS[c.status]}">${STATUS_LABEL[c.status] || c.status}</span></td>
        <td>${formatDate(c.lastContact)}</td>
        <td class="ta-right">
          <div class="row-actions">
            <button class="icon-btn view"   title="عرض التفاصيل" data-action="view">👁</button>
            <button class="icon-btn edit"   title="تعديل"         data-action="edit">✎</button>
            <button class="icon-btn danger" title="حذف"           data-action="delete">🗑</button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  /* ------------------------------------------
     تفويض النقر: عرض / تعديل / حذف
     ------------------------------------------ */
  body.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const id     = btn.closest('tr')?.dataset.id;
    if (!id) return;
    const action  = btn.dataset.action;
    const clients = loadClients();
    const client  = clients.find(c => c.id === id);
    if (!client) return;

    if (action === 'view') {
      openModal(client);
    } else if (action === 'edit') {
      const newStatus = prompt(
        'تحديث حالة العميل (نشط / مهم / متوقف):',
        STATUS_LABEL[client.status] || client.status
      );
      if (!newStatus) return;
      const map = { 'نشط': 'active', 'مهم': 'vip', 'متوقف': 'inactive', 'مهم ⭐': 'vip' };
      const next = map[newStatus.trim()];
      if (next) { client.status = next; saveClients(clients); render(); clientToast('تم تحديث بيانات العميل.'); }
    } else if (action === 'delete') {
      if (confirm(`هل تريد حذف العميل "${client.name}"؟ لا يمكن التراجع عن هذا الإجراء.`)) {
        saveClients(clients.filter(c => c.id !== id));
        render();
        clientToast('تم حذف العميل.');
      }
    }
  });

  // ربط الفلاتر والبحث
  searchInput?.addEventListener('input', render);
  filterType?.addEventListener('change', render);
  filterStatus?.addEventListener('change', render);

  render();
}

/* ============================================
   Modal: عرض تفاصيل العميل
   ============================================ */
function openModal(client) {
  const modal = document.getElementById('clientModal');
  if (!modal) return;

  // رأس الـ Modal
  document.getElementById('modalAvatar').textContent = initials(client.name);
  document.getElementById('modalName').textContent   = client.name;
  document.getElementById('modalType').textContent   = TYPE_LABEL[client.type]   || client.type;
  document.getElementById('modalType').className     = `badge ${TYPE_CLASS[client.type]}`;
  document.getElementById('modalStatus').textContent = STATUS_LABEL[client.status] || client.status;
  document.getElementById('modalStatus').className   = `badge ${STATUS_CLASS[client.status]}`;

  // البيانات الأساسية
  document.getElementById('infoName').textContent      = client.name     || '—';
  document.getElementById('infoNationalId').textContent= client.nationalId|| '—';
  document.getElementById('infoCompany').textContent   = client.company  || '—';
  document.getElementById('infoEmail').textContent     = client.email    || '—';
  document.getElementById('infoPhone').textContent     = client.phone    || '—';
  document.getElementById('infoAddress').textContent   = client.address  || '—';
  document.getElementById('infoAdded').textContent     = formatDate(client.addedDate);
  document.getElementById('infoLast').textContent      = formatDate(client.lastContact);

  // القضايا
  const casesList = document.getElementById('clientCasesList');
  if (client.cases?.length) {
    casesList.innerHTML = client.cases.map(c => `
      <div class="case-card">
        <div class="case-card-name">${esc(c.name)}</div>
        <div class="case-card-meta">
          <span class="muted">${esc(c.type)}</span>
          <span class="badge ${c.status.toLowerCase()}">${c.status === 'Active' ? 'نشطة' : c.status === 'Pending' ? 'معلّقة' : 'مغلقة'}</span>
          <span class="muted">${formatDate(c.date)}</span>
        </div>
      </div>
    `).join('');
  } else {
    casesList.innerHTML = '<div class="empty-inline muted">لا توجد قضايا مرتبطة بهذا العميل.</div>';
  }

  // التعاملات (Timeline)
  const timeline = document.getElementById('interactionTimeline');
  if (client.interactions?.length) {
    timeline.innerHTML = client.interactions.map(i => `
      <div class="timeline-item">
        <div class="timeline-dot">${INTERACTION_ICON[i.type] || '📌'}</div>
        <div class="timeline-content">
          <div class="timeline-label">${esc(i.label)}</div>
          <div class="timeline-detail muted">${esc(i.detail)}</div>
          <div class="timeline-date muted">${formatDate(i.date)} — ${esc(i.time)}</div>
        </div>
      </div>
    `).join('');
  } else {
    timeline.innerHTML = '<div class="empty-inline muted">لا يوجد سجل تعاملات.</div>';
  }

  // الملاحظات
  document.getElementById('notesContent').textContent = client.notes || 'لا توجد ملاحظات.';

  // إظهار الـ Modal مع التبويب الأول
  switchTab('info');
  modal.classList.remove('hidden');
  document.body.classList.add('modal-open');
}

function closeModal() {
  const modal = document.getElementById('clientModal');
  modal?.classList.add('hidden');
  document.body.classList.remove('modal-open');
}

/* التبويبات */
function switchTab(tabId) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById(`tab-${tabId}`)?.classList.remove('hidden');
  document.querySelector(`.tab-btn[data-tab="${tabId}"]`)?.classList.add('active');
}

function initModal() {
  const modal = document.getElementById('clientModal');
  if (!modal) return;

  // إغلاق بالزر
  document.getElementById('closeModal')?.addEventListener('click', closeModal);

  // إغلاق بالنقر خارج الـ Modal
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // إغلاق بـ Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // التبويبات
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.tab));
  });
}

/* ============================================
   صفحة إضافة عميل — initAddClientPage
   ============================================ */
function initAddClientPage() {
  const form = document.getElementById('clientForm');
  if (!form) return;

  function setError(name, msg) {
    const field = form.querySelector(`[name="${name}"]`)?.closest('.field');
    const err   = form.querySelector(`[data-error-for="${name}"]`);
    if (field) field.classList.toggle('invalid', !!msg);
    if (err)   err.textContent = msg || '';
  }

  form.addEventListener('input', (e) => {
    if (e.target.name) setError(e.target.name, '');
  });

//   form.addEventListener('submit', (e) => {
//     e.preventDefault();

//     const data = {
//       id:           cryptoId(),
//       name:         form.clientName.value.trim(),
//       type:         form.clientType.value,
//       phone:        form.phone.value.trim(),
//       email:        form.email.value.trim(),
//       nationalId:   form.nationalId.value.trim(),
//       company:      form.company.value.trim(),
//       address:      form.address.value.trim(),
//       status:       form.clientStatus.value,
//       lastContact:  form.lastContact.value || new Date().toISOString().slice(0,10),
//       addedDate:    new Date().toISOString().slice(0,10),
//       notes:        form.notes.value.trim(),
//       cases:        [],
//       interactions: []
//     };

//     // تحقق من الحقول المطلوبة
//     let ok = true;
//     ['clientName','clientType','phone','clientStatus'].forEach(n => {
//       if (!data[n === 'clientName' ? 'name' : n === 'clientType' ? 'type' : n === 'clientStatus' ? 'status' : n]) {
//         setError(n, 'هذا الحقل مطلوب.');
//         ok = false;
//       }
//     });
//     if (!ok) { clientToast('يرجى تعبئة الحقول المطلوبة.'); return; }

//     const clients = loadClients();
//     clients.unshift(data);
//     saveClients(clients);
//     clientToast('تم حفظ العميل بنجاح.');
//     setTimeout(() => { window.location.href = '/clients.html'; }, 700);
//   });
// }

/* ============================================
   التشغيل
   ============================================ */
document.addEventListener('DOMContentLoaded', () => {
  initClientsPage();
  initModal();
  initAddClientPage();
});
