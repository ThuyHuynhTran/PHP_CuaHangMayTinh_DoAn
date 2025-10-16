<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mai Cồ Shop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    {{-- ========================================================= --}}
    {{-- ✅ 1. THÔNG BÁO FLASH --}}
    {{-- ========================================================= --}}
    @if(session('success'))
        <div class="auto-hide-alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            background: #d4edda; color: #155724; padding: 15px 25px; border-radius: 8px;
            z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="auto-hide-alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            background: #f8d7da; color: #721c24; padding: 15px 25px; border-radius: 8px;
            z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- ========================================================= --}}
    {{-- 🚀 Nút Liên hệ & Lên đầu --}}
    {{-- ========================================================= --}}
    <div id="floating-buttons">
        <button id="btn-contact" class="floating-btn" style="background-color: #e21b1b;">
            <i class="fas fa-headset"></i> Liên hệ
        </button>
        <button id="btn-top" class="floating-btn" style="background-color: #333;">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    {{-- 💬 Popup Liên hệ --}}
    <div id="contact-popup">
        <div class="popup-header">
            <i class="fas fa-comments"></i>
            <span>Chat với nhân viên tư vấn</span>
            <button id="close-popup">&times;</button>
        </div>

        <div class="popup-body">

            {{-- 💡 Gợi ý FAQ --}}
            <div class="faq-preview" style="margin-bottom: 15px;">
                <p><strong>❓ Câu hỏi thường gặp:</strong></p>
                <ul style="padding-left: 18px; margin: 5px 0;">
                    <li><a href="#" class="faq-link" data-target="faq1">Chính sách bảo hành</a></li>
                    <li><a href="#" class="faq-link" data-target="faq2">Thời gian giao hàng</a></li>
                    <li><a href="#" class="faq-link" data-target="faq3">Hướng dẫn thanh toán</a></li>
                </ul>
            </div>

            {{-- 💬 Vùng hiển thị nội dung câu hỏi (ẩn mặc định) --}}
            <div id="faq-content" style="display:none; background:#fff4f4; padding:10px; border-radius:8px; margin-bottom:15px;">
                <div id="faq1" class="faq-item" style="display:none;">
                    <strong>🔧 Chính sách bảo hành:</strong>
                    <p>Mai Cồ Shop hỗ trợ bảo hành chính hãng 12 tháng đối với tất cả sản phẩm điện thoại và phụ kiện chính hãng.</p>
                </div>
                <div id="faq2" class="faq-item" style="display:none;">
                    <strong>🚚 Thời gian giao hàng:</strong>
                    <p>Thời gian giao hàng từ 1–3 ngày tại nội thành và 3–7 ngày đối với khu vực tỉnh xa.</p>
                </div>
                <div id="faq3" class="faq-item" style="display:none;">
                    <strong>💳 Hướng dẫn thanh toán:</strong>
                    <p>Quý khách có thể thanh toán qua chuyển khoản, ví điện tử, hoặc trả tiền khi nhận hàng (COD).</p>
                </div>
                <button id="close-faq" style="margin-top:8px; background:#e21b1b; color:white; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">
                    Đóng câu hỏi
                </button>
            </div>

            <form id="contact-form">
                <label>Họ tên*</label>
                <input type="text" id="chat-name" placeholder="Nhập tên của bạn" required>

                <label>Email*</label>
                <input type="email" id="chat-email" placeholder="Nhập email của bạn" required>

                <label>Số điện thoại*</label>
                <input type="tel" id="chat-phone" placeholder="Nhập số điện thoại của bạn" required>

                <label>Giới tính</label>
                <select>
                    <option>Nam</option>
                    <option>Nữ</option>
                    <option>Khác</option>
                </select>

                <label>Tin nhắn*</label>
                <textarea id="chat-message" rows="3" placeholder="Nhập tin nhắn..." required></textarea>

                <button type="button" id="btn-start-chat" class="btn-send">
                    <i class="fas fa-paper-plane"></i> Bắt đầu trò chuyện
                </button>
            </form>
        </div>
    </div>

    {{-- 💬 Khung chat --}}
    <div id="chat-window">
        <div class="popup-header">
    <i class="fas fa-robot"></i> Nhân viên hỗ trợ
    <div style="display:flex; align-items:center; gap:8px;">
        <button id="menu-toggle" style="background:none;border:none;color:white;font-size:18px;cursor:pointer;">
            <i class="fas fa-bars"></i>
        </button>
        <button id="close-chat" style="background:none;border:none;color:white;font-size:20px;cursor:pointer;">&times;</button>
    </div>
