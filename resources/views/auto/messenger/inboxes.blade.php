@extends('master')
@section('page', 'Nhắn tin tới tất cả mọi người')
@section('page-content')
<div id="messenger" class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				Auto send messages to everyone
			</div>
			<div class="box-body">
				<form v-on:submit.prevent class="form-horizontal" role="form">
					<div class="form-group">
						<label for="user" class="control-label col-md-2">Lấy danh sách bạn của:</label>
						<div class="col-md-3">
							<div class="input-group">
								<input v-model="uid"
									type="text"
									class="form-control"
									name="provider_uid"
									placeholder="ID Facebook" />
								<span class="input-group-btn">
									<button v-on:click="getListFriends(uid)"
										type="button"
										class="btn btn-info btn-flat">Lấy
									</button>
								</span>
							</div>
						</div>
						
						<label for="user" class="control-label col-md-2">Thời gian để gửi tin nhắn:</label>
						<div class="col-md-5">
							<b-form-slider ref="range" v-model="seconds_range.range" range :min="seconds_range.min" :max="seconds_range.max" trigger-change-event></b-form-slider>
							<p style="margin-top: 5px">
								<span>@{{ seconds_range.range[0] }}</span> ~ <span>@{{ seconds_range.range[1] }}</span> Giây
							</p>
						</div>
					</div>

					<div class="form-group">
						<label for="user" class="control-label col-sm-2">Trừ những ID (nếu có):</label>
						<div class="col-md-3">
							<textarea name="except_ids" id="except_ids" class="form-control" rows="5">{{ "100003174541290\n100001035017950\n100001803213569\n100000373519596\n100003016789413\n100004928497216\n100005072629059\n100005683472364\n100008935622801\n100014244607778\n100017529752410\n100030683802530\n100034043250840\n100031068490326" }}</textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<textarea id="messages" class="form-control" name="messages" rows="7" placeholder="Nhập tin nhắn cần gửi...">Hệ thống auto inbox rất xịn!</textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6">
							<input type="file" id="files" name="files" multiple>
						</div>
					</div>
				</form>

				<h4>Gửi tin nhắn đến <span class="label label-success" id="messages_success">0</span> bạn thành công.</h4>
				<table class="table table-striped table-bordered table-hover" id="dataTables-send_sms">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Status</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="box-footer">
				<button id="start-send" class="btn btn-info pull-right">Bắt đầu</button>
				<button id="stop-send" class="btn btn-info pull-right">Dừng</button>
			</div>
		</div>
	</div>
