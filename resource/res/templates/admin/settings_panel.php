<div class="title-content">
    <span class="title">Cài Đặt</span>
</div>
<div class="block-content overflow scrollbar">
    <div class="content fadeRight">
        <div class="preload hidden" id="preload">
            <img src="res/img/loading.gif" alt="">
        </div>
        <form action="" method="POST" id="form_settings">
            <div class="row">
                <div class="col s12">
                    <div class="col l6 s12">
                        <div>
                            <label for="sepay_ngan_hang">Ngân hàng thụ hưởng <span style="color: red;">(*)</span></label>
                            <select name="settings[sepay_ngan_hang]" id="sepay_ngan_hang">
                                <?php foreach ($data['SEPAY_NGAN_HANG'] as $nganHang) : ?>
                                    <option value="<?= $nganHang['short_name'] ?>" <?= $nganHang['short_name'] == $data['settings']['sepay_ngan_hang'] ? 'selected' : '' ?>><b><?= $nganHang['short_name'] ?></b></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div>
                            <label for="sepay_so_tai_khoan">Số tài khoản <span style="color: red;">(*)</span></label>
                            <input type="text" name="settings[sepay_so_tai_khoan]" id="sepay_so_tai_khoan" required value="<?= $data['settings']['sepay_so_tai_khoan'] ?: '' ?>" placeholder="Chỉ nhập chữ, số, không dấu.">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="col l12">
                        <button type="submit" class="btn">Lưu</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="res/js/settings_panel.js"></script>