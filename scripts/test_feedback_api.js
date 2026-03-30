/**
 * ============================================================
 *  Contact Support & Feedback API — Integration Test Script
 * ============================================================
 *
 *  Endpoints tested (14 tests):
 *    1. POST   /api/v1/feedback             (public / no auth)
 *    2. POST   /api/v1/feedback             (authenticated patient)
 *    3. POST   /api/v1/feedback             (validation — missing fields)
 *    4. GET    /api/v1/feedback/my           (user ticket history)
 *    5. POST   /api/v1/feedback/rate         (app rating)
 *    6. POST   /api/v1/feedback/rate         (validation — missing rating)
 *    7. GET    /api/v1/feedback/latest-rating
 *    8. GET    /api/v1/feedback/my           (unauthenticated → 401)
 *    9. GET    /api/v1/feedback              (super_admin — all tickets)
 *   10. GET    /api/v1/feedback?category=bug (super_admin — filter)
 *   11. PUT    /api/v1/feedback/:id          (super_admin — update status)
 *   12. PUT    /api/v1/feedback/:id          (super_admin — resolve)
 *   13. PUT    /api/v1/feedback/:invalid     (super_admin — 404)
 *   14. GET    /api/v1/feedback              (patient → 403)
 *
 *  Usage:  node scripts/test_feedback_api.js
 */

const axios = require('axios');
require('dotenv').config();

const API_KEY = process.env.API_KEY || 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';
const BASE_URL = 'http://localhost:5000/api/v1';

// ── Helpers ────────────────────────────────────────────────
const hdr = (token) => ({
  'x-api-key': API_KEY,
  ...(token ? { Authorization: `Bearer ${token}` } : {}),
  'Content-Type': 'application/json',
});

let passed = 0;
let failed = 0;

function logResult(label, success, detail) {
  if (success) {
    passed++;
    console.log(`  ✅  ${label}`);
  } else {
    failed++;
    console.log(`  ❌  ${label}`);
  }
  if (detail) console.log(`      ↳ ${typeof detail === 'string' ? detail : JSON.stringify(detail)}`);
}

function printSummary() {
  console.log('\n' + '═'.repeat(60));
  if (failed === 0) {
    console.log(`  🎉 ALL PASSED:  ${passed}/${passed + failed} tests`);
  } else {
    console.log(`  Results:  ${passed} passed  ·  ${failed} failed  ·  ${passed + failed} total`);
  }
  console.log('═'.repeat(60) + '\n');
  process.exit(failed > 0 ? 1 : 0);
}

// ── Login / Register helpers ───────────────────────────────
async function loginAsPatient() {
  const res = await axios.post(`${BASE_URL}/auth/login`, {
    username: 'karthik',
    password: 'Karthik@123',
    role: 'patient'
  }, { headers: hdr() });
  return res.data.data.token;
}

async function getOrCreateSuperAdmin() {
  try {
    const res = await axios.post(`${BASE_URL}/auth/login`, {
      username: 'superadmin',
      password: 'Test12345!',
      role: 'super_admin'
    }, { headers: hdr() });
    return res.data.data.token;
  } catch (e) {
    console.log('      ↳ super_admin not found, registering ...');
    const res = await axios.post(`${BASE_URL}/auth/register`, {
      firstName: 'Super', lastName: 'Admin', username: 'superadmin',
      email: 'superadmin_test@example.com', password: 'Test12345!',
      phone: '9840056700', role: 'super_admin', gender: 'male',
      dateOfBirth: '1960-04-02'
    }, { headers: hdr() });
    return res.data.data.token;
  }
}

