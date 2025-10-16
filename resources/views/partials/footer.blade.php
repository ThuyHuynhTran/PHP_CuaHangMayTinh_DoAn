<footer class="footer">
  <div class="footer-container">
    <!-- Cột Đăng ký nhận tin khuyến mãi -->
    <div class="footer-column promo-box">
      <h3><i class="fas fa-envelope"></i> ĐĂNG KÝ NHẬN TIN KHUYẾN MÃI</h3>
      <div class="promo-content">
        <p class="highlight">Nhận ngay voucher 10%</p>
        <p class="subtext">Voucher sẽ được gửi sau 24h, chỉ áp dụng cho khách hàng mới</p>

        <form id="promoForm">
          <label>Email</label>
          <input type="email" id="promoEmail" placeholder="Nhập email của bạn" required>

          <label>Số điện thoại</label>
          <input type="tel" id="promoPhone" placeholder="Nhập số điện thoại của bạn">

          <div class="checkbox-group">
            <input type="checkbox" id="promoAgree" required>
            <label for="promoAgree">Tôi đồng ý với <a href="#" class="terms-link">điều khoản của Mai Cồ Shop</a></label>
          </div>

          <button type="submit" class="promo-btn">ĐĂNG KÝ NGAY</button>
          <p id="promoMsg"></p>
        </form>
      </div>
    </div>

    <!-- Cột 2 -->
    <div class="footer-column">
      <h3>Tổng đài hỗ trợ</h3>
      <ul>
        <li>Gọi mua: <strong class="hotline">1900 232 460</strong> (8:00 - 21:30)</li>
        <li>Khiếu nại: <strong class="hotline">1800 1062</strong> (8:00 - 21:30)</li>
        <li>Bảo hành: <strong class="hotline">1900 232 464</strong> (8:00 - 21:00)</li>
      </ul>
    </div>

    <!-- Cột 3 -->
    <div class="footer-column">
      <h3>Về công ty</h3>
      <ul>
        <li><a href="#">Giới thiệu công ty (MCShop.vn)</a></li>
        <li><a href="#">Tuyển dụng</a></li>
        <li><a href="#">Gửi góp ý, khiếu nại</a></li>
        <li><a href="#">Tìm cửa hàng (2.963 shop)</a></li>
      </ul>
    </div>

    <!-- Cột 4 -->
    <div class="footer-column">
      <h3>Thông tin khác</h3>
      <ul>
        <li><a href="#">Tích điểm Quà tặng VIP</a></li>
        <li><a href="#">Lịch sử mua hàng</a></li>
        <li><a href="#">Đăng ký bán hàng CTV chiết khấu cao</a></li>
        <li><a href="#">Chính sách bảo hành</a></li>
      </ul>
    </div>

    <!-- Cột 5 -->
    <div class="footer-column">
      <h3>Website cùng tập đoàn</h3>
      <div class="footer-logos">
        <a href="#"><img src="{{ asset('assets/img/topzone.jpg') }}" alt="Topzone"></a>
        <a href="#"><img src="{{ asset('assets/img/dienmayxanh.jpg') }}" alt="Điện máy Xanh"></a>
        <a href="#"><img src="{{ asset('assets/img/cellphone.jpg') }}" alt="CellphoneS"></a>
        <a href="#"><img src="{{ asset('assets/img/tgdd.png') }}" alt="TGDD"></a>
      </div>

      <div class="socials">
        <a href="#"><i class="fab fa-facebook"></i> 3886.8k Fan</a>
        <a href="#"><i class="fab fa-youtube"></i> 875k Đăng ký</a>
        <a href="#"><i class="fab fa-zalo"></i> Zalo MCShop</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 Mai Cồ Shop - Thiết bị công nghệ chính hãng.</p>
  </div>
</footer>

<style>
.footer {
  background: #fff;
  color: #000;
  font-family: Arial, sans-serif;
}
.footer-container {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 30px;
  width: 90%;
  margin: auto;
  padding: 30px 0;
  border-top: 1px solid #eee;
}

/* --- Cột khuyến mãi --- */
.promo-box {
  grid-column: 1 / 2; /* ✅ Nằm bên trái */
  border-right: 1px solid #eee;
  padding-right: 25px;
}
.promo-box h3 {
  font-size: 17px;
  text-transform: uppercase;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.promo-box .highlight {
  color: #e21b1b;
  font-weight: bold;
}
.promo-box .subtext {
  font-size: 13px;
  color: #555;
  margin-bottom: 10px;
}
#promoForm label {
  display: block;
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 500;
}
#promoForm input[type="email"],
#promoForm input[type="tel"] {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 10px;
  font-size: 14px;
}
.checkbox-group {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
  font-size: 13px;
  color: #444;
}
.checkbox-group input[type="checkbox"] {
  accent-color: #e21b1b;
  transform: scale(1.2);
}
.checkbox-group a.terms-link {
  color: #e21b1b;
  text-decoration: none;
  font-weight: 500;
}
.checkbox-group a.terms-link:hover {
  text-decoration: underline;
}
.promo-btn {
  width: 100%;
  background: #e21b1b;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
.promo-btn:hover {
  background: #b91515;
}
#promoMsg {
  margin-top: 8px;
  font-size: 13px;
  font-weight: bold;
}

/* --- Các cột khác --- */
.footer-column h3 {
  color: #000;
  font-size: 17px;
  margin-bottom: 10px;
  text-transform: uppercase;
}
.footer-column ul {
  list-style: none;
  padding: 0;
}
.footer-column ul li {
  margin-bottom: 6px;
  font-size: 14px;
}
.footer-column ul li a {
  text-decoration: none;
  color: #333;
}
.footer-column ul li a:hover {
  color: #e21b1b;
}
.footer-logos img {
  width: 60px;
  height: 30px;
  object-fit: contain;
  margin-right: 6px;
  border-radius: 4px;
}
.footer .socials a {
  display: block;
  font-size: 14px;
  color: #333;
  text-decoration: none;
  margin-top: 5px;
}
.footer .socials a:hover {
  color: #e21b1b;
}
.footer-bottom {
  background: #c21b1b;
  color: #fff;
  text-align: center;
  padding: 10px;
  font-size: 14px;
}

/* --- Responsive --- */
@media (max-width: 1024px) {
  .footer-container {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (max-width: 768px) {
  .footer-container {
    grid-template-columns: 1fr;
  }
}
</style>

<script>
document.getElementById('promoForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const email = document.getElementById('promoEmail').value.trim();
  const phone = document.getElementById('promoPhone').value.trim();
  const msg = document.getElementById('promoMsg');

  try {
    const res = await fetch("{{ route('promotion.subscribe') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ email, phone })
    });
    const data = await res.json();
    if (data.success) {
      msg.textContent = "🎉 Đăng ký thành công! Cảm ơn bạn ❤️";
      msg.style.color = "green";
      document.getElementById('promoForm').reset();
    } else {
      msg.textContent = data.message || "Email đã tồn tại hoặc lỗi hệ thống.";
      msg.style.color = "red";
    }
  } catch (err) {
    msg.textContent = "Lỗi kết nối máy chủ, vui lòng thử lại!";
    msg.style.color = "red";
  }
});
</script>
