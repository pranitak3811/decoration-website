<?php
// ============================================================
// SAVE THIS FILE AS: track.php (NOT track.html)
// LOCATION: C:\xampp\htdocs\decoration-website\track.php
// ============================================================

// Handle AJAX calls from JavaScript
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');

    $conn = new mysqli('localhost', 'root', '', 'decoration_db');

    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'DB Error: ' . $conn->connect_error]));
    }

    $action = $_GET['action'] ?? '';

    if ($action === 'by_id') {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { die(json_encode(['success' => false, 'message' => 'Please enter a valid Booking ID.'])); }
        $r = $conn->query("SELECT * FROM bookings WHERE id=$id LIMIT 1");
        if ($r->num_rows === 0) { die(json_encode(['success' => false, 'message' => 'No booking found with ID #'.$id.'. Please check and try again.'])); }
        die(json_encode(['success' => true, 'booking' => $r->fetch_assoc()]));

    } elseif ($action === 'by_email') {
        $email = $conn->real_escape_string(trim($_GET['email'] ?? ''));
        if (!$email) { die(json_encode(['success' => false, 'message' => 'Please enter your email address.'])); }
        $r = $conn->query("SELECT * FROM bookings WHERE email='$email' ORDER BY id DESC LIMIT 1");
        if ($r->num_rows === 0) { die(json_encode(['success' => false, 'message' => 'No booking found with this email address.'])); }
        die(json_encode(['success' => true, 'booking' => $r->fetch_assoc()]));
    }

    die(json_encode(['success' => false, 'message' => 'Invalid action.']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Booking — Aura Decor</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:#0f0e0c;color:#fff;min-height:100vh;}

/* NAV */
.nav{display:flex;align-items:center;justify-content:space-between;padding:20px 48px;border-bottom:1px solid rgba(201,168,76,0.15);background:rgba(15,14,12,0.97);position:sticky;top:0;z-index:100;backdrop-filter:blur(12px);}
.nav-brand{font-family:'Playfair Display',serif;font-size:22px;color:#C9A84C;text-decoration:none;letter-spacing:1px;}
.nav-back{font-size:13px;color:rgba(255,255,255,0.5);text-decoration:none;border:1px solid rgba(255,255,255,0.15);padding:8px 20px;border-radius:40px;transition:all 0.2s;}
.nav-back:hover{border-color:#C9A84C;color:#C9A84C;}

/* ── SEARCH PAGE ── */
#pageSearch{min-height:calc(100vh - 66px);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 24px;background:radial-gradient(ellipse at 50% 0%, rgba(201,168,76,0.1) 0%, transparent 65%);}
.s-tag{font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:#C9A84C;margin-bottom:14px;}
.s-title{font-family:'Playfair Display',serif;font-size:clamp(38px,6vw,68px);font-weight:400;text-align:center;line-height:1.1;margin-bottom:14px;}
.s-title em{color:#C9A84C;font-style:italic;}
.s-sub{color:rgba(255,255,255,0.4);font-size:15px;text-align:center;margin-bottom:48px;max-width:400px;line-height:1.6;}
.s-box{width:100%;max-width:500px;background:rgba(255,255,255,0.03);border:1px solid rgba(201,168,76,0.2);border-radius:20px;padding:36px;}
.s-tabs{display:flex;background:rgba(255,255,255,0.05);border-radius:10px;overflow:hidden;margin-bottom:28px;}
.s-tab{flex:1;padding:11px;text-align:center;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;cursor:pointer;border:none;background:transparent;color:rgba(255,255,255,0.4);transition:all 0.2s;}
.s-tab.on{background:#C9A84C;color:#0f0e0c;font-weight:700;border-radius:8px;}
.s-lbl{display:block;font-size:11px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:10px;}
.s-inp{width:100%;padding:15px 18px;background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.1);border-radius:12px;color:#fff;font-family:'DM Sans',sans-serif;font-size:16px;outline:none;transition:all 0.25s;margin-bottom:8px;}
.s-inp::placeholder{color:rgba(255,255,255,0.18);}
.s-inp:focus{border-color:#C9A84C;background:rgba(201,168,76,0.05);box-shadow:0 0 0 4px rgba(201,168,76,0.1);}
.s-inp::-webkit-outer-spin-button,.s-inp::-webkit-inner-spin-button{-webkit-appearance:none;}
.s-hint{font-size:11px;color:rgba(255,255,255,0.2);margin-bottom:22px;line-height:1.5;}
.s-btn{width:100%;padding:15px;background:#C9A84C;border:none;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;color:#0f0e0c;cursor:pointer;transition:all 0.25s;display:flex;align-items:center;justify-content:center;gap:8px;}
.s-btn:hover{background:#E8D08A;transform:translateY(-2px);box-shadow:0 8px 28px rgba(201,168,76,0.3);}
.s-btn:disabled{opacity:0.5;cursor:not-allowed;transform:none;}
.s-err{margin-top:14px;padding:12px 16px;border-radius:10px;background:rgba(220,38,38,0.12);border:1px solid rgba(220,38,38,0.25);color:#FCA5A5;font-size:14px;display:none;text-align:center;line-height:1.5;}

/* ── RESULT PAGE ── */
#pageResult{display:none;padding:40px 24px 60px;}
.r-wrap{max-width:840px;margin:0 auto;}
.r-back{display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.4);font-size:13px;cursor:pointer;margin-bottom:32px;background:none;border:none;font-family:'DM Sans',sans-serif;transition:color 0.2s;padding:0;}
.r-back:hover{color:#C9A84C;}

/* BANNER */
.r-banner{border-radius:18px;padding:32px 36px;margin-bottom:20px;position:relative;overflow:hidden;}
.r-banner.pending  {background:linear-gradient(135deg,#1c1600,#2e2300);border:1px solid rgba(217,119,6,0.35);}
.r-banner.confirmed{background:linear-gradient(135deg,#001c0e,#002f1a);border:1px solid rgba(5,150,105,0.35);}
.r-banner.completed{background:linear-gradient(135deg,#000a1c,#001540);border:1px solid rgba(37,99,235,0.35);}
.r-banner.cancelled{background:linear-gradient(135deg,#1c0000,#2f0000);border:1px solid rgba(220,38,38,0.35);}
.r-banner::after{content:attr(data-icon);position:absolute;right:28px;top:50%;transform:translateY(-50%);font-size:90px;opacity:0.06;}
.r-pill{display:inline-flex;align-items:center;gap:7px;padding:5px 16px;border-radius:40px;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:16px;}
.pending   .r-pill{background:rgba(217,119,6,0.18); color:#FCD34D;}
.confirmed .r-pill{background:rgba(5,150,105,0.18); color:#6EE7B7;}
.completed .r-pill{background:rgba(37,99,235,0.18); color:#93C5FD;}
.cancelled .r-pill{background:rgba(220,38,38,0.18); color:#FCA5A5;}
.r-dot{width:7px;height:7px;border-radius:50%;display:inline-block;}
.pending   .r-dot{background:#FCD34D;animation:blink 1.4s infinite;}
.confirmed .r-dot{background:#6EE7B7;animation:blink 1.4s infinite;}
.completed .r-dot{background:#93C5FD;}
.cancelled .r-dot{background:#FCA5A5;}
@keyframes blink{0%,100%{opacity:1}50%{opacity:0.25}}
.r-banner h2{font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,40px);font-weight:700;color:#fff;margin-bottom:8px;line-height:1.15;}
.r-banner .r-meta{font-size:13px;color:rgba(255,255,255,0.35);}
.r-banner .r-meta strong{color:#C9A84C;}

/* CARDS */
.r-card{background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);border-radius:18px;padding:28px 32px;margin-bottom:20px;}
.r-card-title{font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:28px;}

/* PROGRESS */
.prog{display:flex;align-items:flex-start;position:relative;}
.prog::before{content:'';position:absolute;top:21px;left:21px;right:21px;height:3px;background:rgba(255,255,255,0.07);border-radius:2px;z-index:0;}
.prog-bar{position:absolute;top:21px;left:21px;height:3px;background:linear-gradient(90deg,#C9A84C,#E8D08A);border-radius:2px;z-index:1;transition:width 1s ease;}
.p-step{flex:1;display:flex;flex-direction:column;align-items:center;gap:10px;position:relative;z-index:2;}
.p-circle{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:17px;border:2px solid rgba(255,255,255,0.1);background:#0f0e0c;transition:all 0.4s;}
.p-step.done   .p-circle{background:#C9A84C;border-color:#C9A84C;filter:drop-shadow(0 0 8px rgba(201,168,76,0.45));}
.p-step.active .p-circle{border-color:#C9A84C;box-shadow:0 0 0 5px rgba(201,168,76,0.12);background:rgba(201,168,76,0.08);}
.p-step.idle   .p-circle{opacity:0.35;}
.p-label{font-size:10px;font-weight:600;text-align:center;color:rgba(255,255,255,0.3);letter-spacing:0.2px;}
.p-step.done .p-label,.p-step.active .p-label{color:rgba(255,255,255,0.75);}

/* DETAILS GRID */
.d-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px 20px;}
.d-key{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.28);margin-bottom:5px;}
.d-val{font-size:15px;font-weight:500;color:#fff;line-height:1.3;}
.d-val.highlight{color:#C9A84C;font-size:22px;font-weight:700;}
.d-full{grid-column:1/-1;}

/* TIMELINE */
.tl{display:flex;flex-direction:column;}
.tl-item{display:flex;gap:16px;position:relative;}
.tl-item:not(:last-child)::after{content:'';position:absolute;left:16px;top:36px;width:2px;height:calc(100% - 8px);background:rgba(255,255,255,0.07);}
.tl-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;margin-top:2px;}
.tl-icon.done  {background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.4);}
.tl-icon.active{background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.5);}
.tl-icon.idle  {background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);opacity:0.45;}
.tl-body{padding-bottom:24px;}
.tl-title{font-size:14px;font-weight:600;margin-bottom:3px;}
.tl-item.done   .tl-title{color:#C9A84C;}
.tl-item.active .tl-title{color:#FCD34D;}
.tl-item.idle   .tl-title{color:rgba(255,255,255,0.5);}
.tl-desc{font-size:13px;color:rgba(255,255,255,0.38);line-height:1.6;}

/* CONTACT */
.r-contact{background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.15);border-radius:18px;padding:24px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;}
.r-contact p{font-size:14px;color:rgba(255,255,255,0.65);}
.r-contact p strong{color:#fff;display:block;font-size:15px;margin-bottom:2px;}
.c-btns{display:flex;gap:10px;flex-wrap:wrap;}
.c-btn{padding:10px 20px;border-radius:40px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px;}
.c-btn-gold{background:#C9A84C;color:#0f0e0c;border:none;}
.c-btn-gold:hover{background:#E8D08A;}
.c-btn-out{background:transparent;color:rgba(255,255,255,0.55);border:1px solid rgba(255,255,255,0.15);}
.c-btn-out:hover{border-color:#C9A84C;color:#C9A84C;}
.r-again{text-align:center;padding:4px 0 20px;}
.r-again button{background:none;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.4);padding:10px 28px;border-radius:40px;font-family:'DM Sans',sans-serif;font-size:14px;cursor:pointer;transition:all 0.2s;}
.r-again button:hover{border-color:#C9A84C;color:#C9A84C;}

/* LOADER */
#loader{display:none;position:fixed;inset:0;background:rgba(10,9,8,0.88);z-index:999;align-items:center;justify-content:center;flex-direction:column;gap:18px;}
#loader.on{display:flex;}
.spin{width:46px;height:46px;border:3px solid rgba(201,168,76,0.15);border-top-color:#C9A84C;border-radius:50%;animation:spin 0.8s linear infinite;}
@keyframes spin{to{transform:rotate(360deg)}}
#loader p{color:rgba(255,255,255,0.4);font-size:13px;letter-spacing:1px;}

@media(max-width:640px){
  .nav{padding:16px 20px;}
  .d-grid{grid-template-columns:1fr 1fr;}
  .r-card,.r-banner,.r-contact{padding:22px 18px;}
  .r-contact{flex-direction:column;}
  .p-label{font-size:8px;}
  .p-circle{width:36px;height:36px;font-size:14px;}
  .prog::before{top:17px;}.prog-bar{top:17px;}
}
</style>
</head>
<body>

<!-- LOADER -->
<div id="loader"><div class="spin"></div><p>Finding your booking...</p></div>

<!-- NAV -->
<div class="nav">
  <a href="index.html" class="nav-brand">✦ Aura Décor</a>
  <a href="index.html" class="nav-back">← Back to Website</a>
</div>

<!-- ══════════ SEARCH PAGE ══════════ -->
<div id="pageSearch">
  <p class="s-tag">✦ Customer Portal</p>
  <h1 class="s-title">Track Your<br><em>Booking Status</em></h1>
  <p class="s-sub">Enter your Booking ID or email to see the live status of your event decoration.</p>

  <div class="s-box">
    <div class="s-tabs">
      <button class="s-tab on" id="tabId" onclick="switchTab('id')">🔖 &nbsp;Booking ID</button>
      <button class="s-tab"    id="tabEmail" onclick="switchTab('email')">✉️ &nbsp;Email Address</button>
    </div>

    <div id="paneId">
      <label class="s-lbl">Enter Your Booking ID</label>
      <input class="s-inp" type="number" id="inpId" placeholder="e.g.  1" min="1" onkeydown="if(event.key==='Enter')doSearch()">
      <p class="s-hint">Your Booking ID was shown after you submitted the booking form — for example: Booking #1</p>
    </div>

    <div id="paneEmail" style="display:none">
      <label class="s-lbl">Enter Your Email Address</label>
      <input class="s-inp" type="email" id="inpEmail" placeholder="e.g.  yourname@gmail.com" onkeydown="if(event.key==='Enter')doSearch()">
      <p class="s-hint">We will find your most recent booking linked to this email address</p>
    </div>

    <button class="s-btn" id="trackBtn" onclick="doSearch()">
      <span id="btnTxt">Track My Booking</span>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </button>

    <div class="s-err" id="errBox"></div>
  </div>
</div>

<!-- ══════════ RESULT PAGE ══════════ -->
<div id="pageResult">
  <div class="r-wrap">

    <button class="r-back" onclick="goBack()">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Search Again
    </button>

    <!-- BANNER -->
    <div class="r-banner" id="rBanner">
      <div class="r-pill" id="rPill"><div class="r-dot"></div><span id="rPillTxt"></span></div>
      <h2 id="rTitle"></h2>
      <p class="r-meta">Booking <strong id="rRef"></strong> &nbsp;·&nbsp; Submitted on <span id="rDate"></span></p>
    </div>

    <!-- PROGRESS -->
    <div class="r-card">
      <div class="r-card-title">Booking Progress</div>
      <div class="prog">
        <div class="prog-bar" id="progBar" style="width:0%"></div>
        <div class="p-step" id="ps1"><div class="p-circle">📝</div><div class="p-label">Received</div></div>
        <div class="p-step" id="ps2"><div class="p-circle">☎️</div><div class="p-label">Confirmed</div></div>
        <div class="p-step" id="ps3"><div class="p-circle">🎨</div><div class="p-label">Preparing</div></div>
        <div class="p-step" id="ps4"><div class="p-circle">🎉</div><div class="p-label">Completed</div></div>
      </div>
    </div>

    <!-- DETAILS -->
    <div class="r-card">
      <div class="r-card-title">Your Event Details</div>
      <div class="d-grid">
        <div><div class="d-key">Booking ID</div><div class="d-val highlight" id="dId"></div></div>
        <div><div class="d-key">Client Name</div><div class="d-val" id="dName"></div></div>
        <div><div class="d-key">Phone Number</div><div class="d-val" id="dPhone"></div></div>
        <div><div class="d-key">Event Type</div><div class="d-val" id="dEvent"></div></div>
        <div><div class="d-key">Event Date</div><div class="d-val" id="dDate"></div></div>
        <div><div class="d-key">No. of Guests</div><div class="d-val" id="dGuests"></div></div>
        <div><div class="d-key">Venue / Location</div><div class="d-val" id="dVenue"></div></div>
        <div><div class="d-key">Budget Range</div><div class="d-val" id="dBudget"></div></div>
        <div><div class="d-key">Current Status</div><div class="d-val" id="dStatus"></div></div>
        <div class="d-full"><div class="d-key">Special Requests</div><div class="d-val" id="dSpecial"></div></div>
      </div>
    </div>

    <!-- TIMELINE -->
    <div class="r-card">
      <div class="r-card-title">What Happens Next</div>
      <div class="tl" id="tlBox"></div>
    </div>

    <!-- CONTACT -->
    <div class="r-contact">
      <p><strong>Need Help?</strong>Our team is always ready to assist you.</p>
      <div class="c-btns">
        <a href="tel:+919876543210" class="c-btn c-btn-gold">📞 Call Us</a>
        <a href="https://wa.me/919876543210" class="c-btn c-btn-out" target="_blank">💬 WhatsApp</a>
        <a href="mailto:hello@auradecor.in" class="c-btn c-btn-out">✉️ Email</a>
      </div>
    </div>

    <div class="r-again"><button onclick="goBack()">Track Another Booking</button></div>
  </div>
</div>

<script>
var activeTab = 'id';

function switchTab(t) {
  activeTab = t;
  document.getElementById('paneId').style.display    = (t === 'id') ? 'block' : 'none';
  document.getElementById('paneEmail').style.display = (t === 'email') ? 'block' : 'none';
  document.getElementById('tabId').className    = 's-tab' + (t === 'id' ? ' on' : '');
  document.getElementById('tabEmail').className = 's-tab' + (t === 'email' ? ' on' : '');
  document.getElementById('errBox').style.display = 'none';
}

function showErr(msg) {
  var e = document.getElementById('errBox');
  e.style.display = 'block';
  e.innerHTML = '⚠️ &nbsp;' + msg;
}

async function doSearch() {
  var val = (activeTab === 'id')
    ? document.getElementById('inpId').value.trim()
    : document.getElementById('inpEmail').value.trim();

  if (!val) {
    showErr(activeTab === 'id' ? 'Please enter your Booking ID.' : 'Please enter your email address.');
    return;
  }

  document.getElementById('errBox').style.display = 'none';
  document.getElementById('loader').className = 'on';
  document.getElementById('trackBtn').disabled = true;
  document.getElementById('btnTxt').textContent = 'Searching...';

  try {
    var url = (activeTab === 'id')
      ? 'track.php?ajax=1&action=by_id&id=' + encodeURIComponent(val)
      : 'track.php?ajax=1&action=by_email&email=' + encodeURIComponent(val);

    var res  = await fetch(url);
    var text = await res.text();
    var data;

    try {
      data = JSON.parse(text);
    } catch(e) {
      throw new Error('Server error: ' + text.substring(0, 300));
    }

    if (!data.success) {
      showErr(data.message);
    } else {
      showResult(data.booking);
    }

  } catch(e) {
    showErr(e.message);
  }

  document.getElementById('loader').className = '';
  document.getElementById('trackBtn').disabled = false;
  document.getElementById('btnTxt').textContent = 'Track My Booking';
}

function showResult(b) {
  var s = b.status;

  // Banner
  var banner = document.getElementById('rBanner');
  banner.className = 'r-banner ' + s;
  var icons = {pending:'⏳', confirmed:'✅', completed:'🎊', cancelled:'❌'};
  banner.setAttribute('data-icon', icons[s] || '📋');

  var pillLabels = {pending:'⏳ Pending Review', confirmed:'✅ Confirmed', completed:'🎉 Completed', cancelled:'❌ Cancelled'};
  document.getElementById('rPillTxt').textContent = pillLabels[s] || s;
  document.getElementById('rPill').className = 'r-pill ' + s;

  var bannerTitles = {
    pending:   b.client_name + ', your booking is under review!',
    confirmed: b.client_name + ', your booking is confirmed! 🎉',
    completed: 'Event completed! Thank you, ' + b.client_name + '!',
    cancelled: 'Booking cancelled — ' + b.client_name
  };
  document.getElementById('rTitle').textContent = bannerTitles[s] || b.client_name + "'s Booking";
  document.getElementById('rRef').textContent   = '#' + b.id;
  document.getElementById('rDate').textContent  = fmtDate(b.created_at);

  // Details
  document.getElementById('dId').textContent      = '#' + b.id;
  document.getElementById('dName').textContent    = b.client_name;
  document.getElementById('dPhone').textContent   = b.phone;
  document.getElementById('dEvent').textContent   = b.event_type;
  document.getElementById('dDate').textContent    = fmtDate(b.event_date);
  document.getElementById('dGuests').textContent  = b.guest_count + ' Guests';
  document.getElementById('dVenue').textContent   = b.venue;
  document.getElementById('dBudget').textContent  = b.budget || 'Not specified';
  document.getElementById('dStatus').textContent  = pillLabels[s] || s;
  document.getElementById('dSpecial').textContent = b.special_requests || 'No special requests';

  // Progress bar
  var barW = {pending:'8%', confirmed:'42%', completed:'97%', cancelled:'8%'};
  document.getElementById('progBar').style.width = barW[s] || '8%';

  var doneCount = {pending:1, confirmed:2, completed:4, cancelled:1};
  var done = doneCount[s] || 1;
  ['ps1','ps2','ps3','ps4'].forEach(function(id, i) {
    var el = document.getElementById(id);
    el.className = 'p-step';
    if (i + 1 < done)                           el.classList.add('done');
    else if (i + 1 === done && s !== 'cancelled') el.classList.add('active');
    else                                         el.classList.add('idle');
  });

  // Timeline
  var ev = fmtDate(b.event_date);
  var timelines = {
    pending: [
      {c:'done',  i:'📝', t:'Booking Received',            d:'Your booking request was received successfully.'},
      {c:'active',i:'📞', t:'Team Will Call You (24 hrs)',  d:'Our team will review your booking and call you to confirm all details and discuss your decoration theme.'},
      {c:'idle',  i:'📦', t:'Decoration Planning Begins',   d:'After confirmation, we start sourcing materials and planning your unique decoration setup.'},
      {c:'idle',  i:'🚚', t:'Setup on Event Day — ' + ev,   d:'Our team will arrive early at your venue and set up everything beautifully before your guests arrive.'},
      {c:'idle',  i:'🎉', t:'Event Successfully Completed', d:'Sit back, relax, and enjoy your perfectly decorated event!'}
    ],
    confirmed: [
      {c:'done',  i:'📝', t:'Booking Received',           d:'Your booking was received successfully.'},
      {c:'done',  i:'✅', t:'Booking Confirmed',           d:'Our team has confirmed your booking. We will be there on ' + ev + '.'},
      {c:'active',i:'📦', t:'Preparation in Progress',    d:'We are currently sourcing flowers, lights, and all materials needed for your decoration.'},
      {c:'idle',  i:'🚚', t:'Setup on Event Day — ' + ev, d:'Our team will arrive early to set up the complete decoration at your venue.'},
      {c:'idle',  i:'🎉', t:'Enjoy Your Event',           d:'Everything will be picture-perfect when you and your guests arrive!'}
    ],
    completed: [
      {c:'done',i:'📝', t:'Booking Received',           d:'Booking was received.'},
      {c:'done',i:'✅', t:'Booking Confirmed',           d:'Booking was confirmed by our team.'},
      {c:'done',i:'📦', t:'Preparation Completed',      d:'All materials sourced and decoration fully planned.'},
      {c:'done',i:'🚚', t:'Setup Completed on ' + ev,   d:'Our team arrived and set up a stunning decoration at your venue.'},
      {c:'done',i:'🎉', t:'Event Successfully Completed',d:'Thank you for choosing Aura Décor! We hope your event was absolutely magical.'}
    ],
    cancelled: [
      {c:'done',  i:'📝', t:'Booking Received',  d:'Booking request was received.'},
      {c:'active',i:'❌', t:'Booking Cancelled', d:'This booking was cancelled. Please contact us if you would like to rebook.'}
    ]
  };

  var rows = timelines[s] || timelines.pending;
  document.getElementById('tlBox').innerHTML = rows.map(function(r) {
    return '<div class="tl-item ' + r.c + '">' +
      '<div class="tl-icon ' + r.c + '">' + r.i + '</div>' +
      '<div class="tl-body">' +
        '<div class="tl-title">' + r.t + '</div>' +
        '<div class="tl-desc">'  + r.d + '</div>' +
      '</div></div>';
  }).join('');

  // Switch pages
  document.getElementById('pageSearch').style.display = 'none';
  document.getElementById('pageResult').style.display = 'block';
  window.scrollTo({top: 0, behavior: 'smooth'});
}

function fmtDate(ds) {
  if (!ds) return '—';
  var d = new Date(ds);
  if (isNaN(d.getTime())) return ds;
  return d.toLocaleDateString('en-IN', {day: 'numeric', month: 'long', year: 'numeric'});
}

function goBack() {
  document.getElementById('pageResult').style.display = 'none';
  document.getElementById('pageSearch').style.display = 'flex';
  document.getElementById('inpId').value = '';
  document.getElementById('inpEmail').value = '';
  document.getElementById('errBox').style.display = 'none';
  window.scrollTo({top: 0, behavior: 'smooth'});
}
</script>
</body>
</html>
