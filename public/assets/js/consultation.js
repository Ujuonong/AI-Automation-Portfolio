/* ==========================================================================
   DE-JUNONG AI — AI Consultation chat
   Expects window.PORTFOLIO_CONSULT = {
     webhookUrl, source, welcome, error, contactLabel, contactUrl
   }
   Webhook communication lives in ConsultationApi, separate from the UI, so
   it can be swapped for any endpoint later (e.g. an n8n webhook).
   ========================================================================== */
(function () {
    "use strict";

    var CONFIG = window.PORTFOLIO_CONSULT || {};

    var NOT_CONFIGURED = !CONFIG.webhookUrl ||
        CONFIG.webhookUrl === "YOUR_WEBHOOK_URL_HERE" ||
        !/^https?:\/\/[^\s]+$/i.test(CONFIG.webhookUrl);

    /* ---------- Session helpers ---------- */

    function makeId() {
        if (window.crypto && typeof crypto.randomUUID === "function") {
            return crypto.randomUUID();
        }
        return "sess-" + Date.now().toString(36) + "-" + Math.random().toString(36).slice(2, 10);
    }

    function getSessionId() {
        var id = null;
        try { id = sessionStorage.getItem("portfolio_consult_session"); } catch (e) { /* ignore */ }
        if (!id) {
            id = makeId();
            try { sessionStorage.setItem("portfolio_consult_session", id); } catch (e) { /* ignore */ }
        }
        return id;
    }

    function nowIso() {
        return new Date().toISOString();
    }

    /* ---------- Webhook client (isolated from the UI) ---------- */

    var ConsultationApi = {
        send: function (message) {
            if (NOT_CONFIGURED) {
                return Promise.reject({ code: "not_configured" });
            }

            var payload = {
                message: message,
                session_id: getSessionId(),
                timestamp: nowIso(),
                source: CONFIG.source || "portfolio-ai-consultation",
                page: window.location.pathname
            };

            var timeout = new Promise(function (_, reject) {
                setTimeout(function () { reject({ code: "timeout" }); }, 30000);
            });

            var request = fetch(CONFIG.webhookUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload)
            }).then(function (res) {
                if (!res.ok) { throw { code: "http_" + res.status }; }
                return res.json();
            }).then(function (data) {
                var reply = data && (data.reply || data.message || data.content || data.text);
                if (typeof reply !== "string" || reply.trim() === "") {
                    throw { code: "empty_reply" };
                }
                return { reply: reply.trim() };
            });

            return Promise.race([request, timeout]);
        }
    };

    /* ---------- UI ---------- */

    var body = document.getElementById("chatBody");
    var form = document.getElementById("chatForm");
    var input = document.getElementById("chatInput");
    var send = document.getElementById("chatSend");
    var pending = false;

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    function toLocalTime(date) {
        return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    }

    function scrollToBottom() {
        if (body) { body.scrollTop = body.scrollHeight; }
    }

    function addMessage(role, text, opts) {
        var msg = document.createElement("div");
        msg.className = "msg msg--" + role + ((opts && opts.error) ? " msg--error" : "");

        var bubble = document.createElement("div");
        bubble.className = "msg-bubble";
        if (opts && opts.html) {
            bubble.innerHTML = opts.html;
        } else {
            bubble.textContent = text;
        }

        var time = document.createElement("span");
        time.className = "msg-time";
        time.textContent = toLocalTime(new Date());

        msg.appendChild(bubble);
        msg.appendChild(time);
        body.appendChild(msg);
        scrollToBottom();
        return msg;
    }

    function addTyping() {
        var msg = document.createElement("div");
        msg.className = "msg msg--ai";
        var bubble = document.createElement("div");
        bubble.className = "msg-bubble typing";
        for (var i = 0; i < 3; i++) { bubble.appendChild(document.createElement("span")); }
        msg.appendChild(bubble);
        body.appendChild(msg);
        scrollToBottom();
        return msg;
    }

    function removeTyping(el) {
        if (el && el.parentNode) { el.parentNode.removeChild(el); }
    }

    function updateSendState() {
        if (send) {
            send.disabled = pending || (input && input.value.trim() === "");
        }
    }

    function sendMessage() {
        var text = input.value.trim();
        if (!text || pending) { return; }

        addMessage("user", text);
        input.value = "";
        updateSendState();

        pending = true;
        var typingEl = addTyping();

        ConsultationApi.send(text)
            .then(function (res) {
                pending = false;
                removeTyping(typingEl);
                addMessage("ai", res.reply, {});
                updateSendState();
            })
            .catch(function () {
                pending = false;
                removeTyping(typingEl);
                var errorText = CONFIG.error ||
                    "Sorry, I couldn't connect right now. Please try again or use the contact form below.";
                addMessage("ai", errorText, {
                    error: true,
                    html: escapeHtml(errorText) +
                        " <a href=\"" + escapeHtml(CONFIG.contactUrl || "/contact") + "\">" +
                        escapeHtml(CONFIG.contactLabel || "contact form") + "</a>"
                });
                updateSendState();
            });
    }

    function init() {
        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                sendMessage();
            });
        }
        if (input) {
            input.addEventListener("input", updateSendState);
            input.addEventListener("keydown", function (e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
        updateSendState();
        if (CONFIG.welcome) {
            addMessage("ai", CONFIG.welcome, {});
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();