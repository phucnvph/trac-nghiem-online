<div class="title-content">
	<span class="title">Danh Sách Bài Tập/Thi</span>
</div>
<div class="block-content overflow scrollbar">
	<div class="content" style="padding-top: 10px">
		<div class="row col l12">
			<?php
			for ($i = 0; $i < count($tests); $i++) {
				?>
				<div class="col l3 m4 s6">
					<div id="tests_list">
						<span class="title">Tên: <?=$tests[$i]->test_name?></span><br />
						<span class="title">Môn: <?=$tests[$i]->subject_detail?></span><br />
						<span class="title">Khối: <?=$tests[$i]->grade?></span><br />
						<span class="title">Mã Đề: <?=$tests[$i]->test_code?></span><br />
						<span class="title">Số Câu Hỏi: <?=$tests[$i]->total_questions?></span><br />
						<span class="title">Thời Gian: <?=$tests[$i]->time_to_do?> Phút</span><br />
						<span class="title">Trạng Thái: <?=$tests[$i]->status?></span><br />
						<span class="title">Ghi Chú: <?=$tests[$i]->note?></span><br />
						<?php
						if($tests[$i]->status_id != 2)
						{
							$flag = false;
							for($j = 0; $j < count($scores); $j++) {
								if($tests[$i]->test_code == $scores[$j]->test_code) {
									$flag = true;
									break;
								}
							}
							if($flag)
								echo '<a href="xem-ket-qua-'.$tests[$i]->test_code.'" class="btn full-width">Chi Tiết Bài Làm</a>';
							else {
								?>
								<a class="waves-effect waves-light btn modal-trigger full-width" style="margin-bottom: 7px;" href="#do-test-<?=$tests[$i]->test_code?>" id="do_test">Làm Bài</a>
								<div id="do-test-<?=$tests[$i]->test_code?>" class="modal">
									<div class="row col l12">
										<form class="form_test" action="" method="POST" role="form" id="form_submit_test_<?=$tests[$i]->test_code?>">
											<div class="modal-content"><h5>Nhập mật khẩu đề: <?=$tests[$i]->test_code?></h5>
												<div class="modal-body">
													<div class="input-field">
														<input type="hidden" value="<?=$tests[$i]->test_code?>" name="test_code" id="test_code">
														<input type="password" name="password" id="password" required>
														<label for="password">Mật Khẩu</label>
													</div>
												</div>
											</div><div class="col l12 s12">
												<div class="modal-footer">
													<a href="#" class="waves-effect waves-green btn-flat modal-action modal-close">Trở Lại</a>
													<button type="submit" class="waves-effect waves-green btn-flat modal-action modal-close">Đồng Ý</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								<?php
							}
						} else {
							$flag_2 = false;
							for($j = 0; $j < count($scores); $j++) {
								if($tests[$i]->test_code == $scores[$j]->test_code) {
									$flag_2 = true;
									break;
								}
							}
							if($flag_2)
								echo '<a href="xem-ket-qua-'.$tests[$i]->test_code.'" class="btn full-width">Chi Tiết Bài Làm</a>';
							else
								echo '<button class="btn full-width" disabled>Chi Tiết Bài Làm</button>';
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</div>		
	</div>
</div>
</div>

<?php if ($remaining_tests <= 0) : ?>
<!-- Modal thông báo hết lượt thi -->
<div id="no-attempts-modal" class="modal">
    <div class="modal-content">
        <h4><i class="material-icons left red-text">warning</i>Hết lượt thi</h4>
        <p>Bạn đã hết lượt thi. Vui lòng mua thêm gói thi để tiếp tục làm bài.</p>
        <div class="row">
            <div class="col s12">
                <div class="card orange lighten-4">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">info</i>Thông tin</span>
                        <p>• Mỗi lần làm bài thi sẽ tiêu tốn 1 lượt thi</p>
                        <p>• Bạn có thể mua thêm gói thi với giá ưu đãi</p>
                        <p>• Lượt thi không bị mất khi thoát giữa chừng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <!-- <a href="#!" class="modal-close waves-effect waves-red btn-flat">Để sau</a> -->
        <a href="index.php?action=show_packages" class="waves-effect waves-green btn green">
            <i class="material-icons left">shopping_cart</i>Mua Gói Thi
        </a>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.modal').modal();

	$('#no-attempts-modal').modal('open');
});
</script>
<?php endif ?>