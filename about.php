<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$pageTitle = 'عن المعهد — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <div class="hero" style="padding:60px 30px">
        <h1>عن معهد NOVA التدريبي</h1>
        <p>منصة تعليمية عربية حديثة، نقدم دورات تدريبية بمعايير عالمية</p>
    </div>

    <div class="grid grid-3" style="margin-bottom:40px">
        <div class="card">
            <div style="font-size:3rem;margin-bottom:14px">🎯</div>
            <h3 style="font-weight:900">رسالتنا</h3>
            <p style="color:var(--text-2);margin-top:10px">تمكين الشباب العربي من مهارات القرن الحادي والعشرين بأفضل جودة وأقل تكلفة.</p>
        </div>
        <div class="card">
            <div style="font-size:3rem;margin-bottom:14px">👁️</div>
            <h3 style="font-weight:900">رؤيتنا</h3>
            <p style="color:var(--text-2);margin-top:10px">أن نكون المعهد التدريبي الأول في المنطقة من حيث جودة المحتوى ورضا المتدربين.</p>
        </div>
        <div class="card">
            <div style="font-size:3rem;margin-bottom:14px">💎</div>
            <h3 style="font-weight:900">قيمنا</h3>
            <p style="color:var(--text-2);margin-top:10px">الجودة، الشفافية، الابتكار، والتركيز الدائم على المتدرب أولاً.</p>
        </div>
    </div>

    <h2 style="font-size:1.8rem;font-weight:900;text-align:center;margin-bottom:26px">لماذا تختارنا؟</h2>
    <div class="grid grid-2" style="margin-bottom:40px">
        <div class="card">
            <h3 style="font-weight:800">✅ مدربون خبراء</h3>
            <p style="color:var(--text-2);margin-top:8px">جميع مدربينا لديهم خبرة عملية لا تقل عن 5 سنوات في مجالاتهم.</p>
        </div>
        <div class="card">
            <h3 style="font-weight:800">✅ شهادات معتمدة</h3>
            <p style="color:var(--text-2);margin-top:8px">شهادات معترف بها تساعدك في سوق العمل.</p>
        </div>
        <div class="card">
            <h3 style="font-weight:800">✅ محتوى محدث</h3>
            <p style="color:var(--text-2);margin-top:8px">نحدّث محتوى الدورات بشكل دوري لمواكبة التطورات.</p>
        </div>
        <div class="card">
            <h3 style="font-weight:800">✅ دعم مستمر</h3>
            <p style="color:var(--text-2);margin-top:8px">فريق دعم متاح للإجابة على استفساراتك في أي وقت.</p>
        </div>
    </div>

    <h2 style="font-size:1.8rem;font-weight:900;text-align:center;margin-bottom:26px">فريق المدربين</h2>
    <div class="grid grid-3">
        <div class="card" style="text-align:center">
            <div style="width:100px;height:100px;border-radius:50%;background:var(--grad);display:grid;place-items:center;font-size:2.5rem;font-weight:900;color:#fff;margin:0 auto 16px">أ</div>
            <h3 style="font-weight:900">م. أحمد عبد الباري</h3>
            <p style="color:var(--primary);font-weight:700;font-size:.88rem">تطوير الويب</p>
        </div>
        <div class="card" style="text-align:center">
            <div style="width:100px;height:100px;border-radius:50%;background:var(--grad);display:grid;place-items:center;font-size:2.5rem;font-weight:900;color:#fff;margin:0 auto 16px">س</div>
            <h3 style="font-weight:900">د. سارة المحمدي</h3>
            <p style="color:var(--primary);font-weight:700;font-size:.88rem">تصميم UX/UI</p>
        </div>
        <div class="card" style="text-align:center">
            <div style="width:100px;height:100px;border-radius:50%;background:var(--grad);display:grid;place-items:center;font-size:2.5rem;font-weight:900;color:#fff;margin:0 auto 16px">م</div>
            <h3 style="font-weight:900">أ. محمد الشامي</h3>
            <p style="color:var(--primary);font-weight:700;font-size:.88rem">إدارة الأعمال</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>