// ── Tests ──────────────────────────────────────────────────
async function runTests() {
  console.log('\n' + '═'.repeat(60));
  console.log('  🧪 Contact Support & Feedback API — Test Suite');
  console.log('  📡 Target: ' + BASE_URL + '/feedback');
  console.log('═'.repeat(60));

  let patientToken, adminToken;
  let createdFeedbackId;

  // ─── 0. Authenticate ──────────────────────────────────
  console.log('\n🔑  Step 0 · Authenticating ...');
  try {
    patientToken = await loginAsPatient();
    logResult('Login as patient (karthik)', true);
  } catch (err) {
    logResult('Login as patient (karthik)', false,
      err.response ? err.response.data.message : err.message);
    console.log('\n⚠️  Cannot proceed without a patient token. Aborting.');
    return printSummary();
  }

  try {
    adminToken = await getOrCreateSuperAdmin();
    logResult('Login / register super_admin', true);
  } catch (err) {
    logResult('Login / register super_admin', false,
      err.response ? err.response.data.message : err.message);
    console.log('\n⚠️  Cannot proceed without a super_admin token. Aborting.');
    return printSummary();
  }

  // ─── 1. Submit feedback — public (no token) ───────────
  console.log('\n📩  Test 1 · Submit Feedback (Public / No Auth)');
  try {
    const res = await axios.post(`${BASE_URL}/feedback`, {
      subject: 'Public Bug Report',
      message: 'The login page freezes on slow networks.',
      category: 'bug'
    }, { headers: hdr() });

    const d = res.data.data;
    const ok = res.status === 201 && d && d.category === 'bug' && d.status === 'open' && d.userId === null;
    logResult('POST /feedback (public)', ok,
      ok ? `ID: ${d._id} | status: ${d.status} | userId: null ✓` : res.data);
  } catch (err) {
    logResult('POST /feedback (public)', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 2. Submit feedback — authenticated ───────────────
  console.log('\n📩  Test 2 · Submit Feedback (Authenticated Patient)');
  try {
    const res = await axios.post(`${BASE_URL}/feedback`, {
      subject: 'Feature Request: Dark Mode',
      message: 'Please add a dark mode option for night-time use.',
      category: 'feature_request'
    }, { headers: hdr(patientToken) });

    const d = res.data.data;
    const ok = res.status === 201 && d && d.userId && d.category === 'feature_request';
    createdFeedbackId = d._id;
    logResult('POST /feedback (authenticated)', ok,
      ok ? `ID: ${d._id} | userId: ${d.userId} ✓` : res.data);
  } catch (err) {
    logResult('POST /feedback (authenticated)', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 3. Validation — missing fields ─────────────────
  console.log('\n🚫  Test 3 · Validation — Missing Subject & Message');
  try {
    await axios.post(`${BASE_URL}/feedback`, {
      category: 'support'
    }, { headers: hdr(patientToken) });

    logResult('POST /feedback (missing fields) should fail', false, 'Expected 400');
  } catch (err) {
    const ok = err.response && err.response.status === 400;
    logResult('POST /feedback (missing fields) → 400', ok,
      err.response ? err.response.data.message : err.message);
  }

  // ─── 4. Get my feedback ──────────────────────────────
  console.log('\n📋  Test 4 · Get My Feedback History');
  try {
    const res = await axios.get(`${BASE_URL}/feedback/my`, {
      headers: hdr(patientToken)
    });

    const d = res.data.data;
    const ok = res.status === 200 && Array.isArray(d) && d.length > 0;
    logResult('GET /feedback/my', ok,
      ok ? `${d.length} ticket(s) returned` : res.data);
  } catch (err) {
    logResult('GET /feedback/my', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 5. Submit app rating ────────────────────────────
  console.log('\n⭐  Test 5 · Submit App Rating (4 stars)');
  try {
    const res = await axios.post(`${BASE_URL}/feedback/rate`, {
      rating: 4,
      message: 'Great app, love the interface!'
    }, { headers: hdr(patientToken) });

    const d = res.data.data;
    const ok = res.status === 201 && d && d.rating === 4 && d.category === 'app_rating';
    logResult('POST /feedback/rate', ok,
      ok ? `ID: ${d._id} | rating: ${d.rating}⭐` : res.data);
  } catch (err) {
    logResult('POST /feedback/rate', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 6. Validation — rating missing ─────────────────
  console.log('\n🚫  Test 6 · Validation — Missing Rating Value');
  try {
    await axios.post(`${BASE_URL}/feedback/rate`, {
      message: 'No rating value'
    }, { headers: hdr(patientToken) });

    logResult('POST /feedback/rate (no rating) should fail', false, 'Expected 400');
  } catch (err) {
    const ok = err.response && err.response.status === 400;
    logResult('POST /feedback/rate (no rating) → 400', ok,
      err.response ? err.response.data.message : err.message);
  }

  // ─── 7. Get latest rating ───────────────────────────
  console.log('\n⭐  Test 7 · Get Latest Rating');
  try {
    const res = await axios.get(`${BASE_URL}/feedback/latest-rating`, {
      headers: hdr(patientToken)
    });

    const d = res.data.data;
    const ok = res.status === 200 && d && d.rating === 4;
    logResult('GET /feedback/latest-rating', ok,
      ok ? `Rating: ${d.rating}⭐ | category: ${d.category}` : res.data);
  } catch (err) {
    logResult('GET /feedback/latest-rating', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 8. Unauthenticated access — should fail ────────
  console.log('\n🔒  Test 8 · Unauthenticated Access to /feedback/my');
  try {
    await axios.get(`${BASE_URL}/feedback/my`, { headers: hdr() });
    logResult('GET /feedback/my (no token) should fail', false, 'Expected 401');
  } catch (err) {
    const ok = err.response && err.response.status === 401;
    logResult('GET /feedback/my (no token) → 401', ok,
      err.response ? err.response.data.message : err.message);
  }

  // ─── 9. Admin — Get all feedback ────────────────────
  console.log('\n👑  Test 9 · Super Admin — Get All Feedback Tickets');
  try {
    const res = await axios.get(`${BASE_URL}/feedback`, {
      headers: hdr(adminToken),
      params: { page: 1, limit: 5 }
    });

    const d = res.data.data;
    const ok = res.status === 200 && d && d.feedbacks && d.pagination && d.pagination.total > 0;
    logResult('GET /feedback (super_admin)', ok,
      ok ? `${d.pagination.total} total · page ${d.pagination.page}/${d.pagination.totalPages}` : res.data);
  } catch (err) {
    logResult('GET /feedback (super_admin)', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 10. Admin — Filter by category ─────────────────
  console.log('\n👑  Test 10 · Super Admin — Filter by Category');
  try {
    const res = await axios.get(`${BASE_URL}/feedback`, {
      headers: hdr(adminToken),
      params: { category: 'bug', page: 1, limit: 5 }
    });

    const d = res.data.data;
    const ok = res.status === 200 && d && d.feedbacks
      && d.feedbacks.every(f => f.category === 'bug');
    logResult('GET /feedback?category=bug', ok,
      ok ? `${d.pagination.total} bug ticket(s)` : res.data);
  } catch (err) {
    logResult('GET /feedback?category=bug', false,
      err.response ? err.response.data : err.message);
  }

  // ─── 11. Admin — Update ticket status ───────────────
  if (createdFeedbackId) {
    console.log('\n👑  Test 11 · Super Admin — Update Ticket → in_progress');
    try {
      const res = await axios.put(`${BASE_URL}/feedback/${createdFeedbackId}`, {
        status: 'in_progress',
        adminNotes: 'Investigating the feature request — dark mode is planned for Q3.'
      }, { headers: hdr(adminToken) });

      const d = res.data.data;
      const ok = res.status === 200 && d && d.status === 'in_progress' && d.adminNotes;
      logResult('PUT /feedback/:id → in_progress', ok,
        ok ? `Status: ${d.status} | Notes: "${d.adminNotes.substring(0, 40)}..."` : res.data);
    } catch (err) {
      logResult('PUT /feedback/:id → in_progress', false,
        err.response ? err.response.data : err.message);
    }

    // ─── 12. Admin — Resolve ticket ───────────────────
    console.log('\n👑  Test 12 · Super Admin — Resolve Ticket');
    try {
      const res = await axios.put(`${BASE_URL}/feedback/${createdFeedbackId}`, {
        status: 'resolved',
        adminNotes: 'Dark mode shipped in v2.4.0'
      }, { headers: hdr(adminToken) });

      const d = res.data.data;
      const ok = res.status === 200 && d && d.status === 'resolved' && d.resolvedAt;
      logResult('PUT /feedback/:id → resolved', ok,
        ok ? `Resolved at: ${d.resolvedAt}` : res.data);
    } catch (err) {
      logResult('PUT /feedback/:id → resolved', false,
        err.response ? err.response.data : err.message);
    }
  } else {
    console.log('\n⏭️  Skipping Tests 11-12 (no feedback ID from Test 2)');
  }

  // ─── 13. Admin — Update invalid ID ──────────────────
  console.log('\n👑  Test 13 · Super Admin — Update Non-Existent Ticket');
  try {
    await axios.put(`${BASE_URL}/feedback/000000000000000000000000`, {
      status: 'closed'
    }, { headers: hdr(adminToken) });

    logResult('PUT /feedback/:invalid should → 404', false, 'Expected 404');
  } catch (err) {
    const ok = err.response && err.response.status === 404;
    logResult('PUT /feedback/:invalid → 404', ok,
      err.response ? err.response.data.message : err.message);
  }

  // ─── 14. Patient cannot access admin routes ─────────
  console.log('\n🔒  Test 14 · Patient Cannot Access Admin Route');
  try {
    await axios.get(`${BASE_URL}/feedback`, { headers: hdr(patientToken) });
    logResult('GET /feedback (patient) should → 403', false, 'Expected 403');
  } catch (err) {
    const ok = err.response && err.response.status === 403;
    logResult('GET /feedback (patient) → 403', ok,
      err.response ? err.response.data.message : err.message);
  }

  // ─── Done ────────────────────────────────────────────
  printSummary();
}

// ── Run ────────────────────────────────────────────────────
runTests().catch((err) => {
  console.error('\n💥 Unhandled error:', err.message);
  process.exit(1);
});
