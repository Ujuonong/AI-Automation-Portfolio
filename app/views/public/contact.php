<?php
/**
 * Contact page + enquiry form.
 * Vars: $csrf, $errors, $form_success, $site, $enquiry_types, $selected_enquiry
 */
use Portfolio\Core\Session;

$errors      = $errors ?? [];
$old         = Session::flush('old_input', []);
$types       = $enquiry_types ?? [];
$auditKey    = 'ai_automation_audit';
$today       = date('Y-m-d');

$selected = isset($old['enquiry_type']) && $old['enquiry_type'] !== ''
    ? (string) $old['enquiry_type']
    : (string) ($selected_enquiry ?? '');
if (!array_key_exists($selected, $types)) {
    $selected = '';
}

$scrollToForm = $selected !== '' && empty($old);
$isAuditSelected = $selected === $auditKey;
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="eyebrow"><?= e(content('contact_eyebrow')) ?></div>
        <h1 id="contact-heading"><?= e(content('contact_heading')) ?></h1>
        <p class="lead"><?= e(content('contact_intro')) ?></p>

        <?php if ($form_success): ?>
            <div class="alert alert--success" role="status"><?= e($form_success) ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert--error" id="formErrors" role="alert">
                <strong>Please fix the following:</strong>
                <ul id="errorList" style="margin:8px 0 0;padding-left:18px;">
                    <?php foreach ($errors as $field => $error): ?>
                    <li data-field="<?= e($field) ?>"><?= e(is_array($error) ? implode(' ', $error) : $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="contactForm" method="post" action="<?= e(url('/contact')) ?>" class="mt-3" novalidate>
            <?= $csrf ?>
            <div class="honeypot" aria-hidden="true">
                <label for="website">Do not fill this in</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="name">Full Name *</label>
                    <input class="input" type="text" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required maxlength="150" autocomplete="name">
                </div>
                <div class="field">
                    <label for="email">Email *</label>
                    <input class="input" type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required maxlength="190" autocomplete="email">
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="phone">Phone / WhatsApp *</label>
                    <input class="input" type="tel" id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>" required maxlength="30" inputmode="tel" autocomplete="tel" placeholder="+234 801 234 5678">
                </div>
                <div class="field">
                    <label for="company">Company / Business Name</label>
                    <input class="input" type="text" id="company" name="company" value="<?= e($old['company'] ?? '') ?>" maxlength="150" autocomplete="organization">
                </div>
            </div>

            <div class="field">
                <label for="enquiry_type">What can I help you with? *</label>
                <select class="select input" id="enquiry_type" name="enquiry_type" required>
                    <option value="">Select a service...</option>
                    <?php foreach ($types as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= $selected === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field" id="auditFields" style="<?= $isAuditSelected ? '' : 'display:none' ?>">
                <label for="preferred_audit_date">Preferred Audit Date *</label>
                <input class="input" type="date" id="preferred_audit_date" name="preferred_audit_date" min="<?= e($today) ?>" value="<?= e($old['preferred_audit_date'] ?? '') ?>" <?= $isAuditSelected ? 'required' : '' ?>>
                <label for="preferred_audit_time" style="margin-top:12px;">Preferred Audit Time (optional)</label>
                <select class="select input" id="preferred_audit_time" name="preferred_audit_time">
                    <option value="">No preference</option>
                    <?php
                    $timeSlots = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30',
                                  '12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30',
                                  '16:00','16:30','17:00'];
                    $currentTime = $old['preferred_audit_time'] ?? '';
                    foreach ($timeSlots as $slot):
                        $label12h = date('g:i a', strtotime($slot));
                    ?>
                    <option value="<?= e($slot) ?>" <?= $currentTime === $slot ? 'selected' : '' ?>><?= e($label12h) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="message">Tell me about your business and the process you'd like to automate. *</label>
                <textarea class="input" id="message" name="message" rows="7" required minlength="10"><?= e($old['message'] ?? '') ?></textarea>
            </div>

            <div class="field">
                <label for="additional_info">Additional Information</label>
                <textarea class="input" id="additional_info" name="additional_info" rows="4"><?= e($old['additional_info'] ?? '') ?></textarea>
            </div>

            <button class="btn btn--primary btn--lg" type="submit"><?= e(content('contact_submit')) ?></button>
        </form>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container container--narrow">
        <div class="grid grid--3">
            <?php if (!empty($site['email'])): ?>
            <div class="cert" style="align-items:flex-start;">
                <div class="cert-icon"><i class="fa-solid fa-envelope"></i></div>
                <h3><?= e(content('contact_email_label')) ?></h3>
                <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
            </div>
            <?php endif; ?>
            <?php if (!empty($site['phone'])): ?>
            <div class="cert" style="align-items:flex-start;">
                <div class="cert-icon"><i class="fa-solid fa-phone"></i></div>
                <h3><?= e(content('contact_phone_label')) ?></h3>
                <a href="tel:<?= e(preg_replace('/[^+\d]/', '', (string) $site['phone'])) ?>"><?= e($site['phone']) ?></a>
            </div>
            <?php endif; ?>
            <?php if (!empty($site['location'])): ?>
            <div class="cert" style="align-items:flex-start;">
                <div class="cert-icon"><i class="fa-solid fa-location-dot"></i></div>
                <h3><?= e(content('contact_location_label')) ?></h3>
                <span class="muted"><?= e($site['location']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
(function () {
    "use strict";

    var form      = document.getElementById("contactForm");
    var select    = document.getElementById("enquiry_type");
    var auditBox  = document.getElementById("auditFields");
    var auditDate = document.getElementById("preferred_audit_date");
    var auditTime = document.getElementById("preferred_audit_time");
    var AUDIT_KEY = <?= json_encode($auditKey) ?>;

    /* ---------- toggle audit fields ---------- */

    function syncAudit () {
        var audit = select && select.value === AUDIT_KEY;
        if (auditBox) { auditBox.style.display = audit ? "" : "none"; }
        if (auditDate) { auditDate.required = audit; }
    }
    if (select) { select.addEventListener("change", syncAudit); }
    syncAudit();

    /* ---------- scroll to form when arriving via CTA ---------- */

    <?php if ($scrollToForm): ?>
    if (form) { form.scrollIntoView({ behavior: "smooth", block: "start" }); }
    <?php endif; ?>

    /* ---------- client-side validation ---------- */

    function trim (s) { return (s || "").replace(/^\s+|\s+$/g, ""); }

    function normalizePhone (v) {
        v = trim(v).replace(/[\s\-\.\(\)]+/g, "");
        var m;
        if ((m = v.match(/^0([7-9][01]\d{8})$/)))  { return "+234" + m[1]; }
        if ((m = v.match(/^234([7-9][01]\d{8})$/))) { return "+234" + m[1]; }
        if (/^\+?[1-9]\d{7,14}$/.test(v))            { return "+" + v.replace(/^\+/, ""); }
        return "";
    }

    function isValidEmail (v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }

    function clearFieldErrors () {
        var list = document.getElementById("errorList");
        if (list) { list.innerHTML = ""; }
        var errBox = document.getElementById("formErrors");
        if (errBox) { errBox.style.display = "none"; }
        var fields = form.querySelectorAll("[aria-invalid]");
        for (var i = 0; i < fields.length; i++) { fields[i].removeAttribute("aria-invalid"); }
    }

    function showFieldError (fieldId, msg) {
        var el = document.getElementById(fieldId);
        if (el) { el.setAttribute("aria-invalid", "true"); }
        var list = document.getElementById("errorList");
        if (list) {
            var li = document.createElement("li");
            li.setAttribute("data-field", fieldId);
            li.textContent = msg;
            list.appendChild(li);
        }
        var errBox = document.getElementById("formErrors");
        if (errBox) { errBox.style.display = ""; }
    }

    if (form) {
        form.addEventListener("submit", function (e) {
            clearFieldErrors();
            var first = null;
            var audit = select && select.value === AUDIT_KEY;

            if (trim(form.querySelector("#name").value) === "") {
                showFieldError("name", "Please enter your name.");
                if (!first) first = form.querySelector("#name");
            }

            var email = trim(form.querySelector("#email").value);
            if (email === "") {
                showFieldError("email", "Please enter your email address.");
                if (!first) first = form.querySelector("#email");
            } else if (!isValidEmail(email)) {
                showFieldError("email", "Please enter a valid email address.");
                if (!first) first = form.querySelector("#email");
            }

            var phone = form.querySelector("#phone").value;
            if (trim(phone) === "") {
                showFieldError("phone", "Please enter your phone or WhatsApp number.");
                if (!first) first = form.querySelector("#phone");
            } else if (normalizePhone(phone) === "") {
                showFieldError("phone", "Please enter a valid phone or WhatsApp number.");
                if (!first) first = form.querySelector("#phone");
            }

            if (select.value === "") {
                showFieldError("enquiry_type", "Please select what you need help with.");
                if (!first) first = select;
            }

            if (audit) {
                var d = trim(auditDate.value);
                if (d === "") {
                    showFieldError("preferred_audit_date", "Please select your preferred audit date.");
                    if (!first) first = auditDate;
                } else if (d < <?= json_encode($today) ?>) {
                    showFieldError("preferred_audit_date", "Please select a future date.");
                    if (!first) first = auditDate;
                }
            }

            var msg = trim(form.querySelector("#message").value);
            if (msg.length < 10) {
                showFieldError("message", "Please tell me a little about your business and the process you'd like to automate.");
                if (!first) first = form.querySelector("#message");
            }

            if (first) {
                e.preventDefault();
                first.focus();
            }
        });
    }
})();
</script>