</div>
@endsection
@push('script')
<script>
	var app = new Vue({
		el: '#messenger',
		data: {
			uid: null,
			user: null,
			friends: null,
			has_friends: false,
			stop_send: false,
			seconds_range: {
				range: [10, 20],
				min: 5,
				max: 50,
				start: 10,
				end: 20
			},
			libs: {
				tbl_friends: $('#dataTables-send_sms'),
				slider: $('#slider'),
				filestyle: $(':file')
			}
		},
		created: function() {
			let app = this,
				libs = app.libs;

			/* DataTable */
			libs.tbl_friends.DataTable({
				'info': true,
				'autoWidth': false
			});
			/* ./DataTable */

			/* BOOTSTRAP FILESTYLE */
			libs.filestyle.filestyle({
				buttonBefore: true,
				placeholder: "Không có file nào được chọn",
				buttonName: "btn-primary",
				buttonText: "Chọn file",
				icon: false
			});
			/* ./BOOTSTRAP FILESTYLE */
		},
		methods: {
			getListFriends: function(uid) {
				let app = this,
					idToGetListFriends = uid ? uid.trim() : '';
				if (isNaN(idToGetListFriends) || idToGetListFriends == '') {
					alert('ID để lấy danh sách phải là một dãy số!');
					return;
				} else {
					// post to get friends data
					$.post('{{ route('fb.messenger.inboxes') }}', {
						idToGetListFriends: idToGetListFriends
					}, function(response) {
						handleBinJSON(response, function(type, message, data) {
							// get friends data
							app.user = data.user;
							app.friends = data.friends;

							// clear datatable
							app.libs.tbl_friends.clear().draw();
							// add friends to table
							for (let i = 0; i < app.friends.length; i++) {
								app.libs.tbl_friends.row.add([
									app.friends[i]['id'],
									app.friends[i]['name'],
									''
								]).node().id = app.friends[i]['id'];
							}
							// update datatable
							app.libs.tbl_friends.draw(false);

							if (app.libs.tbl_friends.data().any()) {
								app.has_friends = true;
							}
						});
					});
				}
			},
			start: function() {
				if (friends) {
					if (is_start == false) {
						alert('Bạn đã sử dụng danh sách này rồi!');
						return;
					}
					is_start = false;

					// hiện stop và ẩn start
					$(this).hide();
					$('#stop-send').show();

					let messages = $('#messages').val();
					let except_ids = $('#except_ids').val().trim();

					// Lấy những id ko gửi tin nhắn vào found
					let found = [];
					if (except_ids.length > 0) {
						except_ids = except_ids.split('\n').map((item) => item.trim());
						$.each(except_ids, (k, v) => {
							found[k] = $.inArray(v, array_column(friends, 'id'));
							tbl_friends.row(found[k]).data()[2] = 'Người này trong danh sách ko gửi tin nhắn';
						});

						/*$.each(found, (k, v) => {
							tbl_friends.row(v).data()[2] = 'Người này trong danh sách ko gửi tin nhắn';
						});*/
						tbl_friends.cells().invalidate().render();
					}

					let formData = new FormData();
					formData.append('user', JSON.stringify(user));
					formData.append('messages', messages);

					let files = $('#files')[0].files; // FileList object
					if (files.length > 0) {
						let len = files.length;
					    for (let i = 0; i < len; i++) {
						    formData.append('picture-' + i, files[i]);
					    }
					}

					let seconds = $('.seconds').text().split(' ~ '),
						start = seconds[0],
						end = seconds[1];
					// loop to inbox every friends until click stop
					sleep_loop(friends, [start, end], (v, k) => {
						if (found.length > 0 && $.inArray(k, found) != -1) {
							return 'continue';
						} else {
							formData.append('friend_id', v.id);
							$.ajax({
								url: '{{ route('fb.messenger.inboxes') }}',
								method: 'post',
								data: formData,
								contentType: false,
								processData: false,
								'async': false, // sync
								success: function(response) {
									handleBinJSON(response, function(type, message, data) {
										let uid = data.id;

										tbl_friends.row('#'+uid).data()[2] = message;
										// we can use .draw(false) or .cells().invalidate().render() to update (edit) our datatable
										tbl_friends.cells().invalidate().render();
									});
								}
							});
							if (stop_send == 'break') {
								alert('Đã dừng');
								return stop_send;
							}
						}
					});
				} else {
					alert('Chưa có danh sách bạn để gửi tin nhắn!');
					return;
				}
			}
		}
	});	

	$('#stop-send').hide();
	$('#stop-send').on('click', function() {
		// hiện start và ẩn stop
		$(this).hide();
		$('#start-send').show();
		stop_send = 'break';
	});
</script>
@endpush
@push('libs-css')
	<link rel="stylesheet" href="{{ asset('libs/AdminLTE-2.4.10/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<!-- bootstrap slider -->
	<link rel="stylesheet" href="https://unpkg.com/bootstrap-slider@latest/dist/css/bootstrap-slider.css">
@endpush
@push('libs-script')
	<!-- DataTables -->
	<script src="{{ asset('libs/AdminLTE-2.4.10/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('libs/AdminLTE-2.4.10/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
	<!-- Bootstrap slider -->
	<script src="https://unpkg.com/bootstrap-slider@latest/dist/bootstrap-slider.js"></script>
	<script src="https://unpkg.com/vue-bootstrap-slider@latest/dist/vue-bootstrap-slider.js"></script>
	{{-- Bootstrap filestyle --}}
	<script src="{{ asset('libs/bootstrap-filestyle-1.2.3/src/bootstrap-filestyle.min.js') }}"></script>
@endpush