</div>

<!-- 🔽 Dropdown menu ẩn mặc định -->
<div id="chat-menu" style="display:none; position:absolute; right:35px; top:55px;
    background:white; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.2);
    padding:8px; z-index:99999; width:180px;">
    <button id="end-chat" style="width:100%; border:none; background:#e21b1b; color:white;
        padding:8px; border-radius:6px; font-weight:600; cursor:pointer;">
        <i class="fas fa-power-off"></i> Kết thúc trò chuyện
    </button>
</div>

        <div id="chat-messages" class="chat-body">
            <div class="chat-message left">
                <strong>Admin:</strong> Xin chào! Em ở đây để hỗ trợ cho anh/chị ạ 💬
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="chat-input" placeholder="Nhập nội dung...">
            <button id="send-chat"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- ✅ CSS POPUP --}}
    {{-- ========================================================= --}}
    <style>
        #floating-buttons { position: fixed; right: 25px; bottom: 25px;
            display: flex; flex-direction: column; align-items: flex-end;
            gap: 10px; z-index: 9999; }
        .floating-btn { border: none; color: white; border-radius: 50px;
            padding: 10px 16px; font-weight: 600;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer; transition: all 0.3s ease;
            display: flex; align-items: center; gap: 6px; }
        .floating-btn:hover { transform: translateY(-2px); opacity: 0.9; }
        #contact-popup, #chat-window { display: none; position: fixed;
            bottom: 90px; right: 25px; width: 320px; background: white;
            border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10000; overflow: hidden; flex-direction: column; }
        .popup-header { background: #e21b1b; color: white; padding: 10px 15px;
            display: flex; align-items: center; justify-content: space-between; font-weight: 600; }
        .popup-body { padding: 15px; }
        .popup-body input, .popup-body textarea, .popup-body select {
            width: 100%; padding: 8px; border: 1px solid #ddd;
            border-radius: 6px; margin-bottom: 10px; }
        .btn-send { background: #e21b1b; color: white; width: 100%;
            padding: 10px; border: none; border-radius: 6px;
            font-weight: bold; cursor: pointer; }
        .chat-body { max-height: 250px; overflow-y: auto; background: #f8f8f8; padding: 10px; }
        .chat-message { margin: 6px 0; padding: 8px 10px; border-radius: 8px; max-width: 80%; }
        .chat-message.left { background: #ffeaea; align-self: flex-start; }
        .chat-message.right { background: #d1e7ff; align-self: flex-end; }
        .chat-input { display: flex; border-top: 1px solid #ddd; }
        .chat-input input { flex: 1; padding: 10px; border: none; outline: none; }
        .chat-input button { background: #e21b1b; color: white; border: none; width: 50px; cursor: pointer; }
        .faq-preview a { color: #007bff; text-decoration: none; cursor: pointer; }
        .faq-preview a:hover { text-decoration: underline; }
    </style>

    {{-- ========================================================= --}}
    {{-- ✅ SCRIPT POPUP + CHAT + FAQ --}}
    {{-- ========================================================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnTop = document.getElementById("btn-top");
    const btnContact = document.getElementById("btn-contact");
    const contactPopup = document.getElementById("contact-popup");
    const closePopup = document.getElementById("close-popup");
    const btnStartChat = document.getElementById("btn-start-chat");
    const chatWindow = document.getElementById("chat-window");
    const closeChat = document.getElementById("close-chat");
    const chatMessages = document.getElementById("chat-messages");
    const input = document.getElementById("chat-input");
    const sendBtn = document.getElementById("send-chat");

    // 🧭 Lên đầu trang
    btnTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));

    // ☎️ Mở form liên hệ
    btnContact.addEventListener("click", () => {
        contactPopup.style.display = "block";
        chatWindow.style.display = "none";
    });

    // ❌ Đóng popup
    closePopup.addEventListener("click", () => contactPopup.style.display = "none");

    // ❌ Đóng khung chat
    closeChat.addEventListener("click", () => chatWindow.style.display = "none");

    // 💬 GỬI TIN NHẮN ĐẦU TIÊN
    btnStartChat.addEventListener("click", function () {
        const name = document.getElementById("chat-name").value.trim();
        const email = document.getElementById("chat-email").value.trim();
        const phone = document.getElementById("chat-phone").value.trim();
        const firstMsg = document.getElementById("chat-message").value.trim();

        if (!name || !email || !firstMsg) {
            alert("Vui lòng nhập đầy đủ Họ tên, Email và Tin nhắn!");
            return;
        }

        sessionStorage.setItem("chat_name", name);
        sessionStorage.setItem("chat_email", email);
        sessionStorage.setItem("chat_phone", phone);

        contactPopup.style.display = "none";
        chatWindow.style.display = "flex";

        appendMessage("Bạn", firstMsg, "right");
        sendToServer({ name, email, phone, message: firstMsg }, true);

        document.getElementById("contact-form").reset();
    });

    // 🚀 GỬI TIN NHẮN TIẾP THEO
    sendBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;

        appendMessage("Bạn", msg, "right");
        input.value = "";

        const name = sessionStorage.getItem("chat_name") || "Khách";
        const email = sessionStorage.getItem("chat_email") || "Không rõ";
        const phone = sessionStorage.getItem("chat_phone") || "";

        sendToServer({ name, email, phone, message: msg }, false);
    });

    // 💾 GỬI LÊN SERVER
    function sendToServer(data, isFirstMessage = false) {
        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.ok ? res.json() : null)
        .then(res => {
            if (res && res.success && isFirstMessage) {
                setTimeout(() => appendMessage("Admin", "Cảm ơn bạn! Chúng tôi sẽ phản hồi sớm ❤️", "left"), 800);
            }
        })
        .catch(() => {});
    }

    // 🧩 Thêm tin nhắn vào giao diện
    function appendMessage(sender, text, side) {
        const div = document.createElement("div");
        div.classList.add("chat-message", side);
        div.innerHTML = `<strong>${sender}:</strong> ${text}`;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // 💡 FAQ trong popup
    const faqLinks = document.querySelectorAll(".faq-link");
    const faqContent = document.getElementById("faq-content");
    const closeFaq = document.getElementById("close-faq");

    faqLinks.forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();
            document.querySelectorAll(".faq-item").forEach(i => i.style.display = "none");
            const target = document.getElementById(link.dataset.target);
            if (target) {
                faqContent.style.display = "block";
                target.style.display = "block";
            }
        });
    });

    closeFaq.addEventListener("click", () => {
        faqContent.style.display = "none";
        document.querySelectorAll(".faq-item").forEach(i => i.style.display = "none");
    });

    // ⚙️ Menu 3 gạch
    const menuToggle = document.getElementById("menu-toggle");
    const chatMenu = document.getElementById("chat-menu");
    const endChat = document.getElementById("end-chat");

    menuToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        chatMenu.style.display = chatMenu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", (e) => {
        if (!chatMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            chatMenu.style.display = "none";
        }
    });

    // 🧹 Kết thúc trò chuyện
    endChat.addEventListener("click", () => {
        if (confirm("Bạn có chắc muốn kết thúc cuộc trò chuyện không?")) {
            sessionStorage.clear();
            chatMessages.innerHTML = `
                <div class="chat-message left">
                    <strong>Hệ thống:</strong> Cuộc trò chuyện đã kết thúc. Cảm ơn bạn đã liên hệ ❤️
                </div>
            `;
            setTimeout(() => {
                chatWindow.style.display = "none";
                chatMenu.style.display = "none";
            }, 1500);
        }
    });
});
</script>

</body>
</html>
